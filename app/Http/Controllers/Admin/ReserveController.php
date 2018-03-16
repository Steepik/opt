<?php

namespace App\Http\Controllers\Admin;

use App\Reserve;
use App\Tire;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReserveController extends Controller
{
    public function index(Request $request)
    {
        $tires = array();
        $t_count = 0;
        if(!empty($request->q)) {
            $tires = Tire::where('name', 'like', '%' . $request->q . '%')->paginate(25);
            $tires->each(function($item, $key) use ($tires) {
               $reserved = Reserve::where('tcae', $item->tcae)->first();
               if(! is_null($reserved)) {
                   $item['reserved'] = true;
                   $tires->forget($key); // delete reserved product from collection
               }
            });

            $t_count =  Tire::where('name', 'like', '%' . $request->q . '%')->count();
        }

        //get products in reserve
        $in_reserve = Reserve::pluck('tcae')->all();

        $p_reserve = Tire::whereIn('tcae', $in_reserve)->get();

        return view('admin.reserve.index', compact('tires', 't_count', 'p_reserve'));
    }

    public function addToReserve(Request $request)
    {
        if($request->ptype == 1) {
            Reserve::create($request->except(['_token', '_method']));
            return redirect()->back()->with('success', 'Выбраный товар был добавлен в резерв');
        } else {
            return redirect()->back();
        }
    }

    public function deleteFromReserve(Request $request)
    {
        if($request->ptype == 1) {
            Reserve::where('tcae', $request->tcae)->delete();
            return redirect()->back()->with('success', 'Выбраный товар был удален с резерва');
        } else {
            return redirect()->back();
        }
    }
}
