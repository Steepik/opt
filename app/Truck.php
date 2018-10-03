<?php

namespace App;

use App\Traits\CalcPercent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Truck extends Model
{
    use CalcPercent;

    protected $fillable = [
        'brand_id', 'name', 'image', 'code', 'twidth', 'tprofile', 'tdiameter',
        'load_index', 'speed_index', 'tseason', 'model', 'tcae',
        'spike', 'price_opt', 'price_roz', 'quantity'
    ];

    public function getPriceOptAttribute($value)
    {
        return $this->calcPercentForOptPrice($value);
    }

    public function brand() {
        return $this->hasOne('App\Brand', 'id', 'brand_id');
    }

    public function brandAccess()
    {
        return $this->hasMany(BrandAccess::class, 'brand_id', 'brand_id');
    }
}
