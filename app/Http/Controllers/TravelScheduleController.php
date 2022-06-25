<?php

namespace App\Http\Controllers;

use App\Models\Branch;
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
            $qSchedule->where('estado',1)
                    ->where('viaje_comienzo','>=',$year.'-01-01')
                    ->where('viaje_comienzo','<',($year+1).'-01-01')
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
        $schedule->usuario_id = null;
        $schedule->sede_id = null;
        $schedule->viaje_comienzo = null;
        $schedule->viaje_fin = null;
        $schedule->vehiculo = null;
        $schedule->hospedaje = null;
        $schedule->viaticos = null;
        $schedule->estado = null;
        $schedule->validacion_uno = null;
        $schedule->validacion_dos = null;
    }
}
