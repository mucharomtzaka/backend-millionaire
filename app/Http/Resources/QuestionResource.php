<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'no' => $this->no,
            'question' => $this->question_text,
            'type' => $this->question_type,
            'image_url' =>  $this->image_url ? url('storage/'.$this->image_url) : null,
            'audio_url' => $this->audio_url,
            'point' => $this->point,
            'quiz'=> $this->quiz->title,
            'idQuiz'=> $this->quiz->id,
            'options' => OptionResource::collection($this->options)
        ];
    }
}
