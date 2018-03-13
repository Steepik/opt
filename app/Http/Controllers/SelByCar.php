<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SelByCar extends Controller
{
    public function index($fvendor, $fcar, $fyear, $fmod)
    {

        //BY CAR
        $vendors = DB::table('sel_by_cars')->select('fvendor')->distinct()->get();

        $result = DB::table('sel_by_cars')
                ->select(['ftips', 'fpriznak', 'fos', 'fsizes'])
                ->where([
                    ['fvendor', '=', $fvendor],
                    ['fcar', '=', $fcar],
                    ['fyear', '=', $fyear],
                    ['fmodification', '=', $fmod]
                ])->get();

        return view('bycar', compact('vendors', 'result'));
    }

    public function getCarModels(Request $request)
    {
        if($request->ajax()) {
            $fvendor = $request->fvendor;
            $models = DB::table('sel_by_cars')->select('fcar')->where('fvendor', '=', $fvendor)->distinct()->groupBy('fcar')->get();

            return response()->json($models);
        }
    }

    public function getCarYear(Request $request)
    {
        if($request->ajax()) {
            $fcar = $request->fcar;
            $year = DB::table('sel_by_cars')->select('fyear')->where('fcar', '=', $fcar)->distinct()->groupBy('fyear')->get();

            return response()->json($year);
        }
    }

    public function getCarMod(Request $request)
    {
        if($request->ajax()) {
            $fyear = $request->fyear;
            $fcar = $request->fcar;
            $mod = DB::table('sel_by_cars')->select('fmodification')->where('fyear', '=', $fyear)->where('fcar', '=', $fcar)->distinct()->groupBy('fmodification')->get();

            return response()->json($mod);
        }
    }
}
