<?php

namespace App;

use App\Traits\CalcPercent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Wheel extends Model
{
    use CalcPercent;

    protected $fillable = ['brand_id', 'name', 'image', 'code', 'twidth', 'tdiameter', 'hole_count',
        'pcd', 'et', 'model', 'et', 'dia', 'tcae', 'type', 'price_opt', 'price_roz', 'quantity',
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

    public function scopeBrandImage($query, $brand_name)
    {
        $image_id = DB::table('manufacturer')->select('manufacturer_id')->where('name', 'like', $brand_name . '%');

        return $image_id;
    }
}
