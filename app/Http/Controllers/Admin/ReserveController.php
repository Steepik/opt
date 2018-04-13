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
        $appends = $request->all();

        $in_reserve = $this->reserve->pluck('tcae')->all();

        if(!empty($request->q)) {
            $tires = $this->tire->whereNotIn('tcae', $in_reserve)
                ->where('name', 'like', '%' . $request->q . '%')
                ->paginate(25);

            $t_count = $this->tire->whereNotIn('tcae', $in_reserve)
                ->where('name', 'like', '%' . $request->q . '%')
                ->count();
        }

        if(!empty($request->r)) {
            $p_reserve = $this->tire->whereIn('tcae', $in_reserve)->where('name', 'like', '%' . $request->r . '%')->paginate(10);
        } else {
            $p_reserve = $this->tire->whereIn('tcae', $in_reserve)->paginate(10);
        }

        return view('admin.reserve.index', compact('tires', 't_count', 'p_reserve', 'appends'));
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
