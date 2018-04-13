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

        $sumByYear = $this->ordersTotalSumByCurrent('year');
        $sumByMonth = $this->ordersTotalSumByCurrent('month');

        return  view('admin.stats.index', compact(['u_online', 'users', 'sumByYear', 'sumByMonth']));
    }

    /**
     * Get total sum of orders
     *
     * @param $by - sort by year | month
     * @return string
     */
    public function ordersTotalSumByCurrent($by)
    {
        $sum = 0;

        if($by == 'year') {
            $orders = Order::where('ptype', '!=', null)
                ->whereYear('updated_at', date('Y'))
                ->where('sid', 6)->get();
        } elseif($by == 'month') {
            $orders = Order::where('ptype', '!=', null)
                ->whereYear('updated_at', date('Y'))
                ->whereMonth('updated_at', date('m'))
                ->where('sid', 6)->get();
        } else {
            $orders = collect();
        }

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
