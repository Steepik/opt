<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TireResource;
use App\Http\Controllers\Controller;
use App\Tire;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tires = Tire::paginate(20);

        return TireResource::collection($tires);
    }
}
