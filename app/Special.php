<?php

namespace App;

use App\Traits\CalcPercent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Special extends Model
{
    use CalcPercent;

    public function brand() {
        return $this->hasOne('App\Brand', 'id', 'brand_id');
    }

    public function getPriceOptAttribute($value)
    {
        return $this->calcPercentForOptPrice($value);
    }

    public function brandAccess()
    {
        return $this->hasMany(BrandAccess::class, 'brand_id', 'brand_id');
    }
}
