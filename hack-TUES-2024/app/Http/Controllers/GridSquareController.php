<?php

namespace App\Http\Controllers;

use App\Models\GridSquare;
use Illuminate\Http\Request;

class GridSquareController extends Controller
{
    public function getGridSquareData()
    {
        $gridSquares = GridSquare::all();
        $resultArray = [];
        if($gridSquares->count()){
            foreach($gridSquares as $grid){
                array_push($resultArray, ['lat1' => $grid->alat, 'lng1' => $grid->along, 'lat2'=>$grid->blat, 'lng2'=>$grid->blong, 'lat3' => $grid->clat, 'lng3' => $grid->clong, 'lat4' => $grid->dlat, 'lng4' => $grid->dlong, 'pop' => $grid->population]);
            }
        }
        // return view('map', compact('resultArray'));
        return response()->json($resultArray);
    }
}
