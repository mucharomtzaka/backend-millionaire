<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PuzzleQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'no' => $this->no,
            'clue' => $this->clue,
            'image' => $this->image_url ? url('storage/'.$this->image_url) : null,
            'point' => $this->point,
            'word_length' => Str::length($this->word),
            'word_helper' => Str::substr($this->word, $this->letter_position - 1, 1),
            'position' => $this->letter_position,
            'puzzle_id' => $this->puzzle_id,
            'puzzleTitle' => $this->puzzle->title
        ];
    }
}
