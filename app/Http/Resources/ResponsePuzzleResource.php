<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponsePuzzleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->user->name,
            'answer' => $this->answer,
            'isCorrect' => $this->isCorrect,
            'point' => $this->point,
            'puzzle_question' => $this->puzzleQuestions->clue,
            'puzzle' => $this->puzzle->title
        ];
    }
}
