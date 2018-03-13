<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['uid', 'oid', 'text'];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'uid');
    }
}
