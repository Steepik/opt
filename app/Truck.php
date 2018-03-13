<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $fillable = [
        'brand_id', 'name', 'image', 'code', 'twidth', 'tprofile', 'tdiameter',
        'load_index', 'speed_index', 'tseason', 'model', 'tcae',
        'spike', 'price_opt', 'price_roz', 'quantity'
    ];

    public function brand() {
        return $this->hasOne('App\Brand', 'id', 'brand_id');
    }
}
