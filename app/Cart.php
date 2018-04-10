<?php

namespace App;

class Cart
{

    /**
     *  Clear cart
     *
     *  return void
     */
    final public static function clearCart(): void
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
        } else {
            $result = array();
        }

        return $result;
    }

    /**
     * Check if cart has the same product and return product's key in array
     *
     * @param $product
     * @return int|null
     */
    final public static function ExistTheSameProduct($product)
    {
        $session = Session();
        $products = !empty($session->get('products')) ? $session->get('products') : [];
        $is_exist = null;
        foreach($products as $key => $item) {
            if($product->tcae == $item['cae']) {
                $is_exist = $key;
            }
        }

        return $is_exist;
    }
}