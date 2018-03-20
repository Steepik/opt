<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImportExcelRequest;
use App\ImportExcelToDb;
use App\Http\Controllers\Controller;
use Excel;
use App\Tire;
use App\Wheel;
use Illuminate\Support\Collection;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.import.index');
    }

    public function uploadExcel(ImportExcelRequest $request)
    {
        if($request->hasFile('uploadfile')) {
            $import = new ImportExcelToDb();
            $import->import($request->uploadfile->getPathName());

            return redirect()->back()->with('updated', '');
        }
    }

    public function autopiter()
    {
            Excel::create('Price-list', function($excel){
                $excel->sheet('Price-list', function($sheet){
                    $sheet->setAutoSize(true);
                    $sheet->rows(array(
                        array(
                            'Каталог',
                            'Номер',
                            'Наименование',
                            'Цена',
                            'Наличие',
                        ),
                    ));
                    $tires = Tire::where('quantity', '>', 0)->get();
                    $wheels = Wheel::where('quantity', '>', 0)->get();
                    $tires->each(function($item){
                        $item['brand'] = $item->brand->name;
                    });
                    $wheels->each(function($item){
                        $item['brand'] = $item->brand->name;
                    });

                    $data = array_merge($tires->toArray(), $wheels->toArray());

                    foreach($data as $item) {
                        $sheet->rows(array(
                            array(
                                $item['brand']['name'],
                                $item['tcae'],
                                $item['name'],
                                $item['price_opt'],
                                $item['quantity'],
                            ),
                        ));
                    }


                });
            })->download('xls');
    }
}
