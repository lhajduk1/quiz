<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttemptQuestion extends Model
{
    protected $fillable = [
        'quiz_attempt_id',
        'question_id',
        'correct_answer_id',
        'user_answer_id',
        'question_snapshot',
        'answers_snapshot',
        'user_answer_snapshot',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'answers_snapshot' => 'array',
        ];
    }

    public function quizAttempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function correctAnswer(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'correct_answer_id');
    }

    public function userAnswer(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'user_answer_id');
    }
}
