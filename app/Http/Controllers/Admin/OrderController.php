<?php

namespace App\Http\Controllers\Admin;

use App\Cart;
use App\Comment;
use App\HistoryOrders;
use App\Order;
use App\OrderMerges;
use App\StatusText;
use App\Traits\CalcPercent;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use NumberToWords\NumberToWords;

class OrderController extends Controller
{
    use CalcPercent;

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

    /**
     * @var User
     */
    public $user;

    /**
     * OrderController constructor.
     * @param Order $order
     * @param OrderMerges $m_order
     * @param HistoryOrders $h_order
     * @param User $user
     */
    public function __construct(Order $order, OrderMerges $m_order, HistoryOrders $h_order, User $user)
    {
        $this->order = $order;
        $this->m_order = $m_order;
        $this->h_order = $h_order;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sql = array('merged' => 0);
        $uid = array();
        $appends = $request->all();

        if(!empty($request->num)) {
            $arr = ['cnum' => $request->num];
            array_push($sql, array($arr));
        }
        if(!empty($request->legal_name)) {
            $users = $this->user->where('legal_name', 'like', '%' . $request->legal_name . '%')->pluck('id')->all();
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
            $orders = $this->order->whereYear('updated_at', '=', date('Y'))
                ->orderBy('updated_at', 'DESC')
                ->where($sql)
                ->whereIn('uid', $uid)
                ->whereBetween('created_at',  [$date_start, $date_end])
                ->paginate(10);
        } else {
            $orders = $this->order->whereYear('updated_at', '=', date('Y'))
                ->orderBy('updated_at', 'DESC')
                ->where($sql)
                ->whereBetween('created_at',  [$date_start, $date_end])
                ->paginate(10);
        }



        $status_list = StatusText::all();

        return view('admin.list.orders', compact('orders', 'status_list', 'appends'));
    }

    /**
     * Show merged orders
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showMergeOrder($id)
    {
        $order = $this->order->find($id);
        $merged = $this->m_order->where('cnum', $order->cnum)->get();
        $plist = collect();
        $total_sum = 0;
        foreach($merged as $item) {
           foreach($item->orders as $info_order) {
               $product = Cart::getInstanceProductType($info_order->ptype)->where('tcae', $info_order->tcae)->first();

               if ($info_order->percent_value != 0) {
                   $product->price_opt = $this->calcPercentForOptPrice($product->price_opt, $order->uid, $product->brand_id, $info_order->percent_value);
               }

               if(! is_null($product)) {
                   $product['count'] = $info_order->count;
                   $plist->push($product);
                   $total_sum += $product->price_opt * $info_order->count;
               } else {
                   $history = $this->h_order->where('oid', $info_order->id)->first();
                   $history['count'] = $info_order->count;
                   $plist->push($history);
                   $total_sum += $history->price_opt * $info_order->count;
               }

           }
        }

        return view('admin.order_merge_info', compact('order', 'plist', 'total_sum'));
    }

    /**
     * Show single order
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showOrder($id)
    {
        $order = $this->order->find($id);
        $pinstance = Cart::getInstanceProductType($order->ptype);
        $product = $pinstance->where('tcae', $order->tcae)->first();

        if ($order->percent_value != 0) {
            $product->price_opt = $this->calcPercentForOptPrice($product->price_opt, $order->uid, $product->brand_id, $order->percent_value);
        }

        if(is_null($product)) {
            $product = $this->h_order->where('oid', $order->id)->first();
        }
        return view('admin.order_info', compact('order', 'product'));
    }

    /**
     * Change order status with ajax
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeOrderStatus(Request $request)
    {
        if($request->ajax()) {
            $order = $this->order->find($request->oid);
            if($order->ptype != null) {
                $order->sid = $request->sid;
                $order->save();
            } else {
                // set all nested orders status
                $order->sid = $request->sid; //main order set
                $order->save();
                $m_orders = $this->m_order->where('mid', $request->oid)->get();
                foreach ($m_orders as $item) {
                    $order = $this->order->find($item->oid);
                    $order->sid = $request->sid;
                    $order->save();
                }
            }

            return response()->json(['success' => true, 'sid' => $request->sid, 'text' => strip_tags($order->status->text)]);
        }
    }

    /**
     * Add comment to order
     *
     * @param Comment $comment
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addComment(Comment $comment, Request $request, $id)
    {
        $comment->text = $request->text;
        $comment->uid = Auth::user()->id;
        $comment->oid = $id;

        if($comment->save()) {
            return redirect()->back()->with('success', '');
        }
    }

    /**
     * Show single order page with invoice for print
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function invoice($id)
    {
        $ntw = new NumberToWords();
        $order = Order::find($id);
        $product = Cart::getInstanceProductType($order->ptype)->where('tcae', $order->tcae)->first();

        if ($order->percent_value != 0) {
            $product->price_opt = $this->calcPercentForOptPrice($product->price_opt, $order->uid, $product->brand_id, $order->percent_value);
        }

        if(is_null($product)) {
            $product = $this->h_order->where('oid', $order->id)->first();
        }
        return view('admin.invoice', compact('order', 'product', 'ntw'));
    }

    /**
     * Show merged order page with invoice for print
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function invoice_merged($id)
    {
        $order = $this->order->find($id);
        $merged = $this->m_order->where('cnum', $order->cnum)->get();
        $ntw = new NumberToWords();
        $plist = collect();
        $total_sum = 0;
        $total_sum_ntw = 0;
        foreach($merged as $item) {
            foreach($item->orders as $info_order) {
                $product = Cart::getInstanceProductType($info_order->ptype)->where('tcae', $info_order->tcae)->first();

                if ($info_order->percent_value != 0) {
                    $product->price_opt = $this->calcPercentForOptPrice($product->price_opt, $order->uid, $product->brand_id, $info_order->percent_value);
                }

                if(! is_null($product)) {
                    $product['count'] = $info_order->count;
                    $plist->push($product);
                    $total_sum += $product->price_opt * $info_order->count;
                } else {
                    $history = $this->h_order->where('oid', $info_order->id)->first();
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

    /**
     * Do action with orders
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function orderAction(Request $request)
    {
       if($request->oid) {
           if ($request->action == 'del') {
               $merges = new OrderMerges();
               $ids = array();

               foreach($request->oid as $oid) {
                   $order = $this->order->where('id', $oid)->first();
                   if ($order->sid != 4) {
                       $merged = $merges->where('cnum', $order->cnum)->get();

                       foreach ($merged as $id) {
                           $ids[] = $id->oid;
                       }
                       $ids[] = (int)$oid; // add common merged order's id to delete
                   }
                   //delete order history
                   $this->h_order->whereIn('oid', $ids)->delete();
               }

               $this->order->destroy($ids);
               return redirect()->back();

           } elseif ($request->action == 'merge') {
               if(count($request->oid) > 1) {
                   $cnum = $this->unique_cnum();
                   $orders_info = $this->order->whereIn('id', $request->oid)->get();

                   //check if all selected orders belongs to same user
                   foreach($orders_info as $item) {
                        if($orders_info[0]->uid == $item->uid) {
                            continue;
                        } else {
                            return redirect()->back()->with('merge-error', 'Вы пытаетесь объединить заказы разных пользователей');
                        }
                   }

                   //get single merged order
                   $null_order = $orders_info->filter(function($item){
                       return $item->ptype == null;
                   });

                   //if merge more then 1 order
                   if(count($null_order) > 1) {
                       return redirect()->back()->with('merge-error', 'Возможно объединить только с одним, уже ранее объединенным заказом');
                   }

                   if($null_order->isEmpty()) { // if we merge single orders with status 2
                       $o_insertId = $this->order->create([
                           'uid' => $orders_info[0]->uid,
                           'cnum' => $cnum,
                           'ptype' => NULL,
                           'count' => $this->getOrderMergeCount($request->oid),
                           'sid' => 5, //set status to merged
                           'merged' => 0,
                           'archived' => 0,
                       ]);

                       foreach ($request->oid as $oid) {
                           $cae = $this->order->where('id', $oid)->first(); //get CAE
                           $this->m_order->create([
                               'uid' => Auth::user()->id,
                               'oid' => $oid,
                               'tcae' => $cae->tcae,
                               'mid' => $o_insertId->id,
                               'cnum' => $cnum,
                           ]);

                           $this->order->find($oid)->update(['merged' => 1]); // set as merged
                       }
                   } else {

                       // get only single orders for merge
                       $orders = $orders_info->filter(function($item){
                           return $item->ptype != null;
                       });

                       //index starts 0
                       $null_order = $null_order->values()->all();
                       foreach ($orders as $oid) {
                           $order = $this->order->where('id', $oid->id)->first();
                           $this->m_order->create([
                               'uid' => Auth::user()->id,
                               'oid' => $oid->id,
                               'tcae' => $order->tcae,
                               'mid' => $null_order[0]->id,
                               'cnum' => $null_order[0]->cnum,
                           ]);

                           $m = $this->order->find($null_order[0]->id);
                           $m->update(['count' => $m->count + $order->count]); // update merged order count
                           $this->order->find($oid->id)->update(['merged' => 1]); // set as merged
                       }
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
            $order = $this->order->whereIn('id', $id)->where('sid', 2)->get();

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
        $result = $this->order->whereIn('id', $data)->get();
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
        $num = mt_rand(100000, 999999);

        $get_list = $this->m_order->where('cnum', '=', $num)->get();
        $get_order_list = $this->order->where('cnum', '=', $num)->get();

        if($get_list->isEmpty() and $get_order_list->isEmpty()) {
            return $num;
        } else {
            $this->unique_cnum(); // do recursion, generate new number if cnum already taken
        }
    }

    /**
     * Change order count
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AjaxChangeOrderCount(Request $request)
    {
        if($request->ajax()) {
            $pTypeIsNull = 0;

            $this->order->where('cnum', $request->cnum)->update(['count' => $request->value]);
            //get new data
            $order = $this->order->where('cnum', $request->cnum)->first();

            if(! is_null($order->ptype)) {
                $instance = Cart::getInstanceProductType($order->ptype);

                $product = $instance->where('tcae', $order->tcae)->first();
            } else {
                $orderInfo = $this->m_order->where('cnum', $request->cnum)->where('tcae', $request->tcae)->first();
                $specOrder = $this->order->find($orderInfo->oid);
                $specOrder->count = $request->value;
                $specOrder->save();

                //get total count of merged order
                $getMergedOrders = $this->m_order->where('cnum', $request->cnum)->get();
                $totalCount = 0;
                foreach ($getMergedOrders as $mergeOrder) {
                    $singleOrder = $this->order->find($mergeOrder->oid);
                    $totalCount += $singleOrder->count;
                }

                $this->order->where('cnum', $request->cnum)->update(['count' => $totalCount]);

                $specInstance = Cart::getInstanceProductType($specOrder->ptype);
                $product = $specInstance->where('tcae', $specOrder->tcae)->first();

                $pTypeIsNull = 1;
            }

            return response()->json([
                'price_opt' => $product->price_opt,
                'type' => $pTypeIsNull,
            ]);
        }
    }

    /**
     * Delete single position from merged order
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxDeletePositionFromMergedOrder(Request $request)
    {
        if($request->ajax()) {
            $orderCnum = $this->m_order->where('cnum', $request->cnum)
                ->where('tcae', $request->tcae)
                ->first(['oid']);

            if(!is_null($orderCnum)) {
                $totalProductMergedCount = $this->order->where('cnum', $request->cnum)->first(['count']);
                $singleOrder = $this->order->find($orderCnum->oid);

                $calcCount = $totalProductMergedCount->count - $singleOrder->count;
                $this->order->where('cnum', $request->cnum)->update(['count' => $calcCount]);

                $deleted = $this->order->destroy($orderCnum->oid);

                return response()->json(['success' => $deleted]);
            }
        }
    }
}
