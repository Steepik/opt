<?php

namespace App;

use App\Traits\CalcPercent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Tire extends Model
{
    use CalcPercent;

    protected $fillable = [
        'brand_id', 'name', 'image', 'code', 'twidth', 'tprofile', 'tdiameter',
        'load_index', 'speed_index', 'tseason', 'model', 'tcae', 'spike',
        'model_class', 'price_opt', 'price_roz', 'quantity'
    ];

    public function getPriceOptAttribute($value)
    {
        return $this->calcPercentForOptPrice($value);
    }

    public function brand()
    {
        return $this->hasOne('App\Brand', 'id', 'brand_id');
    }

    public function brandAccess()
    {
        return $this->hasMany(BrandAccess::class, 'brand_id', 'brand_id');
    }

    /**
     * Get brand's image url from torgshina.com
     *
     * @param $query
     * @param $brand_name
     * @return mixed
     */
    public function scopeBrandImage($query, $brand_name)
    {
        $image_id = DB::table('manufacturer')->select('manufacturer_id')->where('name', 'like', $brand_name . '%');

        return $image_id;
    }
}
