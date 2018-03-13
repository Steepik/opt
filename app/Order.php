<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['uid', 'cnum', 'tcae', 'ptype', 'count', 'sid', 'merged', 'archived'];

    public function user()
    {
        return $this->belongsTo('App\User', 'uid', 'id');
    }

    public function status()
    {
        return $this->hasOne('App\StatusText', 'id', 'sid');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment', 'oid', 'id');
    }

    public function scopeDoneOrders($query)
    {
        return $query->whereMonth('updated_at', '=', date('m'))
            ->where('sid', 6)
            ->where('merged', 0); // 6 is mean Order is done
    }

    public function scopeWaitStatus($query)
    {
        return $query->where('sid', 1); // 1 is mean Order is waiting for check quantity
    }
}
