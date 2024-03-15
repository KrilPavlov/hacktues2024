<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SensorData;
use Illuminate\Http\Request;

class AnalyzeController extends Controller
{
    public function index()
    {
        $right = SensorData::where('direction', 'True')->get();
        $left = SensorData::where('direction', 'False')->count();
        // dd($left);
        return view('admin.analyze');
    }

    public function getMap(){
        return view('map');
    }

    public function getAjaxPeopleCount(){
        $right = SensorData::where('direction', 'True')->count();
        $left = SensorData::where('direction', 'False')->count();

        return response()->json(['right' => $right, 'left' => $left]);
    }
}
