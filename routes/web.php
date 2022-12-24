<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AreaRoleController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HighlightController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReunionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\TravelScheduleController;
use App\Http\Controllers\UserController;
use App\Models\Highlight;
use App\Models\TravelSchedule;
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
        Route::get('/getMatrix', [ObjectiveController::class,"getPilarMatrix"])->name("objectives.matrix");
        Route::get('/pdf', [ObjectiveController::class,"viewPDF"])->name("objectives.pdf");
        Route::post('new_item', [ObjectiveController::class,"storeItem"])->name("new_item");
        Route::get("all_items", [ObjectiveController::class,"allItems"])->name("api_all_activities");

        Route::get('obj_estrategico/newForm', [ObjectiveController::class,"getNewForm"])->name("obj_strat.matrix.create");
        Route::get('obj_estrategico/editForm', [ObjectiveController::class,"getEditForm"])->name("obj_strat.matrix.edit");
        Route::post('obj_estrategico/store', [ObjectiveController::class,"storeStrat"])->name("obj_strat.matrix.store");
        Route::post('obj_estrategico/update', [ObjectiveController::class,"updateStrat"])->name("obj_strat.matrix.update");

        Route::get('obj_especificos/', [ObjectiveController::class,"specificsIndex"])->name("specifics");
        Route::get('obj_especificos/getSummMatrix', [ObjectiveController::class,"getStrategicSummMatrix"])->name("specifics.summarymatrix");
        Route::get('obj_especificos/getMatrix', [ObjectiveController::class,"getspecificsMatrix"])->name("specifics.matrix");
        Route::get('obj_especificos/newSpecForm', [ObjectiveController::class,"getNewSpecForm"])->name("specifics.matrix.create");
        Route::get('obj_especificos/editSpecForm', [ObjectiveController::class,"getEditSpecForm"])->name("specifics.matrix.edit");
        Route::post('obj_especificos/store', [ObjectiveController::class,"storeSpecific"])->name("specifics.matrix.store");
        Route::post('obj_especificos/update', [ObjectiveController::class,"updateSpecific"])->name("specifics.matrix.update");

        Route::get('obj_especificos/acciones', [ActionController::class,"index"])->name("actions");
        Route::get('obj_especificos/acciones/getMatrix', [ActionController::class,"getMatrix"])->name("actions.matrix");
    });

    Route::group(["prefix"=>"action"], function(){
        Route::get('/createForm',[ActionController::class,"create"])->name("action.create");
        Route::get('/edit',[ActionController::class,"edit"])->name("action.edit");
        Route::post('/store',[ActionController::class,"store"])->name("action.store");
        Route::post('/update',[ActionController::class,"update"])->name("action.update");
        Route::post('/delete', [ActionController::class,"delete"])->name("action.delete");
        Route::get("/popup_docs", [ActionController::class,"popupDocs"])->name("action.popup.docs");
        Route::post('/delete', [ActionController::class,"delete"])->name("action.delete");
        Route::post('/add_documents', [ActionController::class,"addDocuments"])->name("action.docs.store");
        Route::post('/delete_documents', [ActionController::class,"deleteDocuments"])->name("action.docs.delete");
    });

    Route::group(["prefix"=>"specific_obj"], function(){
        Route::get('/', [ObjectiveController::class,"specificMatrixIndex"])->name("specifics_matrix");
        Route::get('/get_matrix', [ObjectiveController::class,"getSpecificMatrix"])->name("spec_matrix.matrix");
    });

    Route::group(["prefix"=>"roles"], function(){
        Route::get('/', [AreaRoleController::class,"index"])->name("areaRoles");
        Route::post('new', [AreaRoleController::class,"storeItem"])->name("areaRoles.store");
        Route::get('popup_edit', [AreaRoleController::class,"popUpEdit"])->name("areaRoles.popup.edit");
        Route::post('update', [AreaRoleController::class,"update"])->name("areaRoles.update");
        Route::post('delete', [AreaRoleController::class,"delete"])->name("areaRoles.delete");
    });

    Route::group(["prefix"=>"kpi"], function(){
        Route::get('/',[KpiController::class,"index"])->name("kpi");
        Route::get('/getNowMatrix',[KpiController::class,"getMatrixNow"])->name("kpi.matrix_now");
        Route::get('/getFutureMatrix',[KpiController::class,"getMatrixFuture"])->name("kpi.matrix_future");
        Route::get('/getNowBar',[KpiController::class,"getGraphDataNow"])->name("kpi.bar_now");
        Route::post('/store',[KpiController::class,"store"])->name("kpi.store");
        Route::post('/update',[KpiController::class,"update"])->name("kpi.update");

        Route::get('/highlights',[HighlightController::class,"getMatrix"])->name('kpi.highlights');
        Route::post('/storeHighlight',[HighlightController::class,"store"])->name('kpi.highlights.store');
        Route::post('/deleteHighlight',[HighlightController::class,"delete"])->name('kpi.highlights.delete');

        Route::get('/getKpiForm', [KpiController::class,"getAddKpiForm"])->name("kpi.redirect.form");
        Route::post('/delete', [KpiController::class,"delete"])->name("kpi.delete");
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
        Route::get('/schedule/pdf/download',[TravelScheduleController::class,"exportSchedulePdf"])->name('agenda.schedule.pdf');
        // -- reports tracking
        Route::get('/tracking', [TravelScheduleController::class, "viewTrackingList"])->name('agenda.tracking');
        Route::get('/tracking/popup', [TravelScheduleController::class, "showTrackActivity"])->name('agenda.tracking.popup');
        Route::post('/tracking/update', [TravelScheduleController::class, "updateTrackActivity"])->name('agenda.tracking.update');
        Route::post('/tracking/close', [TravelScheduleController::class, "closeTrackActivity"])->name('agenda.tracking.close');
        Route::get('/tracking/report_form', [TravelScheduleController::class, "getReportForm"])->name('agenda.tracking.form');
        Route::post('/tracking/pdf', [TravelScheduleController::class, "reportPdf"])->name('agenda.tracking.pdf');
    });

    Route::group(["prefix"=>"users"], function(){
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
        Route::get('/reunion', [ReunionController::class, "viewReunion"])->name('results.reunion');
        Route::get('/calendar', [ReunionController::class, "viewCalendar"])->name('results.calendar');
        // Route::get('/calendar/popup/reunion', [ReunionController::class, "showReunionPopup"])->name('results.popup.reunion');
        // Route::get('/reunion/new/', [ReunionController::class, "createReunion"])    ->name('results.create');
        // Route::get('/reunion/modify/', [ReunionController::class, "createModify"])  ->name('results.modify');
        // Route::get('/reunion/popup', [ReunionController::class, "showPopup"])       ->name('results.reunion.popup');
        Route::post('/reunion/new/', [ReunionController::class, "storeReunion"])    ->name('results.store');
        Route::post('/reunion/update/', [ReunionController::class, "updateReunion"])->name('results.update');
        Route::post('/reunion/delete/', [ReunionController::class, "deleteReunion"])->name('results.reunion.delete');
        // -- reports schedules
        // Route::get('/reunions', [ReunionController::class, "viewReunions"])->name('results.reunions');
        // -- add document
        Route::get('/reunion/doc', [ReunionController::class, "viewDocument"])->name('results.doc');
        Route::post('/reunion/doc/new', [ReunionController::class, "storeDocument"])->name('results.doc.store');
        Route::post('/reunion/doc/delete', [ReunionController::class, "deleteDocument"])->name('results.doc.delete');
    });

    Route::group(['prefix' => 'branches'], function(){
        Route::get('/', [BranchController::class, "index"])->name('branches.index');
        Route::get('/new', [BranchController::class, "create"])->name('branches.new');
        Route::get('/modify', [BranchController::class, "edit"])->name('branches.edit');
        Route::post('/update', [BranchController::class, "update"])->name('branches.save.update');
        Route::post('/store', [BranchController::class, "store"])->name('branches.save.new');
        Route::post('/delete', [BranchController::class, "destroy"])->name('branches.delete');
    });

    Route::group(['prefix' => 'areas'], function(){
        Route::get('/', [AreaController::class, "index"])->name('areas.index');
        Route::get('/new', [AreaController::class, "create"])->name('areas.new');
        Route::get('/modify', [AreaController::class, "edit"])->name('areas.edit');
        Route::post('/update', [AreaController::class, "update"])->name('areas.save.update');
        Route::post('/store', [AreaController::class, "store"])->name('areas.save.new');
        Route::post('/delete', [AreaController::class, "destroy"])->name('areas.delete');
    });

    Route::group(['prefix' => 'positions'], function(){
        Route::get('/', [PositionController::class, "index"])->name('position.index');
        Route::get('/new', [PositionController::class, "create"])->name('position.new');
        Route::get('/modify', [PositionController::class, "edit"])->name('position.edit');
        Route::post('/update', [PositionController::class, "update"])->name('position.save.update');
        Route::post('/store', [PositionController::class, "store"])->name('position.save.new');
        Route::post('/delete', [PositionController::class, "destroy"])->name('position.delete');
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
Route::get("/reunions/details", [ReunionController::class,'viewFrontReunion'])->name('front.reunion.details');
Route::get("/reunions/calendar", [ReunionController::class,'viewFrontCalendar'])->name('front.reunion.calendar');
Route::get("/reunions/document", [ReunionController::class,'viewDocument'])->name('front.reunion.document');

// EXTRAS
Route::get("/document/download", [DocumentController::class,"download"])->name('doc.download');
Route::get("/test_mail", [DashboardController::class,"testMail"])->name('test.mail');
Route::get("/test_pdf", [DashboardController::class,"testPdf"])->name('test.pdf');
Route::get("/test_page", [DashboardController::class,"testPage"])->name('test.index');

require __DIR__.'/auth.php';
