<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function quiz()
    {
        return $this->hasMany(Quiz::class, 'quiz_id', 'id');
    }
}
