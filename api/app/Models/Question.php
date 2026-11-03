<?php

namespace App\Models;

use Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UseFactory(QuestionFactory::class)]
class Question extends Model
{
    use HasFactory;
    
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
