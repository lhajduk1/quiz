<?php

namespace App\Models;

use Database\Factories\AnswerFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UseFactory(AnswerFactory::class)]
class Answer extends Model
{
    use HasFactory;
    
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    protected function casts()
    {
        return [
            'is_correct' => 'boolean'
        ];
    }
}
