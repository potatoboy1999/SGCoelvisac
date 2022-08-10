<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReunionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\TravelScheduleController;
use App\Http\Controllers\UserController;
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

Route::group(["prefix"=>"intranet", "middleware"=>["auth"]], function(){
    Route::get('/',[DashboardController::class,"index"]);
    Route::get('dashboard',[DashboardController::class,"index"])->name('dashboard');

    Route::group(["prefix"=>"matriz"], function(){
        Route::get('/', [ObjectiveController::class,"index"])->name("objectives");
        Route::post('new_item', [ObjectiveController::class,"storeItem"])->name("new_item");
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
        Route::get("popup_adjacent_docs", [ActivityController::class,"popupAdjacentDocs"])->name("activity.popup.adjacents");
        Route::post("popup_update", [ActivityController::class,"popupUpdate"])->name("activity.popup.update");
        Route::post("popup_delete", [ActivityController::class,"popupDelete"])->name("activity.popup.delete");
        Route::post('add_politics', [ActivityController::class,"updatePolicy"])->name("upd_activity_policy");
        Route::post('add_adjacent', [ActivityController::class,"updateAdjacent"])->name("upd_activity_adjacent");
    });

    Route::group(["prefix"=>"comments"], function(){
        Route::get("popup_show", [CommentController::class,"popupShow"])->name('comment.popup.show');
        Route::post("popup_delete", [CommentController::class,"popupDelete"])->name('comment.popup.delete');
        Route::post("popup_update", [CommentController::class,"popupUpdate"])->name('comment.popup.update');
    });

    Route::group(["prefix"=>"document"], function(){
        Route::post('delete', [DocumentController::class,"delete"])->name('doc.delete');
    });

    Route::group(["prefix"=>"agenda"], function(){
        // -- schedule calendar
        Route::get('/', [TravelScheduleController::class, "backIndex"])->name('agenda.index');
        Route::get('/calendar', [TravelScheduleController::class, "viewCalendar"])->name('agenda.calendar');
        Route::get('/calendar/popup/schedule', [TravelScheduleController::class, "showSchedulePopup"])->name("agenda.popup.schedule");
        Route::post('/new/schedule',[TravelScheduleController::class, "storeSchedule"])->name('agenda.nuevo');
        // -- pendings schedules
        Route::get('/pendings',[TravelScheduleController::class, "viewPending"])->name("agenda.pending");
        Route::post('/confirm/schedule',[TravelScheduleController::class, "confirmSchedule"])->name('agenda.confirm');
        Route::post('/deny/schedule',[TravelScheduleController::class, "denySchedule"])->name('agenda.deny');
        // -- reports schedules
        Route::get('/reports',[TravelScheduleController::class, "viewReports"])->name("agenda.reports");
        Route::get('/reports/details',[TravelScheduleController::class, "showReport"])->name("agenda.reports.show");
        Route::post('/report/delete',[TravelScheduleController::class, "deleteReport"])->name('agenda.reports.deactivate');
        Route::post('/report/finalize',[TravelScheduleController::class, "finalizeReport"])->name('agenda.reports.finalize');
        Route::get('/reports/activity/modal',[TravelScheduleController::class, "showReportActivity"])->name("agenda.reports.activity.popup");
        Route::post('/reports/activity/save',[TravelScheduleController::class, "saveActivity"])->name("agenda.reports.activity.save");
        Route::post('/reports/activity/delete',[TravelScheduleController::class, "deleteActivity"])->name("agenda.reports.activity.delete");
        Route::get('/reports/pdf/download',[TravelScheduleController::class,"exportReportPdf"])->name('agenda.reports.pdf');
    });

    Route::group(["prefix"=>"usuario"], function(){
        Route::GET('/',[UserController::class, 'index'])->name('user.index');
        Route::POST('/deactivate',[UserController::class, 'deactivate'])->name('user.deactivate');
        Route::POST('/activate',[UserController::class, 'activate'])->name('user.activate');
        Route::GET('/save_popup',[UserController::class, 'popupSaveShow'])->name('user.popup');
        Route::POST('/save',[UserController::class, 'popupSaveNew'])->name('user.popup.save.new');
        Route::POST('/update',[UserController::class, 'popupSaveUpdate'])->name('user.popup.save.update');
        Route::get('/names',[UserController::class, 'getNames'])->name('user.name_list');
    });

    Route::group(["prefix"=>"profiles"], function(){
        Route::get('/',[ProfileController::class, 'index'])->name('user.profiles');
        Route::post('/deactivate',[ProfileController::class, 'deactivate'])->name('user.profiles.deactivate');
        Route::post('/activate',[ProfileController::class, 'activate'])->name('user.profiles.activate');

        Route::get('/save_popup',[ProfileController::class, 'popupSaveShow'])->name('user.profiles.popup');
        Route::post('/save',[ProfileController::class, 'popupSaveNew'])->name('user.profiles.popup.save.new');
        Route::post('/update',[ProfileController::class, 'popupSaveUpdate'])->name('user.profiles.popup.save.update');
    });

    Route::group(["prefix"=>"results"], function(){
        // -- reunion calendar
        Route::get('/', [ReunionController::class, "backIndex"])->name('results.index');
        Route::get('/calendar', [ReunionController::class, "viewCalendar"])->name('results.calendar');
        // Route::get('/calendar/popup/reunion', [ReunionController::class, "showReunionPopup"])->name('results.popup.reunion');
        Route::get('/reunion/new/', [ReunionController::class, "createReunion"])    ->name('results.create');
        Route::get('/reunion/modify/', [ReunionController::class, "createModify"])  ->name('results.modify');
        Route::get('/reunion/popup', [ReunionController::class, "showPopup"])       ->name('results.reunion.popup');
        Route::post('/reunion/new/', [ReunionController::class, "storeReunion"])    ->name('results.store');
        Route::post('/reunion/update/', [ReunionController::class, "updateReunion"])->name('results.update');
        Route::post('/reunion/delete/', [ReunionController::class, "deleteReunion"])->name('results.reunion.delete');
        // -- reports schedules
        Route::get('/reunions', [ReunionController::class, "viewReunions"])->name('results.reunions');
    });

});

Route::get("/", function(){return view("front.index"); });
Route::get("/menu", [ActivityController::class,'showMenu'])->name('front.menu');

// MATRIZ
Route::get("/matriz", [ActivityController::class,'showMatrix'])->name('front.activity.matrix.show');
Route::get("/activity/popup_adjacent_docs", [ActivityController::class,"popupFrontAdjacentDocs"])->name("front.activity.popup.adjacents");

// TRAVEL SCHEDULES
Route::get("/schedules", [TravelScheduleController::class,'frontIndex'])->name('front.schedules');
Route::get("/schedules/calendar", [TravelScheduleController::class,'viewCalendar'])->name('front.schedules.calendar');
Route::get('/schedules/popup', [TravelScheduleController::class, "showSchedulePopup"])->name("front.schedules.popup");

// REUNIONS
Route::get("/reunions", [ReunionController::class,'frontIndex'])->name('front.reunions');
Route::get("/reunions/calendar", [ReunionController::class,'viewCalendar'])->name('front.reunions.calendar');
Route::get('/reunions/popup', [ReunionController::class, "showPopup"])->name("front.reunions.popup");

// EXTRAS
Route::get("/document/download", [DocumentController::class,"download"])->name('doc.download');
Route::get("/test_mail", [DashboardController::class,"testMail"])->name('test.mail');
Route::get("/test_pdf", [DashboardController::class,"testPdf"])->name('test.pdf');

require __DIR__.'/auth.php';
