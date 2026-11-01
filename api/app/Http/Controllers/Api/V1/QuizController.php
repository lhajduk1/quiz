<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\QuizAttemptStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitQuizRequest;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuizAttemptResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\QuizAttempt;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use LogicException;

class QuizController extends Controller
{
    private const int LIMIT = 10;

    public function index(Request $request): AnonymousResourceCollection
    {
        $limit = $request->integer('limit', 10);
        $limit = max(1, min($limit, 50));

        $questions = Question::with('answers')
            ->inRandomOrder()
            ->limit($limit)
            ->get();

        return QuestionResource::collection($questions);
    }

    public function store(Request $request): QuizAttemptResource
    {
        $questions = Question::with('answers')
            ->whereHas('answers', function (Builder $query) {
                $query->where('is_correct', true);
            })
            ->inRandomOrder()
            ->limit(self::LIMIT)
            ->get();

        if ($questions->count() < self::LIMIT) {
            throw new Exception(
                'Must be ' . self::LIMIT . ' valid questions.'
            );
        }

        $result = DB::transaction(function () use ($questions) {
            $quizAttempt = QuizAttempt::create([
                'status' => QuizAttemptStatus::STARTED,
                'questions_count' => $questions->count(),
                'started_at' => Date::now(),
            ]);

            foreach ($questions as $index => $question) {
                $correctAnswer = $question->answers->firstWhere('is_correct', true);

                if (!$correctAnswer) {
                    throw new LogicException(
                        "Question {$question->id} has no correct answer."
                    );
                }

                $quizAttempt->quizAttemptQuestions()->create([
                    'question_id' => $question->id,
                    'correct_answer_id' => $correctAnswer->id,
                    'question_snapshot' => $question->question,
                    'correct_answer_snapshot' => $correctAnswer->text,
                    'answers_snapshot' => $question->answers->map(function ($answer) {
                        return [
                            'id' => $answer->id,
                            'text' => $answer->text
                        ];
                    }),
                    'position' => $index,
                ]);
            }

            return $quizAttempt->load([
                'quizAttemptQuestions' => fn($query) => $query->orderBy('poistion')
            ]);
        });

        return new QuizAttemptResource($result);
    }

    public function submit(SubmitQuizRequest $request, int $id): QuizAttemptResource
    {
        $validated = $request->validated();

        $quizAttempt = DB::transaction(function () use ($validated, $id) {
            $quizAttempt = QuizAttempt::with('quizAttemptQuestions')
                ->whereId($id)
                ->lockForUpdate()
                ->where('status', QuizAttemptStatus::STARTED)
                ->first();

            if (!$quizAttempt) {
                throw ValidationException::withMessages([
                    'id' => "Quiz attempt not found with given id: {$validated['id']} or was finished."
                ]);
            }


            if ($quizAttempt->questions_count !== count($validated['answers'])) {
                throw ValidationException::withMessages([
                    'answers' => "Number of answers is invalid."
                ]);
            }

            $correctAnswersCount = 0;

            foreach ($validated['answers'] as $index => $answer) {
                $questionAttempt = $quizAttempt->quizAttemptQuestions->firstWhere('question_id', $answer['questionId']);

                if (!$questionAttempt) {
                    throw ValidationException::withMessages([
                        'answers.' . $index . '.questionId' => "Question with id: {$answer['questionId']} does not belong to this attempt."
                    ]);
                }

                $answerSnapshot = collect($questionAttempt->answers_snapshot)->firstWhere('id', $answer['answerId']);

                if (!$answerSnapshot) {
                    throw ValidationException::withMessages([
                        'answers.' . $index . '.answerId' => 'Answer does not belong to this question.',
                    ]);
                }

                $questionAttempt->update([
                    'user_answer_id' => $answerSnapshot['id'],
                    'user_answer_snapshot' => $answerSnapshot['text'],
                ]);

                if ($questionAttempt->correct_answer_id === $answerSnapshot['id']) {
                    $correctAnswersCount++;
                }
            }

            $quizAttempt->update([
                'correct_answers_count' => $correctAnswersCount,
                'status' => QuizAttemptStatus::FINISHED,
                'finished_at' => Date::now()
            ]);

            return $quizAttempt;
        });

        $quizAttempt->quizAttemptQuestions->each(
            fn($attempt) => $attempt->setRelation('quizAttempt', $quizAttempt)
        );

        return new QuizAttemptResource($quizAttempt);
    }
}
