<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Events\PusherNotify;
use App\HistoryOrders;
use App\Order;
use App\Special;
use App\Tire;
use App\Truck;
use App\Wheel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class CartController extends Controller
{
    public $total_total;
    public $total_count;
    public $products;
    public $id;
    public $count;

    public function index()
    {
        $products = $this->getProductsForCart();
        return view('cart.index', compact('products'));
    }

    public function addToCart(Request $request)
    {
        if($request->ajax()) {
            $session = Session();
            $tire_type = $request->type; // 1 - tires | 2 - trucks | 3 - special | 4 - wheels
            $product = Cart::getInstanceProductType($request->type);
            $p_info = $product->where('tcae', $request->product_id)->first();
            $p_price = $p_info['price_opt'];
            $p_quantity = $p_info['quantity'];
            $this->total_price = $p_price * $request->count;
            $this->total_count = $request->count;
            $this->products = $request->product_id;
            $count = $request->count;
            if($request->count <= $p_quantity and $request->count > 0) {

                //calculate total price, products count of all products in cart
                if ($session->has('total_price')) {
                    $this->total_price += $session->get('total_price');
                }
                if ($session->has('total_count')) {
                    $this->total_count += $session->get('total_count');
                }

                $session->put('total_price', $this->total_price);
                $session->put('total_count', $this->total_count);
                $session->push('products', [
                    'id' => md5(rand(0, 9999999)), 'pid' => $this->products, 'type' => $tire_type,
                    'cae' => $p_info->tcae, 'count' => $count,
                ]);
                $session->put('cart_products', count($session->get('products')));
            }
            //$session->forget(['total_price', 'total_count', 'products', 'cart_products']);
            return response()->json(['quantity' => $p_quantity, 'price' => $p_price, $session->get('products'), 'cart_products' => count($session->get('products'))]);
        }
    }

    public function makeOrder()
    {
        $order = new Order();
        $history = new HistoryOrders();
        $session = Session();
        $products = !empty($session->get('products')) ? $session->get('products') : array();

        foreach($products as $product) {

            $oid = $order->updateOrCreate([
                'uid' => Auth::user()->id,
                'cnum' => $this->unique_cnum(),
                'tcae' => $product['cae'],
                'ptype' => $product['type'],
                'count' => $product['count'],
                'sid' => $this->setStatus($product['pid'], $product['type'], $product['count']),
                'merged' => false,
                'archived' => false
            ]);

            //add order's history
            $p_info = Cart::getInstanceProductType($product['type'])->where('tcae', $product['cae'])->first();
            $history->create([
                'uid' => Auth::user()->id,
                'oid' => $oid->id,
                'brand_id' => $p_info->brand_id,
                'name' => $p_info->name,
                'tseason' => $p_info->tseason,
                'spike' => (! is_null($p_info->spike)) ? $p_info->spike : 0,
                'price_opt' => $p_info->price_opt,
                'price_roz' => $p_info->price_roz,
                'image' => $p_info->image
            ]);
        }
        Cart::clearCart();

        //Notify admin about new order
        PusherNotify::dispatch(Auth::user()->legal_name . ', совершил новый заказ!');

        return redirect('/');
    }

    /**
     * Get product cae - actually we make it for search by index 'cae'
     *
     * @param $pid
     * @param $ptype
     * @return mixed
     */
    public function getProductCae($pid, $ptype)
    {

       $data = Cart::getInstanceProductType($ptype);

        $cae = $data->where('id', $pid)->first();

        return $cae->tcae;
    }

    /**
     * Set status for order
     *
     * @return int
     */
    public function setStatus($pid, $ptype, $count)
    {
        $sid = 0;

            $data = Cart::getInstanceProductType($ptype);

            $pdata = $data->where('tcae', $pid)->first();
            $diff = ($pdata->quantity - $count); // difference between quantity and count

            if($pdata->quantity == $count or $diff < 3) {
                $sid = 1; // status: wait for moderation
            } elseif($diff > 2) {
                $sid = 2; //status: ready to go wait for pay
            }

        return $sid;
    }

    public function getProductsForCart()
    {

        $products = Session::get('products');

        //dd($products);
        $products_list = collect();
        $total_price = 0;
        $total_count = 0;

        if($products) {
            foreach ($products as $key =>$product) {
                $data = Cart::getInstanceProductType($product['type']);
                $pdata = $data->where('tcae', $product['pid'])->first();
                $products_list->prepend(['id' => $product['id'], $pdata , 'count' => $product['count'], 'ptype' => $product['type']]);
                $total_price += $pdata->price_opt * $product['count'];
            }

        }

        //refresh session data
        Session::forget('total_price', 'cart_products');
        Session::put('total_price', $total_price);
        Session::put('cart_products', count($products));

        return $products_list;
    }

    public function refreshProductCount(Request $request)
    {
        if($request->ptype > 4 or $request->ptype < 1) return redirect()->back();

        $product = Cart::getInstanceProductType($request->ptype);
        $p_info = $product->where('tcae', $request->product_id)->first();
        $p_quantity = $p_info['quantity'];

        if($request->count > 0 and $request->count <= $p_quantity) {
            $this->products = collect(Session::get('products'));

            $this->id = $request->id;
            $this->count = $request->count;
            $action = $request->action;

            if ($action == 'ref') { // refresh price
                $data = $this->products->map(function ($item) {
                    if ($item['id'] == $this->id) {
                        $item['count'] = $this->count;
                    }
                    return $item;
                });

            } elseif ($action == 'del') { // delete item
                $data = $this->products->map(function ($item) {
                    if ($item['id'] == $this->id) {
                        $item = [];
                    }
                    return $item;
                });
            } else {
                return redirect(route('cart'));
            }

            //dd($data);

            Session::forget(['products']);
            Session::put('products', array_filter($data->toArray()));


            //refresh cart
            $this->getProductsForCart();

            return redirect(route('cart'));
        }
        else {
            return redirect()->back()->with('error-refresh', '');
        }
    }

    /**
     * Generate unique check-number for order
     *
     * @return int
     */
    public function unique_cnum()
    {
        $orders = new Order();
        $num = mt_rand(100000, 999999);

        $get_order = $orders->where('cnum', '=', $num)->get();

        if($get_order->isEmpty()) {
            return $num;
        }
        else {
            $this->unique_cnum(); // do recursion, generate new number if cnum already taken
        }


    }

}
