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
        $result_array = [];
        foreach ($events as $event) {
            $arr = [];
            array_push($arr, $event->first()->event_id);
            foreach ($event as $sector) {
                array_push($arr, $sector->population);
            }
            array_push($result_array, $arr);
        }
        return view('admin.analyze', ['result_json' => json_encode($result_array)]);
    }

    public function getMap()
    {
        return view('map');
    }

    public function getAjaxPeopleCount()
    {
        $currentDateTime = Carbon::now();
        $weekAgoDateTime = $currentDateTime->subDays(7);

        $events = Demo1::where('created_at', '>=', $weekAgoDateTime)->get()->groupBy('event_id');
        $result_array = [];
        foreach ($events as $event) {
            $arr = [];
            Log:info(serialize($result_array));
            foreach ($event as $sector) {
                array_push($arr, $sector->population);
            }
            array_push($result_array, $arr);
        }
    }
}
