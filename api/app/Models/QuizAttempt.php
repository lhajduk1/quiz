<?php

namespace App\Models;

use App\Enums\QuizAttemptStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    protected $fillable = [
        'status',
        'correct_answers_count',
        'questions_count',
        'started_at',
        'finished_at'
    ];

    protected function casts(): array
    {
        return [
            'status' => QuizAttemptStatus::class,
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function quizAttemptQuestions(): HasMany
    {
        return $this->hasMany(QuizAttemptQuestion::class);
    }
}
