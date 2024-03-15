<?php

use App\Http\Controllers\Admin\AnalyzeController;
use App\Http\Controllers\SensorDataController;
use Illuminate\Support\Facades\Route;

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
    return view('admin.analyze');
})->name('welcome');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('analyze', [AnalyzeController::class, 'index'])->name('analyze');
    Route::get('map', [AnalyzeController::class, 'getMap'])->name('map');
});

Route::post('/post', [SensorDataController::class, 'index'])->name('post');
Route::post('/sim', [SensorDataController::class, 'postSim'])->name('post_sim');
Route::get('/get', [SensorDataController::class, 'show'])->name('show');
