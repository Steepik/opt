<?php

namespace App\Traits;

use App\Order;
use App\User;
use Illuminate\Support\Facades\Auth;

trait CalcPercent
{
    /**
     * Set new price for opt_price depend on value of percent
     *
     * @param $value
     * @param null $userId
     * @param null $brandId
     * @return number
     */
    public function calcPercentForOptPrice($value, $userId = null, $brandId = null, $percent = null)
    {
        $user = is_null($userId) ? Auth::user() : User::find($userId);
        $brandId = is_null($brandId) ? $this->brand->id : $brandId;

        foreach($user->percent as $userPercent) {
            if ($brandId == $userPercent->brand_id) {
                $percent = is_null($percent) ? $userPercent->percent_value : $percent;

                if ($percent < 0) { // Decrease by percent
                    $coeff = $value / 100;
                    $equil = $coeff * abs($percent); #from negative value to positive
                    $value = $value - $equil;
                } else { // Increase by percent
                    $coeff = $value / 100;
                    $equil = $coeff * $percent;
                    $value = $equil + $value;
                }
            }
        }

        return abs(round($value));
    }

    public function calcPercentForOrder($value, $orderId)
    {
        $percent = Order::findOrFail($orderId)->percent_value;

        if ($percent < 0) { // Decrease by percent
            $coeff = $value / 100;
            $equil = $coeff * abs($percent); #from negative value to positive
            $value = $value - $equil;
        } else { // Increase by percent
            $coeff = $value / 100;
            $equil = $coeff * $percent;
            $value = $equil + $value;
        }

        return abs(round($value));
    }
}