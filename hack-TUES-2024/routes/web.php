<?php

use App\Http\Controllers\Admin\AnalyzeController;
use App\Http\Controllers\SensorDataController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\NodeController as AdminNodeController;
use App\Models\GridSquare;
use App\Models\Node;
use GrahamCampbell\ResultType\Success;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('map2');
})->name('welcome');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('analyze', [AnalyzeController::class, 'index'])->name('analyze');
    Route::get('map', [AnalyzeController::class, 'getMap'])->name('map');
    Route::resource('nodes', AdminNodeController::class);
    Route::post('nodes/datatable', [AdminNodeController::class, 'getDatatable'])->name('nodes.datatable');
    Route::get('chart-datas', [AnalyzeController::class, 'getAjaxPeopleCount'])->name('getAjax');
    Route::get('restore/population', function(){
        $nodes = Node::all();
        if($nodes->count()){
            foreach($nodes as $node){
                $node->population = 0;
                $node->save();
            }
        }
        $grid_square = GridSquare::all();
        if($grid_square->count()){
            foreach($grid_square as $grid){
                $grid->population = 0;
                $grid->save();
            }
        }
        return redirect()->back()->with(['success' => true, 'message' => "Популациите бяха нулирани"]);

    })->name('restore.population');
});

Route::post('/post', [SensorDataController::class, 'index'])->name('post');
Route::post('/sim', [SensorDataController::class, 'postSim'])->name('post_sim');
Route::get('/get', [SensorDataController::class, 'show'])->name('show');
