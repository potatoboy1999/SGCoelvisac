<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\TravelActivity;
use App\Models\TravelSchedule;
use Exception;
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
        $action = $request->action;
        /*
            ACTION
            ==========
            1 = NEW
            2 = SHOW
            3 = CONFIRMATION ONE
            4 = CONFIRMATION TWO
        */
        $schedule = null;
        if($action > 1 && isset($request->id)){
            if($action == 2){
                $schedule = TravelSchedule::where('id', $request->id)
                                    ->where('estado', 5); // aprovado a area de gestion
            }
            if($action == 3){
                $schedule = TravelSchedule::where('id', $request->id)
                                    ->where('estado', 1); // enviado a gerente de area
            }
            if($action == 4){
                $schedule = TravelSchedule::where('id', $request->id)
                                    ->where('estado', 2)// aprovado por el gerente de area
                                    ->where('validacion_uno', 2); // validation 1 accepted
            }
            $schedule->with(['activities']);
            $schedule->with(['user']);
            $schedule = $schedule->first();
        }
        $branches = Branch::where('estado', 1)->get();
        $start_date = $request->start_date;
        if($action > 1 && !$schedule){
            return "ERROR, AGENDA NO ENCONTRADA";
        }
        return view('intranet.travels.modal_schedule',[
            'action'    => $action,
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

        try{
            // send mail link to "gerente del area"
        }catch(Exception $e){
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

    public function confirmSchedule(Request $request)
    {
        $schedule = TravelSchedule::find($request->id);
        if($schedule){
            if($request->confirmation == 1){
                $schedule->validacion_uno = 2; // confirmed
            }else{
                $schedule->validacion_dos = 2; // confirmed
            }
            $schedule->estado = 2; // aprovado por el gerente de area
            $schedule->save();
    
            try{
                // send mail link to "area de gestion"
            }catch(Exception $e){
            }
        }else{
            return [
                'status' => 'error',
                'msg' => 'Agenda de viaje no encontrada'
            ];
        }

        return [
            'status' => 'ok'
        ];
    }
    
    public function denySchedule(Request $request)
    {
        $schedule = TravelSchedule::find($request->id);
        if($schedule){
            if($request->confirmation == 1){
                $schedule->validacion_uno = 1; // denied
            }else{
                $schedule->validacion_dos = 1; // denied
            }
            $schedule->estado = 3; // rechazado por el gerente de area
            $schedule->save();
        }else{
            return [
                'status' => 'error',
                'msg' => 'Agenda de viaje no encontrada'
            ];
        }

        return [
            'status' => 'ok'
        ];
    }
}
