<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailResponse extends Model
{
    protected $guarded = [];

    public function questions(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function response(): BelongsTo
    {
        return $this->belongsTo(Response::class, 'response_id', 'id');
    }
}
