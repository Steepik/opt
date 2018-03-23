<?php

namespace App\Http\Controllers\Admin;

use App\Reserve;
use App\Tire;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReserveController extends Controller
{
    /**
     * @var Tire
     */
    public $tire;

    /**
     * @var Reserve
     */
    public $reserve;

    /**
     * ReserveController constructor.
     * @param Tire $tire
     * @param Reserve $reserve
     */
    public function __construct(Tire $tire, Reserve $reserve)
    {
        $this->tire = $tire;
        $this->reserve = $reserve;
    }

    public function index(Request $request)
    {
        $tires = array();
        $t_count = 0;
        if(!empty($request->q)) {
            $tires = $this->tire->where('name', 'like', '%' . $request->q . '%')->paginate(25);
            $tires->each(function($item, $key) use ($tires) {
               $reserved = $this->reserve->where('tcae', $item->tcae)->first();
               if(! is_null($reserved)) {
                   $item['reserved'] = true;
                   $tires->forget($key); // delete reserved product from collection
               }
            });

            $t_count =  $this->tire->where('name', 'like', '%' . $request->q . '%')->count();
        }

        //get products from reserve
        $in_reserve = $this->reserve->pluck('tcae')->all();
        $p_reserve = $this->tire->whereIn('tcae', $in_reserve)->get();

        return view('admin.reserve.index', compact('tires', 't_count', 'p_reserve'));
    }

    public function addToReserve(Request $request)
    {
        if($request->ptype == 1) {
            $this->reserve->create($request->except(['_token', '_method']));
            return redirect()->back()->with('success', 'Выбраный товар был добавлен в резерв');
        } else {
            return redirect()->back();
        }
    }

    public function deleteFromReserve(Request $request)
    {
        if($request->ptype == 1) {
            $this->reserve->where('tcae', $request->tcae)->delete();
            return redirect()->back()->with('success', 'Выбраный товар был удален с резерва');
        } else {
            return redirect()->back();
        }
    }
}
