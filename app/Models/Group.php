<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $guarded = [];

    public function joins()
    {
        return $this->hasMany(JoinGroup::class, 'group_id', 'id');
    }

}
