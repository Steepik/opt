<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderMerges extends Model
{
    protected $fillable = ['uid', 'oid', 'mid', 'cnum', 'tcae'];

    public function orders()
    {
        return $this->hasMany('App\Order', 'id', 'oid');
    }
}
