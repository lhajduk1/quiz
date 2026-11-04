<?php

use App\Models\Answer;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptQuestion;
use Illuminate\Support\Facades\Date;

function createQuizQuestions(int $count = 10): void
{
    Question::factory()
        ->count($count)
        ->has(
            Answer::factory()
                ->count(2)
        )->has(
            Answer::factory()
                ->count(1)
                ->correct()
        )->create();
}

test('it creates a new quiz attempt', function () {
    createQuizQuestions();

    $response = $this->postJson('/api/quiz/attempts');

    $response
        ->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'status'
            ]
        ])
        ->assertJsonPath('data.status', 'started')
        ->assertJsonCount(10, 'data.quizAttemptQuestions')
        ->assertJsonMissingPath('data.quizAttemptQuestions.0.correctAnswerId')
        ->assertJsonMissingPath('data.quizAttemptQuestions.0.answeredCorrectly')
        ->assertJsonCount(3, 'data.quizAttemptQuestions.0.answersSnapshot');
});

test('it does not create a quiz attempt when fewer than ten valid questions exist', function () {
    createQuizQuestions(5);

    $response = $this->postJson('/api/quiz/attempts');

    $response->assertStatus(409)
        ->assertJsonPath('message', 'Must be 10 valid questions.');

    $this->assertDatabaseCount('quiz_attempts', 0);
});

test('it submits a quiz attempt with correct answers', function () {
    createQuizQuestions();

    $response = $this->postJson('/api/quiz/attempts');

    $attemptId = $response->json('data.id');

    $quizAttempt = QuizAttempt::with('quizAttemptQuestions')
        ->findOrFail($attemptId);

    $answers = $quizAttempt->quizAttemptQuestions->map(function ($question) {
        return [
            'questionId' => $question->question_id,
            'answerId' => $question->correct_answer_id
        ];
    })->all();

    $response = $this->postJson(
        "/api/quiz/attempts/{$attemptId}/submit",
        ['answers' => $answers]
    );

    $response
        ->assertOk()
        ->assertJsonPath('data.status', 'finished')
        ->assertJsonPath('data.correctAnswersCount', 10);

    $this->assertDatabaseHas('quiz_attempts', [
        'id' => $attemptId,
        'status' => 'finished',
        'correct_answers_count' => 10
    ]);
});

test('it calculates score with partially incorrect answers', function () {
    createQuizQuestions();

    $response = $this->postJson('/api/quiz/attempts');

    $attemptId = $response->json('data.id');

    $quizAttempt = QuizAttempt::with('quizAttemptQuestions.question.answers')
        ->findOrFail($attemptId);

    $answers = $quizAttempt->quizAttemptQuestions->map(function ($question, $index) {
        $answerId = $index >= 7
            ? $question->question->answers
            ->firstWhere('is_correct', false)
            ->id
            : $question->correct_answer_id;

        return [
            'questionId' => $question->question_id,
            'answerId' => $answerId
        ];
    })->all();

    $response = $this->postJson(
        "/api/quiz/attempts/{$attemptId}/submit",
        ['answers' => $answers]
    );

    $response
        ->assertOk()
        ->assertJsonPath('data.status', 'finished')
        ->assertJsonPath('data.correctAnswersCount', 7);

    $this->assertDatabaseHas('quiz_attempts', [
        'id' => $attemptId,
        'status' => 'finished',
        'correct_answers_count' => 7
    ]);
});

test('it rejects quiz submission without answers', function () {
    createQuizQuestions();

    $response = $this->postJson('/api/quiz/attempts');

    $attemptId = $response->json('data.id');

    $response = $this->postJson(
        "/api/quiz/attempts/{$attemptId}/submit",
        []
    );

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('answers');

    $this->assertDatabaseHas('quiz_attempts', [
        'id' => $attemptId,
        'status' => 'started',
        'correct_answers_count' => null,
    ]);
});

test('it rejects quiz submission with duplicate question ids', function () {
    createQuizQuestions();

    $response = $this->postJson('/api/quiz/attempts');

    $attemptId = $response->json('data.id');

    $quizAttempt = QuizAttempt::with('quizAttemptQuestions')
        ->findOrFail($attemptId);

    $answers = $quizAttempt->quizAttemptQuestions->map(function ($question) {
        return [
            'questionId' => $question->question_id,
            'answerId' => $question->correct_answer_id
        ];
    })->all();

    $answers[9]['questionId'] = $answers[8]['questionId'];

    $response = $this->postJson(
        "/api/quiz/attempts/{$attemptId}/submit",
        ['answers' => $answers]
    );

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors('answers.8.questionId')
        ->assertJsonValidationErrors('answers.9.questionId');

    $this->assertDatabaseHas('quiz_attempts', [
        'id' => $attemptId,
        'status' => 'started',
        'correct_answers_count' => null
    ]);
});

test('it rejects an answer belonging to another question', function () {
    createQuizQuestions();

    $response = $this->postJson('/api/quiz/attempts');

    $attemptId = $response->json('data.id');

    $quizAttempt = QuizAttempt::with('quizAttemptQuestions')
        ->findOrFail($attemptId);

    $answers = $quizAttempt->quizAttemptQuestions->map(function ($question) {
        return [
            'questionId' => $question->question_id,
            'answerId' => $question->correct_answer_id
        ];
    })->all();

    $answers[0]['answerId'] = $answers[1]['answerId'];

    $response = $this->postJson(
        "/api/quiz/attempts/{$attemptId}/submit",
        ['answers' => $answers]
    );

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors('answers.0.answerId');

    $this->assertDatabaseHas('quiz_attempts', [
        'id' => $attemptId,
        'status' => 'started',
        'correct_answers_count' => null
    ]);
});

test('it prevents resubmitting a finished quiz attempt', function () {
    createQuizQuestions();

    $response = $this->postJson('/api/quiz/attempts');

    $attemptId = $response->json('data.id');

    $quizAttempt = QuizAttempt::with('quizAttemptQuestions.question.answers')
        ->findOrFail($attemptId);

    $answers = $quizAttempt->quizAttemptQuestions->map(function ($question, $index) {
        $answerId = $index >= 7
            ? $question->question->answers
            ->firstWhere('is_correct', false)
            ->id
            : $question->correct_answer_id;

        return [
            'questionId' => $question->question_id,
            'answerId' => $answerId
        ];
    })->all();

    $response = $this->postJson(
        "/api/quiz/attempts/{$attemptId}/submit",
        ['answers' => $answers]
    );

    $answersBefore = QuizAttemptQuestion::where('quiz_attempt_id', $attemptId)
        ->orderBy('position')
        ->pluck('user_answer_id')
        ->all();

    $response
        ->assertOk()
        ->assertJsonPath('data.status', 'finished')
        ->assertJsonPath('data.correctAnswersCount', 7);

    $answers = $quizAttempt->quizAttemptQuestions->map(function ($question, $index) {
        return [
            'questionId' => $question->question_id,
            'answerId' => $question->correct_answer_id
        ];
    })->all();

    $response = $this->postJson(
        "/api/quiz/attempts/{$attemptId}/submit",
        ['answers' => $answers]
    );

    $answersAfter = QuizAttemptQuestion::where('quiz_attempt_id', $attemptId)
        ->orderBy('position')
        ->pluck('user_answer_id')
        ->all();

    $response->assertConflict();

    $this->assertDatabaseHas('quiz_attempts', [
        'id' => $attemptId,
        'status' => 'finished',
        'correct_answers_count' => 7
    ]);

    expect($answersAfter)->toBe($answersBefore);
});

test('it returns quiz attempt history', function () {
    $oldest = QuizAttempt::forceCreate([
        'status' => 'finished',
        'correct_answers_count' => 7,
        'questions_count' => 10,
        'started_at' => now()->subDays(3),
        'finished_at' => now()->subDays(3),
        'created_at' => now()->subDays(3),
    ]);

    $middle = QuizAttempt::forceCreate([
        'status' => 'finished',
        'correct_answers_count' => 7,
        'questions_count' => 10,
        'started_at' => now()->subDays(2),
        'finished_at' => now()->subDays(2),
        'created_at' => now()->subDays(2),
    ]);

    $newest = QuizAttempt::forceCreate([
        'status' => 'finished',
        'correct_answers_count' => 7,
        'questions_count' => 10,
        'started_at' => now()->subDay(),
        'finished_at' => now()->subDay(),
        'created_at' => now()->subDay(),
    ]);

    $response = $this->getJson('/api/quiz/attempts');

    $response
        ->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'status',
                    'questionsCount'
                ]
            ]
        ])
        ->assertJsonPath('data.0.id', $newest->id)
        ->assertJsonPath('data.1.id', $middle->id)
        ->assertJsonPath('data.2.id', $oldest->id);
});

test('it does not reveal correct answers for an unfinished quiz attempt', function () {
    createQuizQuestions();

    $response = $this->postJson('/api/quiz/attempts');

    $quizAttemptId = $response->json('data.id');

    $response = $this->getJson("api/quiz/attempts/{$quizAttemptId}");

    $response
        ->assertOk()
        ->assertJsonPath('data.status', 'started')
        ->assertJsonCount(10, 'data.quizAttemptQuestions');

    $questions = $response->json('data.quizAttemptQuestions');

    foreach ($questions as $question) {
        expect($question)
            ->not->toHaveKey('correctAnswerId')
            ->not->toHaveKey('answeredCorrectly');
    }
});

test('it reveals results for a finished quiz attempt', function () {
    createQuizQuestions();

    $response = $this->postJson('/api/quiz/attempts');

    $attemptId = $response->json('data.id');

    $quizAttempt = QuizAttempt::with('quizAttemptQuestions.question.answers')
        ->findOrFail($attemptId);

    $answers = $quizAttempt->quizAttemptQuestions->map(function ($question, $index) {
        $answerId = $index >= 7
            ? $question->question->answers
            ->firstWhere('is_correct', false)
            ->id
            : $question->correct_answer_id;

        return [
            'questionId' => $question->question_id,
            'answerId' => $answerId
        ];
    })->all();

    $response = $this->postJson(
        "/api/quiz/attempts/{$attemptId}/submit",
        ['answers' => $answers]
    );

    $response
        ->assertOk();

    $response = $this->getJson("/api/quiz/attempts/{$attemptId}");

    $response
        ->assertOk()
        ->assertJsonPath('data.status', 'finished')
        ->assertJsonPath('data.correctAnswersCount', 7)
        ->assertJsonCount(10, 'data.quizAttemptQuestions');

    $questions = $response->json('data.quizAttemptQuestions');

    $correctAnswers = 0;

    foreach ($questions as $question) {
        expect($question)
            ->toHaveKey('correctAnswerId')
            ->toHaveKey('answeredCorrectly');

        if ($question['answeredCorrectly']) {
            $correctAnswers++;
        }
    }

    expect($correctAnswers)
        ->toBe(7);
});

test('it returns 404 for a non-existent quiz attempt', function () {
    $response = $this->getJson('api/quiz/attempts/999999');

    $response
        ->assertNotFound();
});

test('it returns 404 for a submit non-existent quiz attempt', function () {
    createQuizQuestions();

    $response = $this->postJson('api/quiz/attempts');

    $response
        ->assertCreated();

    $attemptId = $response->json('data.id');

    $quizAttempt = QuizAttempt::with('quizAttemptQuestions.question.answers')
        ->findOrFail($attemptId);

    $answers = $quizAttempt->quizAttemptQuestions->map(function ($question, $index) {
        return [
            'questionId' => $question->question_id,
            'answerId' => $question->correct_answer_id
        ];
    })->all();

    $response = $this->postJson(
        'api/quiz/attempts/999999/submit',
        ['answers' => $answers]
    );

    $response
        ->assertNotFound();
});
