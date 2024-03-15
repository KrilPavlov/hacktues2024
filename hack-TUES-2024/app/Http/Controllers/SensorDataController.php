<?php

namespace App\Http\Controllers;

use App\Models\Demo1;
use App\Models\SensorData;
use App\Models\SensorDataSim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorDataController extends Controller
{
    public function index(Request $request)
    {
        $sensore = new SensorData;
        $data = $request->all();

        $last_data_0 = 0;
        $last_data_1 = 0;
        $event = 1;


        if (Demo1::all()->count()) {
            $last_data_0 = Demo1::where('sector_id', 0)->latest()->population;
            $last_data_1 = Demo1::where('sector_id', 1)->latest()->population;
            $event = Demo1::latest()->event_id;
        }
        if ($data['direction'] == 'True') {
            if ($last_data_0 > 0) {
                --$last_data_0;
                ++$last_data_1;
                ++$event;
            } else {
                ++$last_data_1;
                ++$event;
            }
            $new_event = new Demo1;
            $new_event->event_id = $event;
            $new_event->sector_id = 0;
            $new_event->population = $last_data_0;
            $new_event->save();

            $new_event = new Demo1;
            $new_event->event_id = $event;
            $new_event->sector_id = 1;
            $new_event->population = $last_data_1;
            $new_event->save();
        } else {
            if ($last_data_1 > 0) {
                ++$last_data_0;
                --$last_data_1;
                ++$event;
            } else {
                ++$last_data_0;
                ++$event;
            }
            $new_event = new Demo1;
            $new_event->event_id = $event;
            $new_event->sector_id = 0;
            $new_event->population = $last_data_0;
            $new_event->save();

            $new_event = new Demo1;
            $new_event->event_id = $event;
            $new_event->sector_id = 1;
            $new_event->population = $last_data_1;
            $new_event->save();
        }


        if ($data['speed'] != 0) {
            $sensore->sensore_id = "Ultrasonic sensosre 1";
            $sensore->speed = $data['speed'];
            $sensore->direction = $data['direction'];
            $sensore->save();
            Log::info($request->all());
        }
        return $request->all();
    }
    public function postSim(Request $request)
    {
        Log::info($request->all());
        $data = $request->all();
        $sensor = new SensorDataSim;
        $sensor->sensor_id = $data['sensorID'];
        $sensor->direction = $data['Direction'];
        $sensor->speed = $data['speed'];
        $sensor->sim_id = $data['sim_id'];
        $sensor->detected_at = $data['DetectedAT'];
        $sensor->save();
        return $request->all();
    }
    public function show()
    {
        $data = SensorData::all();
        return $data;
    }
}
