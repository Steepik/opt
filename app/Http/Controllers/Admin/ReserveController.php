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
            $tires->each(function($item, $key){
               $reserved = Reserve::where('tcae', $item->tcae)->first();
               if(! is_null($reserved)) {
                   $item['reserved'] = true;
               }
            });

            $t_count =  Tire::where('name', 'like', '%' . $request->q . '%')->count();
        }

        return view('admin.reserve.index', compact('tires', 't_count'));
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
