<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RespondResource extends JsonResource
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
            'name' => $this->name,
            'profile' => $this->profile,
            'isFinish' => $this->is_finish,
            'grade' => $this->grade,
            'nominalChallenge' => $this->nominal_challenge,
            'nominalGet' => $this->nominal_get,
            'user' => $this->user->name,
            'quiz' => $this->quiz->title,
        ];
    }
}
