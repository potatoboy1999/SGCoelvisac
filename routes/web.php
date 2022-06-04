<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ObjectiveController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {return view('welcome');});
Route::get('/', function () {return redirect('/login');});

Route::group(["middleware"=>["auth"]], function(){
    Route::get('dashboard',[DashboardController::class,"index"])->name('dashboard');
    Route::get('gestor_objetivos', [ObjectiveController::class,"index"])->name("objectives");

    Route::group(["prefix"=>"gestor"], function(){
        Route::get('objetivos', [ObjectiveController::class,"index"])->name("objectives");
        Route::post('nuevo_item', [ObjectiveController::class,"storeItem"])->name("new_item");
        Route::get("all_items", [ObjectiveController::class,"all_items"])->name("api_all_activities");
    });
});

require __DIR__.'/auth.php';
