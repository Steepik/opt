<?php

namespace App\Http\Controllers\Admin;

use App\Cart;
use App\HistoryOrders;
use App\Http\Controllers\Controller;
use App\Mail\AccessGiven;
use App\Mail\SendNotifyStatus;
use App\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{

    /**
     * @var Order
     */
    public $order;

    /**
     * @var User
     */
    public $user;

    /**
     * @var HistoryOrders
     */
    public $history;
    
    /**
     * AdminController constructor.
     * @param Order $order
     * @param User $user
     */
    public function __construct(Order $order, User $user, HistoryOrders $history)
    {
        $this->order = $order;
        $this->user = $user;
        $this->history = $history;
    }

    public function index()
    {
        $users = $this->user->isAdmin(0)->get();
        $access = $this->user->hasAccess(0)->get();
        $orders = $this->order->doneOrders()->get();
        $s_wait = $this->order->waitStatus()->get();
        $chart = $this->getDataForChart();
        //get leaders
        $info = collect();
        foreach($this->user->all() as $user) {
            $sum = 0;

            //get orders for current month and year
            $orders_list = $user->orders()
                ->whereMonth('updated_at', date('m'))
                ->whereYear('updated_at', date('Y'))
                ->where('ptype', '!=', null)
                ->where('sid', 6)
                ->get();

            foreach($orders_list as $order) {
                $instance = Cart::getInstanceProductType($order->ptype);

                $product = $instance->where('tcae', $order->tcae)->first();

                if(! is_null($product)) {
                    $sum += $product->price_opt * $order->count;
                } else {
                    $history = $this->history->where('oid', $order->id)->first();
                    $sum += $history->price_opt * $order->count;
                }
            }

            $info[] = [
                'legal_name' => $user->legal_name,
                'sum' => $sum,
            ];

            unset($sum);
        }

        $sorted = $info->where('sum', '>', 0)->sortByDesc('sum');

        $leaders = $sorted->take(3);

        return view('admin.dashboard', compact('users', 'access', 'orders', 's_wait', 'chart', 'leaders'));
    }

    public function userModeration()
    {
        $users = $this->user->hasAccess(0)->get();

        return view('admin.list.moder', compact('users'));
    }

    public function giveAccess(Request $request) {
        $uid = $request->uid;
        $action = $request->action;
        $user = $this->user->find($uid);

        if($action == 'update') {
            $user->access = true;

            if($user->save()) {
                Mail::to($user->email)->send(new AccessGiven($user));
            }

            return redirect()->back()->with('updated', $user->name);
        } elseif($action == 'delete') {
            $this->user->destroy($uid);

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
        $orders = $this->order->waitStatus()->get();

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
        $order = $this->order->find($oid);

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
            $data[] = $this->order->whereYear('updated_at', date('Y'))
                ->whereMonth('updated_at', '=', $m)
                ->where('sid', 6)
                ->where('merged', 0)
                ->get()
                ->count();
        }

        return json_encode(array_values($data));
    }
}
