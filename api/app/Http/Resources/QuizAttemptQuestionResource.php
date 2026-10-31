<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizAttemptQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'questionId' => $this->question_id,
            'questionSnapshot' => $this->question_snapshot,
            'answersSnapshot' => $this->answers_snapshot
        ];
    }
}
