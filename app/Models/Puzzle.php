<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Puzzle extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function question_puzzle()
    {
        return $this->hasMany(PuzzleQuestion::class);
    }

    public function responses()
    {
        return $this->hasMany(ResponsePuzzle::class, 'puzzle_id', 'id');
    }
}
