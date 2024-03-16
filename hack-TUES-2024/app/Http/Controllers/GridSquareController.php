<?php

namespace App\Http\Controllers;

use App\Models\GridSquare;
use Illuminate\Http\Request;

class GridSquareController extends Controller
{
    public function getGridSquareData()
    {
        $gridSquares = GridSquare::all()->toArray();
        return response()->json($gridSquares);
    }
}
