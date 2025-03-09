<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuzzleQuestion extends Model
{
    protected $guarded = [];

    public function puzzle()
    {
        return $this->belongsTo(Puzzle::class, 'puzzle_id', 'id');
    }
}
