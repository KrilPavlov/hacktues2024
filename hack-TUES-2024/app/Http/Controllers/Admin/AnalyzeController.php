<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SensorData;
use Illuminate\Http\Request;
use App\Models\Demo1;
use Carbon\Carbon;

class AnalyzeController extends Controller
{
    public function index()
    {
        $currentDateTime = Carbon::now();
        $weekAgoDateTime = $currentDateTime->subDays(7);

        $events = Demo1::where('created_at', '>=', $weekAgoDateTime)->get()->groupBy('event_id');
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
