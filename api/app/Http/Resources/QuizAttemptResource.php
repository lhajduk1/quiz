<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizAttemptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'correctAnswersCount' => $this->correct_answers_count,
            'questionsCount' => $this->questions_count,
            'startedAt' => $this->started_at,
            'finishedAt' => $this->finished_at,
            'quizAttemptQuestions' => QuizAttemptQuestionResource::collection(
                $this->whenLoaded('quizAttemptQuestions')
            )
        ];
    }
}
