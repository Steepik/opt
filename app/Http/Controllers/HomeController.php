<?php

namespace App\Http\Controllers;

use App\Cart;
use App\HistoryOrders;
use App\Order;
use App\OrderMerges;
use App\SelByCar;
use App\Special;
use App\StatusText;
use App\Tire;
use App\Truck;
use App\Wheel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * @var bool
     */
    public $error = false;

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
     * HomeController constructor.
     * @param Order $order
     * @param OrderMerges $m_order
     */
    public function __construct(
        Order $order,
        OrderMerges $m_order,
        HistoryOrders $h_order
    ){
        $this->order = $order;
        $this->m_order = $m_order;
        $this->h_order = $h_order;
    }

    public function index(
        HistoryOrders $history,
        StatusText $status,
        Request $request
    ){
        $products = array();
        $list = array();
        $get_status = $status->all();

        foreach($this->filteringData($request->toArray()) as $order) {
            if($order->ptype != null) {
                $instance = Cart::getInstanceProductType($order->ptype);
                $products['product'] = $instance->where('tcae', $order->tcae)->first();
                if($products['product'] != null) {
                    $products['product']['oid'] = $order->id;
                    $products['product']['cnum'] = $order->cnum;
                    $products['product']['count'] = $order->count;
                    $products['product']['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('d.m.y H:i');
                    $products['product']['status'] = $order->status->text;
                    $products['product']['sid'] = $order->status->id;
                    $products['product']['commented'] = $order->commented;
                } else {
                    if($order->sid == 4 or $order->sid == 6 or $order->sid == 7) {
                        $products['product'] = $history->where('oid', $order->id)->first();
                        $products['product']['oid'] = $order->id;
                        $products['product']['cnum'] = $order->cnum;
                        $products['product']['count'] = $order->count;
                        $products['product']['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('d.m.y H:i');
                        $products['product']['status'] = $order->status->text;
                        $products['product']['sid'] = $order->status->id;
                        $products['product']['commented'] = $order->commented;
                    } else {
                        //delete order
                        $order->destroy($order->id);
                        $history->where('oid', $order->id)->delete();

                        return redirect(route('home'))->with('info-msg', 'После обновления базы товаров, заказ №' . $order->cnum . ' был удален, так как товара нет в наличии.');
                    }
                }
            } elseif($order->ptype == null) {
                $products['product'] = null;
                $products['product']['oid'] = $order->id;
                $products['product']['cnum'] = $order->cnum;
                $products['product']['count'] = $order->count;
                $products['product']['time'] = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('d.m.y H:i');
                $products['product']['status'] = $order->status->text;
                $products['product']['sid'] = $order->status->id;
                $products['product']['merged'] = 1; // is it merged order? Default = 1;
                $products['product']['price'] = $this->getMergedOrdersPrice($order->cnum);
            }
            $list[] = $products;
        }
        //get list of brands
        $tire_brands = $this->getAllBrands(1);
        $truck_brands = $this->getAllBrands(2);
        $special_brands = $this->getAllBrands(3);
        $wheels_brands = $this->getAllBrands(4);
        //BY CAR
        $vendors = DB::table('sel_by_cars')->select('fvendor')->distinct()->get();

        return view('home', compact(['list', 'get_status', 'tire_brands', 'truck_brands', 'special_brands', 'wheels_brands', 'vendors']));
    }

    /**
     * Doing action depend on which button is clicked
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function actionWithProduct(Request $request)
    {
        if(count($request->input('oid')) > 0) {
            if ($request->action == 'delete') {
                $ids = array();
                foreach($request->oid as $oid) {
                    $order = $this->order->where('id', $oid)->first();
                    if ($order->sid != 4) {
                        $merged = $this->m_order->where('cnum', $order->cnum)->get();
                        foreach ($merged as $id) {
                            $ids[] = $id->oid;
                        }
                        $ids[] = (int)$oid; // add common merged order's id to delete
                    } else {
                        $this->error = 'Запрещены действия с заказами пока водитель в пути';
                    }
                    //delete order history
                    $this->h_order->whereIn('oid', $ids)->delete();
                }
                $this->order->destroy($ids);

                return redirect(route('home'))->with('status-error', $this->error);
            } elseif ($request->action == 'cancel') {
                $order = $this->order->whereIn('id', $request->oid)->get();
                    foreach($order as $item) {
                        if ($item->sid != 1 and $item->sid != 7 and $item->sid != 6 and $item->sid != 4) {
                            $this->order->where('id', $item->id)->update(['sid' => 3]); // 3 = order canceled
                        } elseif($item->sid == 4) {
                            $this->error = 'Запрещены действия с заказами пока водитель в пути';
                        }else {
                            $this->error = 'Заказы у которых статус "Ожидается проверка, Завершён или Отменён модератором" подлежать только удалению';
                        }
                    }

                    return redirect(route('home'))->with('status-error', $this->error);

            } elseif ($request->action == 'ready') {
                $order = $this->order->whereIn('id', $request->oid)->get();
                foreach($order as $item) {
                    $result = $this->m_order->where('cnum', $item->cnum)->get();
                    if(!$result->isEmpty()) {
                        if($item->sid != 7 and $item->sid != 6 and $item->sid != 4) {
                            $this->order->where('id', $item->id)->update(['sid' => 5, 'archived' => 0]); // 5 = order merged
                        }
                    } else {
                        if($item->sid != 1 and $item->sid != 7 and $item->sid != 6 and $item->sid != 4) {
                            $this->order->where('id', $item->id)->update(['sid' => 2, 'archived' => 0]); // 2 = order ready to go waiting for pay
                        } elseif($item->sid == 4) {
                           $this->error = 'Запрещены действия с заказами пока водитель в пути';
                        }else {
                            $this->error = 'Заказы у которых статус "Ожидается проверка, Завершён или Отменён модератором" подлежать только удалению';
                        }
                    }
                }

                return redirect(route('home'))->with('status-error', $this->error);
            } elseif ($request->action == 'merge') {
                if($this->checkStatus($request->oid) and count($request->oid) > 1) {
                    $merge = new OrderMerges();
                    $cnum = $this->unique_cnum();

                    $o_insertId = $this->order->create([
                        'uid' => Auth::user()->id,
                        'cnum' => $cnum,
                        'ptype' => NULL,
                        'count' => $this->getOrderMergeCount($request->oid),
                        'sid' => 5, //set status to merged
                        'merged' => 0,
                        'archived' => 0
                    ]);
                    foreach ($request->oid as $oid) {
                        $cae = Auth::user()->orders()->where('id', $oid)->first(); //get CAE

                        $merge->create([
                            'uid' => Auth::user()->id,
                            'oid' => $oid,
                            'tcae' => $cae->tcae,
                            'mid' => $o_insertId->id,
                            'cnum' => $cnum,
                        ]);
                        $this->order->find($oid)->update(['merged' => 1]); // set as merged
                    }
                } elseif(!$this->checkStatus($request->oid) and count($request->oid) > 1) {
                    return redirect(route('home'))->with('status-error', 'Статус всех выбранных заказов должен быть: "Готов к  отгрузке, ожидается оплата"');
                } elseif(count($request->oid) < 2) {
                    return redirect(route('home'))->with('status-error', 'Количество объединяемых заказов должно быть минимум 2');
                }
            } elseif ($request->action == 'archive') {
                $order = $this->order->whereIn('id', $request->oid)->get();
                foreach($order as $item) {
                    if($item->sid != 4) {
                        $this->order->where('id', $item->id)->update(['archived' => 1]); // archived
                    } else {
                        $this->error = 'Запрещены действия с заказами пока водитель в пути';
                    }
                }

                return redirect(route('home'))->with('status-error', $this->error);
            }
        }

        return redirect(route('home'));
    }

    /**
     * Filtering data if user use search on orders page
     *
     * @param $data - GET parameters
     * @return mixed
     */
    public function filteringData($data)
    {
        $orders = Auth::user();
        $sql = array('merged' => false);
        $m_arr = array();

        if(empty($data)) {
            $result = $orders->orders()->where('merged', false)->where('archived', false)->get();
        } else {
            if(isset($data['sid']) and $data['sid'] != '') {
                $arr = ['sid' => $data['sid']];
                array_push($sql, array($arr));
            }
            if(isset($data['arch']) and $data['arch'] != '') {
                $arr = ['archived' => $data['arch']];
                array_push($sql, array($arr));
            }
            if(isset($data['cnum']) and $data['cnum'] != '') {
                $arr = ['cnum' => $data['cnum']];
                array_push($sql, array($arr));
            }
            if(isset($data['cae']) and $data['cae'] != '') {
                $result_m = $this->m_order->where('tcae', $data['cae'])->get();
                foreach($orders->orders()->where('tcae', $data['cae'])->get() as $oid) {
                    array_push($m_arr, array($oid->id));
                }
                if(!$result_m->isEmpty()) { // check for merged orders with that CAE
                    foreach($result_m as $item) {
                        array_push($m_arr, array($item->mid)); // add ids which should view
                    }
                    //remove duplicate id from array
                    $m_arr = array_map("unserialize", array_unique(array_map("serialize", $m_arr)));
                }
                if(isset($data['start'])) {
                    $date_start = $data['start'];
                    $date_end = $data['end'] . ' 23:59:59';
                    $result = $orders->orders()->where($sql)->whereIn('id', $m_arr)->whereBetween('created_at',  [$date_start, $date_end])->get();
                } else {
                    $result = $orders->orders()->where($sql)->whereIn('id', $m_arr)->get();
                }
            } else {
                if(!isset($data['start'])) {
                    $result = $orders->orders()->where($sql)->get();
                } else {
                    $date_start = $data['start'];
                    $date_end = $data['end'] . ' 23:59:59';
                    $result = $orders->orders()->whereBetween('created_at',  [$date_start, $date_end])->where($sql)->get();
                }
            }
        }

        return $result;
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
     * Get merged order's total amount
     *
     * @param $cnum - search by order's cnum
     * @return int
     */
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



    /**
     * Get list of all brands for each type of products
     *
     * @param $type | 1 - tire, 2 - truck, 3 - special, 4 - wheels
     * @return mixed
     */
    public function getAllBrands($type)
    {
        $brand = collect();
        $ptype = Cart::getInstanceProductType($type);

        if(Redis::exists('brands_' . $type)) {
            $brand = Redis::get('brands_' . $type);
        } else {
            $ptype->orderBy('name', 'ASC')->each(function($item, $key) use ($brand) {
                $brand->push(['name' => $item->brand->name, 'id' => $item->brand->id]);
            });

            Redis::set('brands_' . $type, $brand->sortBy('name')->unique(), 'EX', 3600);
            $brand = Redis::get('brands_' . $type);
        }

        return json_decode($brand);
    }
}