<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Comment;
use App\HistoryOrders;
use App\Order;
use Dotenv\Validator;
use Illuminate\Http\Request;
use App\Tire;
use App\Truck;
use App\Special;
use App\Wheel;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use NumberToWords\NumberToWords;
use App\OrderMerges;

class OrderController extends Controller
{

    public function index($id)
    {
        $user = Auth::user();
        $products = array();
        $order_id = $id;

        $result = $user->orders()->where('id', $id)->get();

        if(!$result->isEmpty()) {
            foreach ($result as $order) {
                if ($order->ptype == 1) { //tires
                    $tires = new Tire();
                    $products = $tires->where('tcae', $order->tcae)->first();
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
                            $history = new HistoryOrders();
                            $products = $history->where('oid', $order->id)->first();
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
                            Order::destroy($order->id);
                            HistoryOrders::where('oid', $order->id)->delete();
                            return redirect(route('home'))->with('info-msg', 'После обновления базы товаров, заказ №' . $order->cnum . ' был удален, так как товара нет в наличии.');
                        }
                    }
                } elseif ($order->ptype == 2) { // trucks
                    $trucks = new Truck();
                    $products = $trucks->where('tcae', $order->tcae)->first();
                    $products['oid'] = $order->id;
                    $products['cnum'] = $order->cnum;
                    $products['count'] = $order->count;
                    $products['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('d.m.y H:i');
                    $products['uptime'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->updated_at)->format('d.m.y H:i');
                    $products['status'] = $order->status->text;
                    $products['sid'] = $order->status->id;
                    $products['comments'] = $order->comments()->where('oid', $id)->orderBy('created_at', 'ASC')->get();
                } elseif ($order->ptype == 3) { // specials
                    $specials = new Special();
                    $products = $specials->where('tcae', $order->tcae)->first();
                    $products['oid'] = $order->id;
                    $products['cnum'] = $order->cnum;
                    $products['count'] = $order->count;
                    $products['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('d.m.y H:i');
                    $products['uptime'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->updated_at)->format('d.m.y H:i');
                    $products['status'] = $order->status->text;
                    $products['sid'] = $order->status->id;
                    $products['comments'] = $order->comments()->where('oid', $id)->orderBy('created_at', 'ASC')->get();
                } elseif ($order->ptype == 4) { // wheels
                    $wheels = new Wheel();
                    $products = $wheels->where('tcae', $order->tcae)->first();
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
                            $history = new HistoryOrders();
                            $products = $history->where('oid', $order->id)->first();
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
                            Order::destroy($order->id);
                            HistoryOrders::where('oid', $order->id)->delete();
                            return redirect(route('home'))->with('info-msg', 'После обновления базы товаров, заказ №' . $order->cnum . ' был удален, так как товара нет в наличии.');
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
                Order::destroy($id);
                return redirect(route('home'))->with('info-msg', 'После обновления базы товаров, заказ №' . $order->cnum . ' был удален, так как товара нет в наличии.');
                }
        } elseif($order and $m_products->count() < 2) {
            $single_order = OrderMerges::where('cnum', $order->cnum)->first();
            $sorder = Order::find($single_order->oid);
            $sorder->merged = 0;
            $sorder->save();
            Order::destroy($id);

            return redirect(route('home'));
        } else {
            return redirect(route('home'))->with('info-msg', 'После обновления базы товаров, заказ №' . $order->cnum . ' был удален, так как товара нет в наличии.');
        }

        return view('order.merged', compact('products'));
    }

    public function addComment(Request $request)
    {
        $comment = new Comment();

        $comment->create([
            'uid'  => Auth::user()->id,
            'oid'  => $request->oid,
            'text' => $request->text
        ]);

        return redirect()->back()->with('c_added', '');
    }

    public function orderBill($id)
    {
        $ntw = new NumberToWords();
        $user = Auth::user();
        $tires = new Tire();
        $trucks = new Truck();
        $specials = new Special();
        $wheels = new Wheel();
        $products = array();

        $result = $user->orders()->where('id', $id)->get();

        if (!$result->isEmpty() and $result[0]['sid'] == 2) { // not empty and has status "ready to go waiting for pay"
            foreach ($result as $order) {
                if ($order->ptype == 1) { //tires
                    $products = $tires->where('tcae', $order->tcae)->first();
                    $products['oid'] = $order->id;
                    $products['cnum'] = $order->cnum;
                    $products['count'] = $order->count;
                    $products['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('d.m.y');
                    $products['uptime'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->updated_at)->addDays(3)->format('d.m.y');
                    $products['status'] = $order->status->text;
                    $products['sid'] = $order->status->id;
                    $products['comments'] = $order->comments()->where('oid', $id)->orderBy('created_at', 'ASC')->get();
                } elseif ($order->ptype == 2) { // trucks
                    $products = $trucks->where('tcae', $order->tcae)->first();
                    $products['oid'] = $order->id;
                    $products['cnum'] = $order->cnum;
                    $products['count'] = $order->count;
                    $products['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('d.m.y');
                    $products['uptime'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->updated_at)->format('d.m.y');
                    $products['status'] = $order->status->text;
                    $products['sid'] = $order->status->id;
                    $products['comments'] = $order->comments()->where('oid', $id)->orderBy('created_at', 'ASC')->get();
                } elseif ($order->ptype == 3) { // specials
                    $products = $specials->where('tcae', $order->tcae)->first();
                    $products['oid'] = $order->id;
                    $products['cnum'] = $order->cnum;
                    $products['count'] = $order->count;
                    $products['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('d.m.y');
                    $products['uptime'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->updated_at)->format('d.m.y');
                    $products['status'] = $order->status->text;
                    $products['sid'] = $order->status->id;
                    $products['comments'] = $order->comments()->where('oid', $id)->orderBy('created_at', 'ASC')->get();
                } elseif ($order->ptype == 4) { // wheels
                    $products = $wheels->where('tcae', $order->tcae)->first();
                    $products['oid'] = $order->id;
                    $products['cnum'] = $order->cnum;
                    $products['count'] = $order->count;
                    $products['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('d.m.y');
                    $products['uptime'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->updated_at)->format('d.m.y');
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

    public function orderMergeBill($id)
    {
        $ntw = new NumberToWords();
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
        $orders = new OrderMerges();
        $data = collect();
        $result = $orders->where('cnum', $cnum)->get();

        if(! $result->isEmpty()) {
            foreach ($result as $item) {
                foreach ($item->orders as $order) {
                    $instance = Cart::getInstanceProductType($order->ptype);
                    $product = $instance->where('tcae', $order->tcae)->first();
                    if (!is_null($product)) { //if product not found | was deleted
                        $product['count'] = $order->count; //common count for each product
                        $data->push($product);
                    } else {
                        $history = HistoryOrders::where('oid', $order->id)->first();
                        if ($order->sid == 4 or $order->sid == 6 or $order->sid == 7) {
                            $history['count'] = $order->count; //common count for each product
                            $data->push($history);
                        } else {
                            //decrease total count of merged order
                            $m_order = Order::find($item->mid);
                            $m_order->count -= $order->count;
                            $m_order->save();

                            Order::destroy($order->id);
                            HistoryOrders::where('oid', $order->id)->delete();
                            Session::flash('info-msg',  'После обновления базы товаров, ' . $history->name . ' был удален из заказа, так как товара нет в наличии.');
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function getMergedOrdersPrice($cnum)
    {
        $merged = new OrderMerges();
        $tires = new Tire();
        $trucks = new Truck();
        $specials = new Special();
        $wheels = new Wheel();
        $price = 0;

        $result = $merged->where('cnum', '=', $cnum)->get();

        foreach ($result as $item) {
            foreach ($item->orders as $order) {
                $instance = Cart::getInstanceProductType($order->ptype);
                $tire = $instance->where('tcae', $order->tcae)->first();
                if(! is_null($tire)) {
                    $price += $tire->price_opt * $order->count;
                } else {
                    $history = new HistoryOrders();
                    $h_info = $history->where('oid', $order->id)->first();
                    $price += $h_info->price_opt * $order->count;
                }
            }
        }

        return $price;
    }
}
