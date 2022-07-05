<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\TravelActivity;
use App\Models\TravelSchedule;
use Illuminate\Http\Request;

class TravelScheduleController extends Controller
{
    public function backIndex(Request $request){
        $page = "objectives";
        $bcrums = ["Agendas"];
        $year = intval(isset($request->year)?$request->year:date('Y'));
        $month = intval(isset($request->month)?$request->month:date('m'));
        // return $branches->toArray();

        return view('intranet.travels.index',[
            "page"=>$page,
            "bcrums" => $bcrums,
            "year" => $year,
            "month" => $month,
        ]);
    }

    public function viewCalendar(Request $request){
        $year = intval(isset($request->year)?$request->year:date('Y'));
        $month = intval(isset($request->month)?$request->month:date('m'));
        $endMonth = $month + 1;
        if($endMonth > 12){
            $endMonth = 1;
        }

        $schedules = TravelSchedule::where('estado','>',0)
                    ->where('viaje_comienzo','>=',$year.'-'.$month.'-01')
                    ->where('viaje_comienzo','<',($year+1).'-'.$endMonth.'-01')
                    ->where('validacion_uno', 2) // validation 1 accepted
                    ->where('validacion_dos', 2) // validation 2 accepted
                    ->orderBy('viaje_comienzo','asc')
                    ->orderBy('viaje_fin','desc');
        $schedules->with(['user.position']);
        $schedules = $schedules->get();

        return view('intranet.travels.calendar',[
            "year" => $year,
            "month" => $month,
            "schedules" => $schedules
        ]);
    }

    public function showSchedulePopup(Request $request){
        $schedule = null;
        if(isset($request->id)){
            $schedule = TravelSchedule::where('id', $request->id)
                                ->where('estado', 5);
            $schedule->with(['activities']);
            $schedule = $schedule->first();
        }
        $branches = Branch::where('estado', 1)->get();
        $start_date = $request->start_date;
        return view('intranet.travels.modal_schedule',[
            'branches'  => $branches,
            'schedule'  => $schedule,
            's_date'    => $start_date
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
        $type = isset($request->type)?$request->type:1;
        $schedules = TravelSchedule::where('estado','>',0);
                                //->where('estado','<',5)
        if($type == 1){
            $schedules->where('validacion_uno', 0); // not set
            $schedules->where('validacion_dos', 0); // not set
        }else if ($type == 2) {
            $schedules->where('validacion_uno', 2); // aprobado
            $schedules->where('validacion_dos', 0); // not set
        }
        $schedules->orderBy('created_at','desc')
                  ->orderBy('viaje_comienzo','desc');
        $schedules->with(['user']);
        $schedules->with(['branch']);
        $schedules = $schedules->get();
        return view('intranet.travels.pending',[
            'page' => $page,
            'bcrums' => $bcrums,
            'type'=> $type,
            'schedules' => $schedules
        ]);
    }
}
