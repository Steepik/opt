<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Wheel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
class WheelController extends Controller
{
    public $brands = [];

    public function index()
    {
        //get list of brands only for wheel
        $brands_list = $this->getAllBrands();
        return view('wheels.index', compact('brands_list'));
    }

    public function podbor(Request $request)
    {
        if (!$request->all()) {
            return redirect('/');
        }
        $appends = $request->except('_token');
        $session = Session();
        $filter = array();
        $type = $request->type;
        $limit = $request->limit;
        $wheel = Wheel::query();
        $session->forget(['width', 'diameter', 'hole_count', 'pcd', 'et', 'dia', 'cae', 'brand']);
        //get list of brands only for wheel
        $brands_list = $this->getAllBrands();
        if (!empty($request->twidth)) {
            //check if diameter has symbol like ',' change it to '.'
            if(str_contains($request->twidth, ','))
            {
                $request->twidth = str_replace(',', '.', $request->twidth);
            }
            $filter['twidth'] = $request->twidth;
            $session->flash('width', $request->twidth);
        }
        if (!empty($request->tdiameter)) {
            $filter['tdiameter'] = $request->tdiameter;
            $session->flash('diameter', $request->tdiameter);
        }
        if (!empty($request->hole_count)) {
            $filter['hole_count'] = $request->hole_count;
            $session->flash('hole_count', $request->hole_count);
        }
        if (!empty($request->pcd)) {
            //check if diameter has symbol like ',' change it to '.'
            if(str_contains($request->pcd, '.'))
            {
                $request->pcd = str_replace('.', ',', $request->pcd);
            }
            $filter['pcd'] = $request->pcd;
            $session->flash('pcd', $request->pcd);
        }
        if (!empty($request->et)) {
            //check if diameter has symbol like ',' change it to '.'
            if(str_contains($request->et, ','))
            {
                $request->et = str_replace(',', '.', $request->et);
            }
            $filter['et'] = $request->et;
            $session->flash('et', $request->et);
        }
        if (!empty($request->dia)) {
            //check if diameter has symbol like ',' change it to '.'
            if(str_contains($request->dia, ','))
            {
                $request->dia = str_replace(',', '.', $request->dia);
            }
            $filter['dia'] = $request->dia;
            $session->flash('dia', $request->dia);
        }
        if (!empty($request->tcae)) {
            $filter['tcae'] = $request->tcae;
            $session->flash('cae', $request->tcae);
        }
        if (!empty($request->brand_id)) {
            $filter['brand_id'] = $request->brand_id;
            $session->flash('brand', $request->brand_id);
        }
        if (!empty($request->d_type)) {
            $filter['type'] = $request->d_type;
            $session->flash('d_type', $request->d_type);
        } else {
            $session->forget('d_type');
        }

        if (isset($request->sortOptPrice)) {
            $orderBy = [
                'field' => 'price_opt',
                'sort'  => $request->sortOptPrice,
            ];
        } elseif (isset($request->sortRozPrice)) {
            $orderBy = [
                'field' => 'price_roz',
                'sort'  => $request->sortRozPrice,
            ];
        } else {
            $orderBy = [
                'field' => 'id',
                'sort'  => 'desc',
            ];
        }

        if($limit === 'all') {
            $data = $wheel->where($filter)
                ->where('quantity', '>', 0)
                ->orderBy($orderBy['field'], $orderBy['sort'])
                ->paginate(999999);
        } else {
            $data = $wheel->where($filter)
                ->where('quantity', '>', 0)
                ->orderBy($orderBy['field'], $orderBy['sort'])
                ->paginate(10);
        }

        return view('wheels.podbor', compact('data', 'brands_list', 'appends','type'));
    }

    public function getAllBrands()
    {
        $brand = collect();
        if(Redis::exists('brands_wheel')) {
            $brand = Redis::get('brands_wheel');
        } else {
            Wheel::orderBy('name', 'ASC')->each(function($item, $key) use ($brand) {
                $brand->push(['name' => $item->brand->name, 'id' => $item->brand->id]);
            });
            Redis::set('brands_wheel', $brand->sortBy('name')->unique(), 'EX', 3600);
            $brand = Redis::get('brands_wheel');
        }

        return json_decode($brand);
    }
}
