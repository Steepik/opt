<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Special extends Model
{
    public function brand() {
        return $this->hasOne('App\Brand', 'id', 'brand_id');
    }
}
