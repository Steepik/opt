<?php

namespace App\Http\Controllers\Admin;

use App\Cart;
use App\GlobalNotify;
use App\Http\Controllers\Controller;
use App\Mail\AccessGiven;
use App\Mail\SendNotifyStatus;
use App\NotifyUsers;
use App\Order;
use App\Special;
use App\Tire;
use App\Truck;
use App\User;
use App\Wheel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::isAdmin(0)->get();
        $access = User::hasAccess(0)->get();
        $orders = Order::doneOrders()->get();
        $s_wait = Order::waitStatus()->get();
        $chart = $this->getDataForChart();

        return view('admin.dashboard', compact('users', 'access', 'orders', 's_wait', 'chart'));
    }

    public function userModeration()
    {
        $users = User::hasAccess(0)->get();
        return view('admin.list.moder', compact('users'));
    }

    public function giveAccess(Request $request) {
        $uid = $request->uid;
        $action = $request->action;
        $user = User::find($uid);

        if($action == 'update') {
            $user->access = true;

            if($user->save()) {
                Mail::to($user->email)->send(new AccessGiven($user));
            }

            return redirect()->back()->with('updated', $user->name);
        } elseif($action == 'delete') {
            User::destroy($uid);

            return redirect()->back()->with('deleted', '');
        } elseif($action == 'blocked') {
            $user->access = false;

            if($user->save()) {
                return redirect()->back()->with('blocked', '');
            }
        } elseif($action == 'pay_beznal') {
            $user->payment_type = 0;

            if($user->save()) {
                return redirect()->back()->with('payment', '');
            }
        } elseif($action == 'pay_nal') {
            $user->payment_type = 1;

            if($user->save()) {
                return redirect()->back()->with('payment', '');
            }
        }else {
            return redirect()->back();
        }
    }

    public function productModeration()
    {
        $orders = Order::waitStatus()->get();
        foreach($orders as $key => $order) {
            $data = Cart::getInstanceProductType($order->ptype);
            $result = $data->where('tcae', $order->tcae)->first();
            $orders[$key]['pname'] = $result->name;
            $orders[$key]['price'] = $result->price_opt;
        }
        return view('admin.list.pcheck', compact('orders'));
    }

    public function productAction(Request $request) {
        $oid = $request->oid;
        $action = $request->action;
        $order = Order::find($oid);

        if($action == 'update') {
            $order->sid = 2; // set status ready to go
            if($order->save()) {
                Mail::to($order->user->email)->send(new SendNotifyStatus($order));
                return redirect()->back()->with('updated', '');
            }
        } elseif($action == 'delete') {
            $order->sid = 7; // set status canceled by moder

            if($order->save()) {
                Mail::to($order->user->email)->send(new SendNotifyStatus($order));
                return redirect()->back()->with('deleted', '');
            }
        } else {
            return redirect()->back();
        }
    }

    /**
     * For admin page diagram
     *
     * Get order's count for each month of current year where status = 6
     *
     * @return string
     */
    public function getDataForChart()
    {
        $month = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12); //num list of month
        $data = array();
        foreach($month as $m) {
            $data[] = Order::whereYear('updated_at', date('Y'))
                ->whereMonth('updated_at', '=', $m)
                ->where('sid', 6)
                ->where('merged', 0)
                ->get()
                ->count();
        }
        return json_encode(array_values($data));
    }
}
