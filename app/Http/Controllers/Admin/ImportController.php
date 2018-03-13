<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImportExcelRequest;
use App\ImportExcelToDb;
use App\Http\Controllers\Controller;

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
}
