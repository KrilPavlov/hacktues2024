<?php

namespace App\Http\Controllers;

use App\Models\Demo1;
use App\Models\Sensor;
use App\Models\Node;
use App\Models\GridSquare;
use App\Models\SensorData;
use App\Models\SensorDataSim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorDataController extends Controller
{
    public function index(Request $request)
    {
        // Log:info($request->all());
        $data = $request->all();
        if(isset($data['direction'])){
        $sensore = new SensorData;
        $data = $request->all();

        $last_data_0 = 0;
        $last_data_1 = 0;
        $event = 0;


        if (Demo1::all()->count()) {
            // Fetching the latest population for sector_id 0
            $last_data_0 = Demo1::where('sector_id', 0)
            ->orderBy('id', 'desc') 
            ->first()
            ->population;

            // Fetching the latest population for sector_id 1
            $last_data_1 = Demo1::where('sector_id', 1)
            ->orderBy('id', 'desc') // Replace 'created_at' with your actual date column if different
            ->first()
            ->population;

            // Fetching the latest event_id
            $event = Demo1::orderBy('id', 'desc') // Again, assuming 'created_at' is the date column
            ->first()
            ->event_id;
        }
        ++$event;
        
        if ($data['direction'] == '1') {
            if ($last_data_0 > 0) {
                --$last_data_0;
                ++$last_data_1;
            } else {
                ++$last_data_1;
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
            } else {
                ++$last_data_0;
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
        }
        return $request->all();
    }
    }
    public function postSim(Request $request)
    {

        $sensor_id = $request['sensorID'];
        $dir = $request['Direction'];
        $sensor = Sensor::find($sensor_id);
        $start_id = 1;
        $end_id = 1;

        if ($sensor){
            
            $start_id = $sensor->start_node;
            $end_id = $sensor->end_node;
            $start = Node::find($start_id);
            $end = Node::find($end_id);
            Log:info($start_id);
            if ($start && $end){
                
                $old_start_population = $start->population;
                $old_end_population = $end->population;

                if($dir == 'True'){
                    $start->population = ($start->population>0) ? $start->population - 1 : 0;
                    $end->population = $end->population+1;
                    $start->save();
                    $end->save();
                }
                else{
                    $start->population = $start->population+1;
                    $end->population = ($end->population>0) ? $end->population - 1 : 0;
                    $start->save();
                    $end->save();
                }

                $sq_start = $start->sq_Id;
                $sq_end = $end->sq_Id;
                $start_square = GridSquare::find($sq_start);
                $end_square = Node::find($sq_start);
                if ($start_square && $end_square){
                    $start_population = $start_square->population-$old_start_population+$start->population;
                    $end_population = $end_square->population-$old_end_population+$end->population;

                    $start_square->population = ($start_population >0) ? $start_population : 0;
                    $end_square->population = ($end_population >0) ? $end_population : 0;
                    $start_square->save();
                    $end_square->save();
                }
            }

        }


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
