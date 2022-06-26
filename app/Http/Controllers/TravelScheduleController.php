<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\TravelActivity;
use App\Models\TravelSchedule;
use Illuminate\Http\Request;

class TravelScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function backIndex(Request $request){
        $page = "objectives";
        $bcrums = ["Agendas"];
        $year = intval(isset($request->year)?$request->year:date('Y'));
        // return $branches->toArray();

        return view('intranet.travels.index',[
            "page"=>$page,
            "bcrums" => $bcrums,
            "year" => $year
        ]);
    }

    public function viewCalendar(Request $request){
        $year = intval(isset($request->year)?$request->year:date('Y'));
        $branches = Branch::where('estado', 1);
        $branches->with(['travel_schedules'=>function($qSchedule) use ($year){
            $qSchedule->where('estado','>',0)
                    ->where('viaje_comienzo','>=',$year.'-01-01')
                    ->where('viaje_comienzo','<',($year+1).'-01-01')
                    ->where('validacion_uno', 1)
                    ->where('validacion_dos', 1)
                    ->orderBy('viaje_comienzo','asc');
            $qSchedule->with(['user.position']);
        }]);
        $branches = $branches->get();
        return view('intranet.travels.calendar',[
            "year" => $year,
            "branches" => $branches
        ]);
    }

    public function storeSchedule(Request $request){        
        $schedule = new TravelSchedule;
        $schedule->usuario_id = $request->user;
        $schedule->sede_id = $request->branch;
        $schedule->viaje_comienzo = date_format(date_create_from_format('d/m/Y',$request->date_start),'Y-m-d');
        $schedule->viaje_fin = date_format(date_create_from_format('d/m/Y',$request->date_end),'Y-m-d');
        $schedule->vehiculo = isset($request->vehicle_check)?1:0;
        $schedule->hospedaje = isset($request->hab_check)?1:0;
        $schedule->viaticos = isset($request->extras_check)?1:0;
        $schedule->estado = 1;
        $schedule->validacion_uno = 0;
        $schedule->validacion_dos = 0;
        $schedule->save();

        $schId = $schedule->id;
        if(isset($request->area_act)){
            foreach ($request->area_act as $activity) {
                $new_activity = new TravelActivity;
                $new_activity->descripcion = $activity;
                $new_activity->tipo = 1;
                $new_activity->agenda_viaje_id = $schId;
                $new_activity->estado = 1;
                $new_activity->save();
            }
        }

        if(isset($request->non_area_act)){
            foreach ($request->non_area_act as $activity) {
                $new_activity = new TravelActivity;
                $new_activity->descripcion = $activity;
                $new_activity->tipo = 2;
                $new_activity->agenda_viaje_id = $schId;
                $new_activity->estado = 1;
                $new_activity->save();
            }
        }

        return [
            'status' => 'ok'
        ];

    }

    public function viewPending(Request $request){
        $page = "objectives";
        $bcrums = ["Agendas"];
        $schedules = TravelSchedule::where('estado','>',0)
                                ->where('estado','<',5)
                                ->orderBy('created_at','desc')
                                ->orderBy('viaje_comienzo','desc');
        $schedules->with(['user']);
        $schedules->with(['branch']);
        $schedules = $schedules->get();
        return view('intranet.travels.pending',[
            'page' => $page,
            'bcrums' => $bcrums,
            'schedules' => $schedules
        ]);
    }
}
