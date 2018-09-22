<?php

namespace App\Http\Controllers;

use App\Reserve;
use Illuminate\Http\Request;
use App\Tire;
use App\Truck;
use App\Special;
use Illuminate\Support\Facades\Redis;
use Excel;
use File;
class TireController extends Controller
{

    public $image = array();

    public function index()
    {
        $brands_list  = $this->getAllBrands();
        return view('tires.index', compact('brands_list'));
    }

    /**
     * Product selection
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function podbor(Request $request)
    {
        if (!$request->all()) {
            return redirect('/');
        }
        $appends = $request->except('_token');
        $filter_type = $request->type;
        $type = $request->type;

        $brands_list  = $this->getAllBrands();

        $data = $this->filter(
                $request->type, $request->twidth, $request->tprofile, $request->tdiameter,
                $request->tseason, $request->brand_id, $request->tcae, $request->taxis, $request->limit,
                $request->sortOptPrice, $request->sortRozPrice
            );

        return view('tires.podbor', compact('data', 'appends', 'brands_list', 'filter_type', 'type'));
    }

    /**
     * Filtering data by value
     *
     * @param $type | 1 - Tire, 2 - Truck tires, 3 - Special tires
     * @param $twidth | tire's width
     * @param $tprofile | tire's profile
     * @param $tdiameter | tire's diameter
     * @param string $tseason | tires' season | winter/summer
     * @param $tbrand | tire's brand
     * @param string $tcae | tire's special cae number
     * @param string $taxis | tire's axis only for truck
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function filter($type, $twidth, $tprofile, $tdiameter, $tseason = '', $tbrand, $tcae = '', $taxis = '',
                           $limit = 10, $sortOptPrice = 'desc', $sortRozPrice = 'desc') {
        $session = Session();
        $filter = array();
        if ($type == 1) { // tires
            $tire = Tire::query();
            $session->forget(['twidth', 'tprofile', 'tdiameter', 'tseason', 'tbrand', 'tcae']);
            if (!empty($twidth)) {
                $filter['twidth'] = $twidth;
                $session->flash('twidth', $twidth);
            }
            if (!empty($tprofile)) {
                $filter['tprofile'] = $tprofile;
                $session->flash('tprofile', $tprofile);
            }
            if (!empty($tdiameter)) {
                //check if diameter has russian letter 'C | c' change it to english letter
                if(str_contains($tdiameter, 'С') or str_contains($tdiameter, 'с'))
                {
                    $tdiameter = str_replace(['С', 'с'], 'C', $tdiameter);
                }

                $filter['tdiameter'] = $tdiameter;
                $session->flash('tdiameter', $tdiameter);
            }
            if (!empty($tseason) and $tseason != 'spike' and $tseason != 'nospike') {
                $filter['tseason'] = $tseason;
                $session->flash('tseason', $tseason);
            } elseif (!empty($tseason) and $tseason == 'spike') {
                $filter['tseason'] = 'Зимняя';
                $filter['spike'] = 1;
                $session->flash('tseason', $tseason);
            } elseif (!empty($tseason) and $tseason == 'nospike') {
                $filter['tseason'] = 'Зимняя';
                $filter['spike'] = 0;
                $session->flash('tseason', $tseason);
            }
            if (!empty($tbrand)) {
                $filter['brand_id'] = $tbrand;
                $session->flash('tbrand', $tbrand);
            }
            if (!empty($tcae)) {
                $filter['tcae'] = $tcae;
                $session->flash('tcae', $tcae);
            }

            if (isset($sortOptPrice)) {
                $orderBy = [
                    'field' => 'price_opt',
                    'sort'  => $sortOptPrice,
                ];
            } elseif (isset($sortRozPrice)) {
                $orderBy = [
                    'field' => 'price_roz',
                    'sort'  => $sortRozPrice,
                ];
            } else {
                $orderBy = [
                    'field' => 'id',
                    'sort'  => 'desc',
                ];
            }

            if($limit === 'all') {
                $data = $tire->where($filter)
                    ->where('quantity', '>', 0)
                    ->orderBy($orderBy['field'], $orderBy['sort'])
                    ->paginate(999999);
            } else {
                $data = $tire->where($filter)
                    ->where('quantity', '>', 0)
                    ->orderBy($orderBy['field'], $orderBy['sort'])
                    ->paginate(10);
            }
                $data->each(function($item, $key) use ($data) {
                $reserve = Reserve::where('tcae', $item->tcae)->where('ptype', 1)->first();
                if(! is_null($reserve)) { // if product is in reserve then remove element from collect
                    $data->forget($key);
                }
            });
        } elseif ($type == 2) { // trucks tires
            $truck = Truck::query();
            $session->forget(['trwidth', 'trprofile', 'trdiameter', 'trseason', 'trbrand', 'traxis', 'trcae']);
            if (!empty($twidth)) {
                $filter['twidth'] = $twidth;
                $session->flash('trwidth', $twidth);
            }
            if (!empty($tprofile)) {
                $filter['tprofile'] = $tprofile;
                $session->flash('trprofile', $tprofile);
            }
            if (!empty($tdiameter)) {
                //check if diameter has symbol like ',' change it to '.'
                if(str_contains($tdiameter, ','))
                {
                    $tdiameter = str_replace(',', '.', $tdiameter);
                }
                //check if diameter has russian letter 'C | c' change it to english letter
                if(str_contains($tdiameter, 'С') or str_contains($tdiameter, 'с'))
                {
                    $tdiameter = str_replace(['С', 'с'], 'C', $tdiameter);
                }
                $filter['tdiameter'] = $tdiameter;
                $session->flash('trdiameter', $tdiameter);
            }
            if (!empty($tseason) and $tseason != 'spike' and $tseason != 'nospike') {
                $filter['tseason'] = $tseason;
                $session->flash('trseason', $tseason);
            } elseif (!empty($tseason) and $tseason == 'spike') {
                $filter['tseason'] = 'Зимняя';
                $filter['spike'] = 1;
                $session->flash('trseason', $tseason);
            } elseif (!empty($tseason) and $tseason == 'nospike') {
                $filter['tseason'] = 'Зимняя';
                $filter['spike'] = 0;
                $session->flash('trseason', $tseason);
            }
            if (!empty($tbrand)) {
                $filter['brand_id'] = $tbrand;
                $session->flash('trbrand', $tbrand);
            }
            if (!empty($tcae)) {
                $filter['tcae'] = $tcae;
                $session->flash('trcae', $tcae);
            }
            if (!empty($taxis)) {
                $filter['axis'] = $taxis;
                $session->flash('traxis', $taxis);
            }

            if (isset($sortOptPrice)) {
                $orderBy = [
                    'field' => 'price_opt',
                    'sort'  => $sortOptPrice,
                ];
            } elseif (isset($sortRozPrice)) {
                $orderBy = [
                    'field' => 'price_roz',
                    'sort'  => $sortRozPrice,
                ];
            } else {
                $orderBy = [
                    'field' => 'id',
                    'sort'  => 'desc',
                ];
            }

            if($limit === 'all') {
                $data = $truck->where($filter)
                    ->where('quantity', '>', 0)
                    ->orderBy($orderBy['field'], $orderBy['sort'])
                    ->paginate(999999);
            } else {
                $data = $truck->where($filter)
                    ->where('quantity', '>', 0)
                    ->orderBy($orderBy['field'], $orderBy['sort'])
                    ->paginate(10);
            }
        } elseif ($type == 3) { // special tires
            $special = Special::query();
            $session->forget(['swidth', 'sprofile', 'sdiameter', 'sseason', 'sbrand', 'scae']);
            if (!empty($twidth)) {
                //check if diameter has symbol like ',' change it to '.'
                if(str_contains($twidth, ','))
                {
                    $twidth = str_replace(',', '.', $twidth);
                }
                $filter['twidth'] = $twidth;
                $session->flash('swidth', $twidth);
            }
            if (!empty($tprofile)) {
                $filter['tprofile'] = $tprofile;
                $session->flash('sprofile', $tprofile);
            }
            if (!empty($tdiameter)) {
                $filter['tdiameter'] = $tdiameter;
                $session->flash('sdiameter', $tdiameter);
            }
            if (!empty($tseason) and $tseason != 'spike' and $tseason != 'nospike') {
                $filter['tseason'] = $tseason;
                $session->flash('sseason', $tseason);
            } elseif (!empty($tseason) and $tseason == 'spike') {
                $filter['tseason'] = 'Зимняя';
                $filter['spike'] = 1;
                $session->flash('sseason', $tseason);
            } elseif (!empty($tseason) and $tseason == 'nospike') {
                $filter['tseason'] = 'Зимняя';
                $filter['spike'] = 0;
                $session->flash('sseason', $tseason);
            }
            if (!empty($tbrand)) {
                $filter['brand_id'] = $tbrand;
                $session->flash('sbrand', $tbrand);
            }
            if (!empty($tcae)) {
                $filter['tcae'] = $tcae;
                $session->flash('scae', $tcae);
            }

            if (isset($sortOptPrice)) {
                $orderBy = [
                    'field' => 'price_opt',
                    'sort'  => $sortOptPrice,
                ];
            } elseif (isset($sortRozPrice)) {
                $orderBy = [
                    'field' => 'price_roz',
                    'sort'  => $sortRozPrice,
                ];
            } else {
                $orderBy = [
                    'field' => 'id',
                    'sort'  => 'desc',
                ];
            }

            if($limit === 'all') {
                $data = $special->where($filter)
                    ->where('quantity', '>', 0)
                    ->orderBy($orderBy['field'], $orderBy['sort'])
                    ->paginate(999999);
            } else {
                $data = $special->where($filter)
                    ->where('quantity', '>', 0)
                    ->orderBy($orderBy['field'], $orderBy['sort'])
                    ->paginate(10);
            }
        } else {
            abort(204, ''); // no content
        }

        return $data;
    }

    public function getAllBrands()
    {
        $brand = collect();
        if(Redis::exists('brands')) {
            $brand = Redis::get('brands');
        } else {
            Tire::orderBy('name', 'ASC')->each(function($item, $key) use ($brand) {
                $brand->push(['name' => $item->brand->name, 'id' => $item->brand->id]);
            });
            Redis::set('brands', $brand->sortBy('name')->unique(), 'EX', 3600);
            $brand = Redis::get('brands');
        }

        return json_decode($brand);
    }
}
