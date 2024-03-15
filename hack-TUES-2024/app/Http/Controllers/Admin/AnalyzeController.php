<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyzeController extends Controller
{
    public function index()
    {
        return view('admin.analyze');
    }

    public function getMap(){
        return view('map');
    }
}
