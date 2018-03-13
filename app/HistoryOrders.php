<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryOrders extends Model
{
    protected $fillable = [
        'uid', 'oid', 'brand_id', 'name', 'spike',
        'tseason', 'price_opt', 'price_roz', 'image'
    ];

    public function orders()
    {
        return $this->hasMany('App\Order', 'oid', 'id');
    }

    public function brand()
    {
        return $this->hasOne('App\Brand', 'id', 'brand_id');
    }
}
