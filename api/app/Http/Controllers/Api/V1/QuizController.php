<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitQuizRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Answer;
use App\Models\Question;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;
use LogicException;

class QuizController extends Controller
{
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

    public function submit(SubmitQuizRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $correctAnswersCount = 0;
        $questionResults = [];

        $answerIds = array_column($validated['answers'], 'answerId');
        $questionIds = array_column($validated['answers'], 'questionId');

        $answers = Answer::whereIn('id', $answerIds)
            ->get();

        $correctAnswers = Answer::whereIn('question_id', $questionIds)
            ->where('is_correct', true)
            ->get()
            ->keyBy('question_id');

        foreach ($validated['answers'] as $index => $answer) {
            $answerModel = $answers->find($answer['answerId']);

            if (!$answerModel || $answerModel->question_id !== $answer['questionId']) {
                throw ValidationException::withMessages([
                    'answers.' . $index . '.answerId' => 'Answer does not belong to this question.',
                ]);
            }

            $correctAnswer = $correctAnswers->get($answer['questionId']);
            $answeredCorrectly = $correctAnswer->id === $answerModel->id;

            if (!$correctAnswer) {
                throw new LogicException(
                    "Question {$answer['questionId']} has no correct answer."
                );
            }

            $questionResults[] = [
                'id' => $correctAnswer->question_id,
                'answeredCorrectly' => $answeredCorrectly,
                'selectedAnswerId' => $answerModel->id,
                'correctAnswerId' => $correctAnswer->id,
            ];

            if ($answeredCorrectly) {
                $correctAnswersCount++;
            }
        }

        return response()->json([
            'data' => [
                'correctAnswersCount' => $correctAnswersCount,
                'questionsCount' => count($validated['answers']),
                'questions' => $questionResults
            ]
        ]);
    }
}
