<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $guarded = [];

    public function options(): HasMany
    {
        return $this->hasMany(Option::class, 'questions_id', 'id');
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'id');
    }

    public function user_response()
    {
        return $this->hasOne(DetailResponse::class, 'question_id', 'id');
    }

    public function response()
    {
        return $this->hasOneThrough(
            Response::class,
            DetailResponse::class,
            'question_id',            // Foreign key in DetailResponse for Question
            'id',                     // Foreign key in Response for DetailResponse
            'id',                     // Primary key in Question
            'response_id'             // Foreign key in DetailResponse for Response
        );
    }
}
