<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
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
    public function show()
    {
        $data = SensorData::all();
        return $data;
    }
}
