<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Comment;
use App\HistoryOrders;
use App\Order;
use Illuminate\Http\Request;
use App\Tire;
use App\Truck;
use App\Special;
use App\Wheel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use NumberToWords\NumberToWords;
use App\OrderMerges;

class OrderController extends Controller
{

    /**
     * @var Order
     */
    public $order;

    /**
     * @var OrderMerges
     */
    public $m_order;

    /**
     * @var HistoryOrders
     */
    public $h_order;

    public function __construct(Order $order, OrderMerges $m_order, HistoryOrders $h_order)
    {
        $this->order = $order;
        $this->m_order = $m_order;
        $this->h_order = $h_order;
    }

    public function index($id)
    {
        $user = Auth::user();
        $products = array();
        $order_id = $id;
        $result = $user->orders()->where('id', $id)->get();
        if(!$result->isEmpty()) {
            foreach ($result as $order) {
                if ($order->ptype != null) {
                    $instance = Cart::getInstanceProductType($order->ptype);
                    $products = $instance->where('tcae', $order->tcae)->first();
                    if($products != null) {
                        $products['oid'] = $order->id;
                        $products['cnum'] = $order->cnum;
                        $products['count'] = $order->count;
                        $products['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('d.m.y H:i');
                        $products['uptime'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->updated_at)->format('d.m.y H:i');
                        $products['status'] = $order->status->text;
                        $products['sid'] = $order->status->id;
                        $products['comments'] = $order->comments()->where('oid', $id)->orderBy('created_at', 'ASC')->get();
                    } else {
                        if($order->sid == 4 or $order->sid == 6 or $order->sid == 7) {
                            $products = $this->h_order->where('oid', $order->id)->first();
                            $products['oid'] = $order->id;
                            $products['cnum'] = $order->cnum;
                            $products['count'] = $order->count;
                            $products['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('d.m.y H:i');
                            $products['uptime'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->updated_at)->format('d.m.y H:i');
                            $products['status'] = $order->status->text;
                            $products['sid'] = $order->status->id;
                            $products['comments'] = $order->comments()->where('oid', $id)->orderBy('created_at', 'ASC')->get();
                        } else {
                            //delete order
                            $this->order->destroy($order->id);
                            $this->h_order->where('oid', $order->id)->delete();
                            return redirect(route('home'))->with('info-msg', Lang::get('messages.p_deleted', ['num' => $order->cnum]));
                        }
                    }
                }
            }
        } else {
            return redirect(route('home'));
        }
        //if new comment was added check it as read
        $get_order = $user->orders()->find($id);
        if($get_order->commented == true) {
            $user->orders()->where('id', $id)->update(['commented' => 0]);
        }

        return view('order.index', compact(['products', 'order_id']));
    }

    public function showMergedOrder($id)
    {
        $user = Auth::user();
        $products = array();
        $order = $user->orders()->find($id);
        $m_products = $this->getProductsForMergedOrder($order->cnum);
        if($order and $m_products->count() > 1) {
            if ($order->ptype == NULL and !$m_products->isEmpty()) { // wheels
                $products['products'] = $m_products;
                $products['oid'] = $order->id;
                $products['cnum'] = $order->cnum;
                $products['count'] = $order->count;
                    //$products['pcount'] =
                $products['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('d.m.y H:i');
                $products['uptime'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->updated_at)->format('d.m.y H:i');
                $products['status'] = $order->status->text;
                $products['sid'] = $order->status->id;
                $products['price'] = $this->getMergedOrdersPrice($order->cnum);
                $products['comments'] = $order->comments()->where('oid', $id)->orderBy('created_at', 'ASC')->get();
            } else {
                $this->destroy($id);
                return redirect(route('home'))->with('info-msg', Lang::get('messages.p_deleted', ['num' => $order->cnum]));
                }
        } elseif($order and $m_products->count() < 2) {
            $single_order = $this->m_order->where('cnum', $order->cnum)->first();
            $sorder = $this->order->find($single_order->oid);
            $sorder->merged = 0;
            $sorder->save();
            $this->order->destroy($id);

            return redirect(route('home'));
        } else {
            return redirect(route('home'))->with('info-msg', Lang::get('messages.p_deleted', ['num' => $order->cnum]));
        }

        return view('order.merged', compact('products'));
    }

    public function addComment(Comment $comment, Request $request)
    {
        $comment->create([
            'uid'  => Auth::user()->id,
            'oid'  => $request->oid,
            'text' => $request->text
        ]);

        return redirect()->back()->with('c_added', '');
    }

    public function orderBill(NumberToWords $ntw, $id)
    {
        $user = Auth::user();
        $products = array();
        $result = $user->orders()->where('id', $id)->get();
        if (!$result->isEmpty() and $result[0]['sid'] == 2) { // not empty and has status "ready to go waiting for pay"
            foreach ($result as $order) {
                if ($order->ptype != null) {
                    $instance = Cart::getInstanceProductType($order->ptype);
                    $products = $instance->where('tcae', $order->tcae)->first();
                    $products['oid'] = $order->id;
                    $products['cnum'] = $order->cnum;
                    $products['count'] = $order->count;
                    $products['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('d.m.y');
                    $products['uptime'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->updated_at)->addDays(3)->format('d.m.y');
                    $products['status'] = $order->status->text;
                    $products['sid'] = $order->status->id;
                    $products['comments'] = $order->comments()->where('oid', $id)->orderBy('created_at', 'ASC')->get();
                }
            }
            return view('order.bill', compact(['products', 'ntw']));
        } else {
            return redirect()->back();
        }
    }

    public function orderMergeBill(NumberToWords $ntw, $id)
    {
        $user = Auth::user();
        $products = array();
        $result = $user->orders()->where('id', $id)->get();
        if (!$result->isEmpty() and $result[0]['sid'] == 5) { // not empty and has status "ready to go waiting for pay"
            foreach ($result as $order) {
                if ($order->ptype == NULL) { // wheels
                    $products['products'] = $this->getProductsForMergedOrder($order->cnum);
                    $products['oid'] = $order->id;
                    $products['cnum'] = $order->cnum;
                    $products['count'] = $order->count;
                    //$products['pcount'] =
                    $products['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at  )->format('d.m.y H:i');
                    $products['uptime'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->updated_at)->addDays(3)->format('d.m.y');
                    $products['status'] = $order->status->text;
                    $products['sid'] = $order->status->id;
                    $products['price'] = $this->getMergedOrdersPrice($order->cnum);
                    $products['comments'] = $order->comments()->where('oid', $id)->orderBy('created_at', 'ASC')->get();
                }
            }

            return view('order.bill-m', compact(['products', 'ntw']));
        } else {
            return redirect()->back();
        }
    }

    public function invoice()
    {
        return view('invoice');
    }

    /**
     * Get products for merged order
     *
     * @param $cnum
     * @return array
     */
    public function getProductsForMergedOrder($cnum)
    {
        $data = collect();
        $result = $this->m_order->where('cnum', $cnum)->get();
        if(! $result->isEmpty()) {
            foreach ($result as $item) {
                foreach ($item->orders as $order) {
                    $instance = Cart::getInstanceProductType($order->ptype);
                    $product = $instance->where('tcae', $order->tcae)->first();
                    if (!is_null($product)) { //if product not found | was deleted
                        $product['count'] = $order->count; //common count for each product
                        $data->push($product);
                    } else {
                        $history = $this->h_order->where('oid', $order->id)->first();
                        if ($order->sid == 4 or $order->sid == 6 or $order->sid == 7) {
                            $history['count'] = $order->count; //common count for each product
                            $data->push($history);
                        } else {
                            //decrease total count of merged order
                            $m_order = $this->order->find($item->mid);
                            $m_order->count -= $order->count;
                            $m_order->save();

                            $this->order->destroy($order->id);
                            $this->h_order->where('oid', $order->id)->delete();
                            Session::flash('info-msg',  Lang::get('messages.p_deleted_name', ['name' => $history->name]));
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function getMergedOrdersPrice($cnum)
    {
        $price = 0;
        $result = $this->m_order->where('cnum', '=', $cnum)->get();
        foreach ($result as $item) {
            foreach ($item->orders as $order) {
                $instance = Cart::getInstanceProductType($order->ptype);
                $tire = $instance->where('tcae', $order->tcae)->first();
                if(! is_null($tire)) {
                    $price += $tire->price_opt * $order->count;
                } else {
                    $h_info = $this->h_order->where('oid', $order->id)->first();
                    $price += $h_info->price_opt * $order->count;
                }
            }
        }

        return $price;
    }
}
