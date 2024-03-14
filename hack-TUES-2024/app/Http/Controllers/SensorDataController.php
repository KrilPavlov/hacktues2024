<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\Request;

class SensorDataController extends Controller
{
    public function index(Request $request){
        dd($request->all());
    }
    public function show(){
        $data = SensorData::all();
        return $data;
    }
}
