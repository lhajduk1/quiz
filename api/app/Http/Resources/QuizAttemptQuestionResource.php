<?php

namespace App\Http\Resources;

use App\Enums\QuizAttemptStatus;
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
            'answersSnapshot' => $this->answers_snapshot,
            $this->mergeWhen($this->quizAttempt->status === QuizAttemptStatus::FINISHED, [
                'selectedAnswerId' => $this->user_answer_id,
                'correctAnswerId' => $this->correct_answer_id,
                'answeredCorrectly' => $this->user_answer_id === $this->correct_answer_id,
            ])
        ];
    }
}
