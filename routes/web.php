<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ThemeController;
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

Route::group(["middleware"=>["auth"]], function(){
    Route::get('dashboard',[DashboardController::class,"index"])->name('dashboard');
    Route::get('gestor_objetivos', [ObjectiveController::class,"index"])->name("objectives");

    Route::group(["prefix"=>"gestor"], function(){
        Route::get('objetivos', [ObjectiveController::class,"index"])->name("objectives");
        Route::post('nuevo_item', [ObjectiveController::class,"storeItem"])->name("new_item");
        Route::get("all_items", [ObjectiveController::class,"allItems"])->name("api_all_activities");
    });

    Route::group(["prefix"=>"role"], function(){
        Route::get("popup_edit",[RoleController::class,"popupEdit"])->name("role.popup.edit");
        Route::post("popup_update",[RoleController::class,"popupUpdate"])->name("role.popup.update");
        Route::post("popup_delete",[RoleController::class,"popupDelete"])->name("role.popup.delete");
    });

    Route::group(["prefix"=>"theme"], function(){
        Route::get("popup_edit",[ThemeController::class,"popupEdit"])->name("theme.popup.edit");
        Route::post("popup_update",[ThemeController::class,"popupUpdate"])->name("theme.popup.update");
        Route::post("popup_delete",[ThemeController::class,"popupDelete"])->name("theme.popup.delete");
    });

    Route::group(["prefix"=>"activity"], function(){
        Route::get("popup_edit", [ActivityController::class,"popupEdit"])->name("activity.popup.edit");
        Route::post("popup_update", [ActivityController::class,"popupUpdate"])->name("activity.popup.update");
        Route::post('add_politics', [ActivityController::class,"updatePolicy"])->name("upd_activity_policy");
        Route::post('add_adjacent', [ActivityController::class,"updateAdjacent"])->name("upd_activity_adjacent");
    });

    Route::group(["prefix"=>"document"], function(){
        Route::get('download', [DocumentController::class,"download"])->name('doc.download');
        Route::post('delete', [DocumentController::class,"delete"])->name('doc.delete');
    });

});

Route::get("/", function(){return view("front.index"); });

require __DIR__.'/auth.php';
