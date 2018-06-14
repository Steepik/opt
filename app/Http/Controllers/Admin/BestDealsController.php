<?php

namespace App\Http\Controllers\Admin;

use App\BestDeals;
use App\Tire;
use App\Wheel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BestDealsController extends Controller
{
    /**
     * @var Tire
     */
    public $tire;

    /**
     * @var Wheel
     */
    public $wheel;

    /**
     * @var BestDeals
     */
    public $bestDeals;

    /**
     * BestDealsController constructor.
     * @param Tire $tire
     * @param Wheel $wheel
     * @param BestDeals $bestDeals
     */
    public function __construct(Tire $tire, Wheel $wheel, BestDeals $bestDeals)
    {
        $this->tire = $tire;
        $this->wheel = $wheel;
        $this->bestDeals = $bestDeals;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $products = array();
        $t_count = 0;
        $appends = $request->all();

        $in_reserve = $this->bestDeals->pluck('tcae')->all();

        if(!empty($request->q)) {
            $products = $this->tire->whereNotIn('tcae', $in_reserve)
                ->where('name', 'like', '%' . $request->q . '%')
                ->orderBy('name')
                ->paginate(25);
            $products->type = 1;

            $t_count = $this->tire->whereNotIn('tcae', $in_reserve)
                ->where('name', 'like', '%' . $request->q . '%')
                ->count();
        }

        if(!empty($request->qw)) {
            //if text contains symbol "," then replace it "."
            if(str_contains($request->qw, ',')) {
                $request['qw'] = str_replace(',', '.', $request->qw);
            }

            $products = $this->wheel->whereNotIn('tcae', $in_reserve)
                ->where('name', 'like', '%' . $request->qw . '%')
                ->orderBy('name')
                ->paginate(25);
            $products->type = 4;

            $t_count = $this->wheel->whereNotIn('tcae', $in_reserve)
                ->where('name', 'like', '%' . $request->qw . '%')
                ->count();
        }

        if (!empty($request->r)) {
            $inDeals = $this->tire->whereIn('tcae', $in_reserve)->where('name', 'like', '%' . $request->r . '%')->get();
        } elseif (!empty($request->rw)) {
            $inDeals = $this->wheel->whereIn('tcae', $in_reserve)->where('name', 'like', '%' . $request->rw . '%')->get();
        } else {
            $p_reserve[] = $this->tire->whereIn('tcae', $in_reserve)->get();
            $p_reserve[] = $this->wheel->whereIn('tcae', $in_reserve)->get();

            foreach($p_reserve as $models) {
                foreach($models as $item) {
                    $inDeals[] = $item;
                }
            }
        }

        return view('admin.bestdeals.index', compact('products', 't_count', 'inDeals', 'appends'));

    }

    public function addToBestDeals(Request $request)
    {
        if($request->ptype == 1) {
            $this->bestDeals->create($request->except(['_token', '_method']));
            return redirect()->back()->with('success', 'Выбраный товар был добавлен');
        } elseif($request->ptype == 4) {
            $this->bestDeals->create($request->except(['_token', '_method']));
            return redirect()->back()->with('success', 'Выбраный товар был добавлен');
        } else {
            return redirect()->back();
        }
    }

    public function deleteFromBestDeals(Request $request)
    {
        if ($request->ptype == 1) {
            $this->bestDeals->where('tcae', $request->tcae)->delete();
            return redirect()->back()->with('success', 'Выбраный товар был удален');
        } else {
            return redirect()->back();
        }
    }
}
