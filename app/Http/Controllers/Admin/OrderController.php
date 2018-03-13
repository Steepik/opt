<?php

namespace App\Http\Controllers\Admin;

use App\Cart;
use App\Comment;
use App\HistoryOrders;
use App\Http\Controllers\CartController;
use App\Order;
use App\OrderMerges;
use App\StatusText;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use NumberToWords\NumberToWords;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sql = array('merged' => 0);
        $uid = array();

        if(!empty($request->num)) {
            $arr = ['cnum' => $request->num];
            array_push($sql, array($arr));
        }
        if(!empty($request->legal_name)) {
            $users = User::where('legal_name', 'like', '%' . $request->legal_name . '%')->pluck('id')->all();
            $uid = $users;
        }
        if(!empty($request->start)) {
            $date_start = $request->start;
            $date_end = $request->end . ' 23:59:59';
        } else {
            $date_start = Carbon::now()->firstOfYear();
            $date_end = Carbon::now();
        }

        if(!empty($request->legal_name)) {
            $orders = Order::whereYear('updated_at', '=', date('Y'))
                ->orderBy('updated_at', 'DESC')
                ->where($sql)
                ->whereIn('uid', $uid)
                ->whereBetween('created_at',  [$date_start, $date_end])
                ->paginate(10);
        } else {
            $orders = Order::whereYear('updated_at', '=', date('Y'))
                ->orderBy('updated_at', 'DESC')
                ->where($sql)
                ->whereBetween('created_at',  [$date_start, $date_end])
                ->paginate(10);
        }

        $status_list = StatusText::all();
        return view('admin.list.orders', compact('orders', 'status_list'));
    }

    public function showMergeOrder($id)
    {
        $order = Order::find($id);
        $merged = OrderMerges::where('cnum', $order->cnum)->get();
        $plist = collect();
        $total_sum = 0;
        foreach($merged as $item) {
           foreach($item->orders as $info_order) {
               $product = Cart::getInstanceProductType($info_order->ptype)->where('tcae', $info_order->tcae)->first();
               if(! is_null($product)) {
                   $product['count'] = $info_order->count;
                   $plist->push($product);
                   $total_sum += $product->price_opt * $info_order->count;
               } else {
                   $history = HistoryOrders::where('oid', $info_order->id)->first();
                   $history['count'] = $info_order->count;
                   $plist->push($history);
                   $total_sum += $history->price_opt * $info_order->count;
               }
           }
        }

        return view('admin.order_merge_info', compact('order', 'plist', 'total_sum'));
    }

    public function showOrder($id)
    {
        $order = Order::find($id);
        $pinstance = Cart::getInstanceProductType($order->ptype);
        $product = $pinstance->where('tcae', $order->tcae)->first();
        if(is_null($product)) {
            $product = HistoryOrders::where('oid', $order->id)->first();
        }
        return view('admin.order_info', compact('order', 'product'));
    }

    public function changeOrderStatus(Request $request)
    {
        if($request->ajax()) {
            $order = Order::find($request->oid);
            if($order->ptype != null) {
                $order->sid = $request->sid;
                $order->save();
            } else {
                // set all nested orders status
                $order->sid = $request->sid; //main order set
                $order->save();
                $m_orders = OrderMerges::where('mid', $request->oid)->get();
                foreach ($m_orders as $item) {
                    $order = Order::find($item->oid);
                    $order->sid = $request->sid;
                    $order->save();
                }
            }

            return response()->json(['success' => true, 'sid' => $request->sid, 'text' => strip_tags($order->status->text)]);
        }
    }

    public function addComment(Request $request, $id)
    {
        $comment = new Comment();
        $comment->text = $request->text;
        $comment->uid = Auth::user()->id;
        $comment->oid = $id;

        if($comment->save()) {
            return redirect()->back()->with('success', '');
        }
    }

    public function invoice($id)
    {
        $ntw = new NumberToWords();
        $order = Order::find($id);
        $product = Cart::getInstanceProductType($order->ptype)->where('tcae', $order->tcae)->first();
        if(is_null($product)) {
            $product = HistoryOrders::where('oid', $order->id)->first();
        }
        return view('admin.invoice', compact('order', 'product', 'ntw'));
    }

    public function invoice_merged($id)
    {
        $order = Order::find($id);
        $merged = OrderMerges::where('cnum', $order->cnum)->get();
        $ntw = new NumberToWords();
        $plist = collect();
        $total_sum = 0;
        $total_sum_ntw = 0;
        foreach($merged as $item) {
            foreach($item->orders as $info_order) {
                $product = Cart::getInstanceProductType($info_order->ptype)->where('tcae', $info_order->tcae)->first();
                if(! is_null($product)) {
                    $product['count'] = $info_order->count;
                    $plist->push($product);
                    $total_sum += $product->price_opt * $info_order->count;
                } else {
                    $history = HistoryOrders::where('oid', $info_order->id)->first();
                    $history['count'] = $info_order->count;
                    $plist->push($history);
                    $total_sum += $history->price_opt * $info_order->count;
                }
            }
        }
        // For Number to Word
        $total_sum_ntw = $total_sum * 100;

        return view('admin.invoice_merged', compact('order', 'plist', 'ntw', 'total_sum', 'total_sum_ntw'));
    }

    public function orderAction(Request $request)
    {
       if($request->oid) {
           $orders = new Order();

           if ($request->action == 'del') {
               $merges = new OrderMerges();
               $ids = array();

               foreach($request->oid as $oid) {
                   $order = $orders->where('id', $oid)->first();
                   if ($order->sid != 4) {
                       $merged = $merges->where('cnum', $order->cnum)->get();

                       foreach ($merged as $id) {
                           $ids[] = $id->oid;
                       }
                       $ids[] = (int)$oid; // add common merged order's id to delete
                   }
                   //delete order history
                   HistoryOrders::whereIn('oid', $ids)->delete();
               }

               $orders->destroy($ids);
               return redirect()->back();

           } elseif ($request->action == 'merge') {
               if($this->checkStatus($request->oid) and count($request->oid) > 1) {
                   $merge = new OrderMerges();
                   $cnum = $this->unique_cnum();

                   $orders_info = Order::whereIn('id', $request->oid)->pluck('uid')->all();

                   //check if all selected orders belongs to same user
                   foreach($orders_info as $item) {
                        if($orders_info[0] == $item) {
                            continue;
                        } else {
                            return redirect()->back()->with('merge-error', 'Вы пытаетесь объединить заказы разных пользователей');
                        }
                   }

                   $o_insertId = $orders->create([
                       'uid' => $orders_info[0],
                       'cnum' => $cnum,
                       'ptype' => NULL,
                       'count' => $this->getOrderMergeCount($request->oid),
                       'sid' => 5, //set status to merged
                       'merged' => 0,
                       'archived' => 0
                   ]);

                   foreach ($request->oid as $oid) {
                       $cae = $orders->where('id', $oid)->first(); //get CAE
                       $merge->create([
                           'uid' => Auth::user()->id,
                           'oid' => $oid,
                           'tcae' => $cae->tcae,
                           'mid' => $o_insertId->id,
                           'cnum' => $cnum,
                       ]);

                       $orders->find($oid)->update(['merged' => 1]); // set as merged
                   }

                   return redirect()->back()->with('merge-success', 'Заказы были успешно объединены');
               } elseif(!$this->checkStatus($request->oid) and count($request->oid) > 1) {
                   return redirect()->back()->with('merge-error', 'Статус всех выбранных заказов должен быть: "Готов к  отгрузке, ожидается оплата"');
               } elseif(count($request->oid) < 2) {
                   return redirect()->back()->with('merge-error', 'Количество объединяемых заказов должно быть минимум 2');
               }
           } else {
               return redirect()->back();
           }
       } else {
           return redirect()->back();
       }
    }

    /**
     * Checking status for orders
     *
     * @param $id
     * @return bool
     */
    public function checkStatus($id)
    {
        if(count($id) > 1) { // if orders more then 1
            $orders = new Order();
            $order = $orders->whereIn('id', $id)->where('sid', 2)->get();

            if(count($id) == count($order)) { // if all records have status "2"
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Get merged order's count
     *
     * @param $data
     * @return int
     */
    public function getOrderMergeCount($data)
    {
        $orders = new Order();
        $result = $orders->whereIn('id', $data)->get();
        $count = 0;

        foreach($result as $item) {
            $count += $item->count;
        }

        return $count;
    }

    /**
     * Generate unique check-number for order
     *
     * @return int
     */
    public function unique_cnum()
    {
        $merges = new OrderMerges();
        $orders = new Order();
        $num = mt_rand(100000, 999999);

        $get_list = $merges->where('cnum', '=', $num)->get();
        $get_order_list = $orders->where('cnum', '=', $num)->get();

        if($get_list->isEmpty() and $get_order_list->isEmpty()) {
            return $num;
        } else {
            $this->unique_cnum(); // do recursion, generate new number if cnum already taken
        }
    }
}
