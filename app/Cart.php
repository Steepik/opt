<?php

namespace App;

use Illuminate\Support\Facades\Session;

class Cart
{

    /**
     *  Clear cart
     *
     *  return void
     */
    final public static function clearCart()
    {
        $session = Session();
        $session->forget(['total_price', 'total_count', 'products', 'cart_products']);
    }

    /**
     * Get instance model depend on product type
     *
     * @param $type | 1 - tire, 2 - truck, 3 - special, 4 - wheels
     * @return Special|Tire|Truck|Wheel
     */
    final public static function getInstanceProductType($type)
    {
        if($type == 1) {
            $result = new Tire();
        } elseif($type == 2) {
            $result = new Truck();
        } elseif($type == 3) {
            $result = new Special();
        } elseif($type == 4) {
            $result = new Wheel();
        }

        return $result;
    }
}