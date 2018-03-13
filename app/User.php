<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'legal_name',
        'inn', 'city', 'street', 'house',
        'phone', 'access', 'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function orders()
    {
        return $this->hasMany('App\Order','uid', 'id')->orderBy('created_at', 'DESC');
    }

    public function scopeIsAdmin($query, $bool)
    {
        return $query->where('is_admin', $bool);
    }

    public function scopeHasAccess($query, $bool)
    {
        return $query->where('access', $bool)->where('is_admin', 0);
    }
}
