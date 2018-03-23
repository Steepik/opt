<?php

namespace App\Http\Controllers\Admin;

use App\Cart;
use App\HistoryOrders;
use App\Http\Controllers\Controller;
use App\Order;
use App\User;
use Illuminate\Support\Facades\Redis;

class StatsController extends Controller
{
    public function index()
    {
        $u_online = Redis::keys('uonline_*');
        $users = User::isAdmin(false)->get();
        $users->each(function($item, $key) use ($u_online) { // add new attribute to collection "is_online"
            $online_str = 'uonline_' . $item->id;
            if(in_array($online_str, $u_online)) {
                return $item['is_online'] = 1;
            } else {
                return $item['is_online'] = 0;
            }
        });
        $orders_sum = $this->ordersTotalSum();

        return  view('admin.stats.index', compact(['u_online', 'users', 'orders_sum']));
    }

    /**
     * Get total sum of all orders with status 6 - done
     *
     * @return string
     */
    public function ordersTotalSum()
    {
        $orders = Order::where('ptype', '!=', null)->where('sid', 6)->get();
        $sum = 0;
        
        foreach($orders as $order)
        {
            $instance = Cart::getInstanceProductType($order->ptype);
            $product = $instance->where('tcae', $order->tcae)->first();
            if(! is_null($product)) {
                $sum += $product->price_opt * $order->count;
            } else {
                $history = HistoryOrders::where('oid', $order->id)->first();
                $sum += $history->price_opt * $order->count;
            }
        }

        return number_format($sum, 0, ',', ' ');
    }
}
