<?php

namespace App\Http\Controllers;

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
