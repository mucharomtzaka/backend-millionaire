<?php

namespace App\Models;

use Doctrine\DBAL\Query\Join;
use Illuminate\Database\Eloquent\Model;

class JoinGroup extends Model
{
    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
