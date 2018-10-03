<?php

namespace App\Http\Controllers\Admin;

use App\Brand;
use App\BrandAccess;
use App\BrandPercent;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function pageBrandAccess(Request $request, User $user)
    {
        if(!empty($request->q)) {
            $users = $user->where('email', 'like', '%' . $request->q . '%')->paginate(10);
        } else {
            $users = $p_reserve = $user->paginate(10);
        }

        return view('admin.settings.brand_view_access', compact('users'));
    }

    public function accessBrandStore(Request $request)
    {
        $brandExist = BrandAccess::where($request->except(['_token']))->get();

        $brandName = Brand::find($request->brand_id)->name;
        $user = User::find($request->user_id)->legal_name;

        if ($brandExist->isEmpty()) {
            BrandAccess::create($request->except(['_token']));

            return redirect()->back()->with('success', 'Отображение ' . $brandName . ' запрещенно для ' . $user);
        } else {
            return redirect()->back()->with('failed', $brandName.' для '.$user.' уже запрещен для отображения');
        }
    }

    public function getBannedBrandView(Request $request)
    {
        if ($request->ajax()) {
            $brandList = BrandAccess::where('user_id', $request->user_id)->pluck('brand_id')->all();

            $brand = Brand::whereIn('id', $brandList)->get();

            return response()->json($brand);
        }
    }

    public function deleteFromBrandAccess(Request $request)
    {
        if($request->ajax()) {
            BrandAccess::where('user_id', $request->user_id)
                ->where('brand_id', $request->brand_id)
                ->delete();
        }
    }

    public function pageBrandPercent(Request $request, User $user)
    {
        if(!empty($request->q)) {
            $users = $user->where('email', 'like', '%' . $request->q . '%')->paginate(10);
        } else {
            $users = $p_reserve = $user->paginate(10);
        }

        return view('admin.settings.brand_view_percent', compact('users'));
    }

    public function percentBrandStore(Request $request)
    {
        $brandExist = BrandPercent::where('user_id', $request->user_id)
            ->where('brand_id', $request->brand_id)
            ->get();

        $brandName = Brand::find($request->brand_id)->name;
        $user = User::find($request->user_id)->legal_name;

        if ($brandExist->isEmpty()) {
            BrandPercent::create($request->except(['_token']));

            return redirect()->back()->with('success', 'Оптовику '.$user.' для '.$brandName.' установлено '.$request->percent_value.'%');
        } else {
            return redirect()->back()->with('failed', 'Оптовику '.$user.' для '.$brandName.' уже установлен процент');
        }
    }

    public function getPercentBrandView(Request $request)
    {
        if ($request->ajax()) {
            $percentList = BrandPercent::where('user_id', $request->user_id)->pluck('brand_id')->all();

            $brands = Brand::whereIn('id', $percentList)->get();

            foreach($brands as $brand) {
                $data[] = [
                    'id'         => $brand->id,
                    'brand_name' => $brand->name,
                    'percent'    => BrandPercent::where('user_id', $request->user_id)->where('brand_id', $brand->id)->first()->percent_value,
                ];
            }

            return response()->json($data);
        }
    }

    public function deleteFromBrandPercent(Request $request)
    {
        if($request->ajax()) {
            BrandPercent::where('user_id', $request->user_id)
                ->where('brand_id', $request->brand_id)
                ->delete();
        }
    }
}
