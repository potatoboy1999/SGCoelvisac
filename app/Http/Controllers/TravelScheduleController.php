<?php

namespace App\Http\Controllers;

use App\Mail\TravelAlert;
use App\Mail\TravelValidationAlert;
use App\Models\Area;
use App\Models\Branch;
use App\Models\Document;
use App\Models\ReportActivity;
use App\Models\ReportFile;
use App\Models\TravelActivity;
use App\Models\TravelSchedule;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
        $endYear = $year;
        if($endMonth > 12){
            $endMonth = 1;
            $endYear = $year+1;
        }

        $schedules = TravelSchedule::where('estado','>',0)
                    ->where('viaje_comienzo','>=',$year.'-'.$month.'-01')
                    ->where('viaje_comienzo','<',($endYear).'-'.$endMonth.'-01')
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
            's_date'    => $start_date,
            'source'    => isset($request->source)?$request->source:'back',
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

        $user = Auth::user();
        $area = $user->position->area_id;

        // find manager
        $manager = User::join('t_sgcv_posiciones','t_sgcv_usuarios.posicion_id','t_sgcv_posiciones.id')
                ->where('t_sgcv_posiciones.es_gerente', 1)
                ->where('t_sgcv_posiciones.area_id',$area)
                ->first();
        try{
            if($manager){
                // send mail link to "gerente del area"
                Mail::to($manager->email)//'alejandrodazaculqui@hotmail.com'
                    ->send(new TravelAlert($user->nombre, route('agenda.pending'), $schedule));
            }
            return [
                'status' => 'ok',
                'mail' => 'sent'
            ];
        }catch(Exception $e){
            return [
                'status' => 'ok',
                'mail' => 'not sent',
                'manager' => $manager
            ];
        }

        return [
            'status' => 'ok',
            'mail' => 'not sent',
            'manager' => $manager
        ];

    }

    public function viewPending(Request $request){
        $page = "objectives";
        $bcrums = ["Agendas"];
        $type = isset($request->type)?$request->type:1;
        $user = Auth::user();
        $schedules = TravelSchedule::where('t_sgcv_agenda_viajes.estado','>',0);
                                //->where('estado','<',5)
        $u_area = $user->position->area->id;
        // if is from DEV ADMIN, 
        if($u_area == 1){

            if($type == 1){
                $schedules->where('t_sgcv_agenda_viajes.validacion_uno', 0); // not set
                $schedules->where('t_sgcv_agenda_viajes.validacion_dos', 0); // not set
            }else if ($type == 2) {
                $schedules->where('t_sgcv_agenda_viajes.validacion_uno', 2); // aprobado
                $schedules->where('t_sgcv_agenda_viajes.validacion_dos', 0); // not set
            }

            $schedules->orderBy('t_sgcv_agenda_viajes.created_at','desc')
                  ->orderBy('t_sgcv_agenda_viajes.viaje_comienzo','desc');
            $schedules->with(['user']);
            $schedules->with(['branch']);
            $schedules = $schedules->get();

        }else{
            
            if($u_area == 11){ // area 'gestion', check schedules with 1st validation approved, ALL AREAS
                $type = 2;
                $schedules->where('t_sgcv_agenda_viajes.validacion_uno', 2);
                $schedules->where('t_sgcv_agenda_viajes.validacion_dos', 0);
            }else if($user->position->es_gerente == 1){ // if not from area 'gestion', check if user is manager
                // get schedules with no validation approved from an specific area
                $type = 1;
                $schedules->where('t_sgcv_agenda_viajes.validacion_uno', 0);
                $schedules->where('t_sgcv_agenda_viajes.validacion_dos', 0);
                $schedules->join('t_sgcv_usuarios','t_sgcv_agenda_viajes.usuario_id','t_sgcv_usuarios.id')
                          ->join('t_sgcv_posiciones','t_sgcv_usuarios.posicion_id','t_sgcv_posiciones.id')
                          ->join('t_sgcv_areas','t_sgcv_posiciones.area_id','t_sgcv_areas.id')
                          ->where('t_sgcv_areas.id',$u_area);
                $schedules->select('t_sgcv_agenda_viajes.*');
            }else{
                // user not valid, go back;
                return back();
            }
            $schedules->orderBy('t_sgcv_agenda_viajes.created_at','desc')
                      ->orderBy('t_sgcv_agenda_viajes.viaje_comienzo','desc');
            $schedules->with(['user']);
            $schedules->with(['branch']);
            $schedules = $schedules->get();

        }

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
                $schedule->val_uno_por = Auth::user()->id;
                $schedule->estado = 2; // aprovado por el gerente de area
            }else{
                $schedule->validacion_dos = 2; // confirmed
                $schedule->val_dos_por = Auth::user()->id;
                $schedule->estado = 5; // aprovado a area de gestion
            }
            $schedule->save();

            if($request->confirmation == 1){
                // find users from "area de gestion"
                $gestion = User::join('t_sgcv_posiciones','t_sgcv_usuarios.posicion_id','t_sgcv_posiciones.id')
                        ->where('t_sgcv_posiciones.area_id', 11) // Area de gestion
                        ->get();

                $emails = [];
                //$emails[] = 'alejandrodazaculqui@hotmail.com';

                foreach ($gestion as $k => $g) {
                    $emails[] = $g->email;
                }

                if(sizeof($emails) > 0){
                    $user = $schedule->user;
                    $area = Area::find($user->position->area_id);
                    if($area){
                        // send mail link to "area de gestion" users
                        try{
                            Mail::to($emails)
                                ->send(new TravelValidationAlert($area->nombre, route('agenda.pending'), $schedule));//'https://workat.fulltimeforce.com'));
                        }catch(Exception $e){
        
                        }
                    }
                }
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
                $schedule->val_uno_por = Auth::user()->id;
                $schedule->estado = 3; // rechazado por el gerente de area
            }else{
                $schedule->validacion_dos = 1; // denied
                $schedule->val_dos_por = Auth::user()->id;
                $schedule->estado = 6; // rechazado por el gerente de area
            }
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

    public function viewReports(Request $request)
    {
        $page = "objectives";
        $bcrums = ["Agendas"];

        $user = Auth::user();
        $position = $user->position;
        $area = $position->area;

        // $schedules = TravelSchedule::where('estado', 5)
        //                         ->where('validacion_uno', 2)
        //                         ->where('validacion_dos', 2);
        $schedules = TravelSchedule::whereNotIn('estado', [0,3,6]);
        if($area->id != 1){
            $schedules->where('usuario_id', $user->id);
        }
        $schedules->with(['user'])
                  ->with(['branch'])
                  ->with(['reportActivities']);
        $schedules = $schedules->orderBy('created_at','desc')
                               ->orderBy('viaje_comienzo','desc')
                               ->get();

        return view('intranet.reports.index',[
            'page' => $page,
            'bcrums' => $bcrums,
            'schedules' => $schedules
        ]);
    }

    public function deleteReport(Request $request)
    {
        $schedule = TravelSchedule::where('id', $request->id)->first();
        if($schedule){
            $schedule->estado = 0;
            $schedule->save();

            TravelActivity::where('agenda_viaje_id', $request->id)->update(['estado' => 0]);
            ReportActivity::where('agenda_viaje_id', $request->id)->update(['estado' => 0]);

            return back();
        }

        return back();
    }

    public function showReport(Request $request)
    {
        $page = "objectives";
        $bcrums = ["Agendas"];
        $user = Auth::user();
        $schedule = TravelSchedule::where('id', $request->id);
        if($user->position->area->id != 1){ // IF USER ISN'T ADMIN
            $schedule->where('usuario_id', $user->id);
        }
        $schedule->with(['reportActivities'=>function($q){
            $q->where('estado','>','0');
        }]);
        $schedule = $schedule->first();

        if(!$schedule){
            // schedule not found or doesn't belong to current user
            return back();
        }
        
        return view('intranet.reports.details',[
            'page' => $page,
            'bcrums' => $bcrums,
            "schedule" => $schedule,
        ]);
    }

    public function showReportActivity(Request $request)
    {
        $schedule = TravelSchedule::find($request->schedule_id);
        $repActivity = null;
        if($schedule){
            if(isset($request->report_id)){
                $repActivity = ReportActivity::where('id', $request->report_id)
                                            ->where('estado', '>','0')
                                            ->first();
            }
        }
        return view('intranet.reports.modal_activity',[
            'schedule' => $schedule,
            'rep_activity' => $repActivity,
            'type' => $request->type
        ]);
    }

    public function saveActivity(Request $request)
    {
        $schedule = TravelSchedule::find($request->schedule_id);
        $action = "new";
        if($schedule){
            $report = null;
            if(isset($request->report_id) && !empty($request->report_id)){
                $report = ReportActivity::find($request->report_id);
                if(!$schedule){
                    return [
                        'status' => 'error',
                        'msg' => 'No se encontro la actividad'
                    ];
                }
                $action = "edit";
            }else{
                $report = new ReportActivity;
            }
            $report->descripcion        = $request->descripcion;
            $report->tipo               = $request->tipo;
            $report->acuerdo            = $request->acuerdo;
            $report->fecha_comienzo     = date_format(date_create_from_format('d/m/Y',$request->date_start),'Y-m-d');
            $report->fecha_fin          = date_format(date_create_from_format('d/m/Y',$request->date_end),'Y-m-d');
            $report->agenda_viaje_id    = $schedule->id;
            $report->estado             = $request->estado;
            $report->save();

            // get status color
            $s = ['t_red','t_yellow','t_green'];
            $status = 0; // not done = RED
            if($report->estado == 2){
                $status = 2; // done = GREEN
            }else{
                $today = time();
                $d_start = strtotime($report->fecha_comienzo);
                $d_end = strtotime($report->fecha_fin);
                if($d_start <= $today && $today <= $d_end){
                    // calculate 25% of time remaining
                    $diff = ($d_end - $d_start)*0.25;
                    $d_limit = $d_start + $diff;

                    if($today < $d_limit){
                        $status = 2; // if today is within 25% of start, status OK = GREEN
                    }
                    
                    if($d_limit <= $today){
                        $status = 1; // if today is past 25%, status warning = YELLOW
                    }

                }else if($d_end < $today){
                    $status = 0; // time expired, not done = RED
                }
            }
            $color = $s[$status];

            return [
                'status' => 'ok', 
                'action' => $action,
                'schedule_id' => $schedule->id,
                'report' => [
                    'id'                => $report->id,
                    'descripcion'       => $report->descripcion,
                    'acuerdo'           => $report->acuerdo,
                    'fecha_comienzo'    => date('d/m/Y', strtotime($report->fecha_comienzo)),
                    'fecha_fin'         => date('d/m/Y', strtotime($report->fecha_fin)),
                    'type'              => $report->tipo,
                    'estado'            => $report->estado,
                    'color'             => $color
                ]
            ];
        }

        return [
            'status' => 'error',
            'msg' => 'No se encontro la agenda de viaje'
        ];
    }

    public function deleteActivity(Request $request)
    {
        $report = ReportActivity::find($request->id);
        if($report){
            $report->estado = 0;
            $report->save();
            return ['status' => 'ok'];
        }
        return [
            'status' => 'error',
            'msg' => 'No se encontro la actividad'
        ];
    }

    // FRONT
    public function frontIndex(Request $request)
    {   
        $page = 'schedules';
        $m_areas = Area::where('vis_matriz', 1)
                    ->where('estado',1)
                    ->get();
        $year = intval(isset($request->year)?$request->year:date('Y'));
        $month = intval(isset($request->month)?$request->month:date('m'));
        return view('front.travels.index',[
            "page" => $page,
            "m_areas" => $m_areas,
            "year" => $year,
            "month" => $month,
        ]);
    }

    public function exportReportPdf(Request $request)
    {
        $schedule = TravelSchedule::find($request->id);
        if($schedule){
            $pdf = Pdf::loadView('pdf.activity_report', [
                "schedule" => $schedule
            ]);

            $path = public_path('pdf/');
            $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
            $name = 'report-'.$now->format("Ymd-His-u");
            $fileName =  $name.'.pdf';
            $pdf->save($path . '/' . $fileName);

            $document = new Document();
            $document->nombre = $fileName;
            $document->file = $fileName;
            $document->estado = 1;
            $document->save();

            $reportFile = new ReportFile();
            $reportFile->agenda_viaje_id = $schedule->id;
            $reportFile->documento_id = $document->id;
            $reportFile->estado = 1;
            $reportFile->save();

            return $pdf->download('report'.time().'.pdf');
        }
        return back()->with(['error'=>'No se encontr?? la agenda']);
    }

    public function finalizeReport(Request $request)
    {
        $schedule = TravelSchedule::find($request->id);
        if($schedule){
            $schedule->finalizado = 1;
            $schedule->save();
            return ['status'=>'ok'];
        }
        return back()->with(['error'=>'No se encontr?? la agenda']);
    }
}

