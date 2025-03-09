<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResponsePuzzle extends Model
{
    protected $guarded = [];

    public function puzzle()
    {
        return $this->belongsTo(Puzzle::class, 'puzzle_id', 'id');
    }

    public function puzzleQuestions()
    {
        return $this->belongsTo(PuzzleQuestion::class, 'puzzle_question_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
