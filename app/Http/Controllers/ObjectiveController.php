<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityDocuments;
use App\Models\Area;
use App\Models\AreaRoles;
use App\Models\Dimensions;
use App\Models\Document;
use App\Models\KpiDates;
use App\Models\Kpis;
use App\Models\Objective;
use App\Models\ObjectiveUsers;
use App\Models\Pilars;
use App\Models\Role;
use App\Models\StratObjective;
use App\Models\Theme;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ObjectiveController extends Controller
{
    
    public function index(Request $request)
    {
        $page = "objectives";
        $bcrums = ["Agenda Estrategica","Objetivos"];

        $area = null;
        $all_areas = Area::where("estado", 1)->where("vis_matriz",1)->get();
        $roles = [];

        $pilars = Pilars::where('estado',1)->get();

        // return $roles->toArray();
        
        return view("intranet.objectives.index",[
            "page"=>$page,
            "bcrums" => $bcrums,
            "roles" => $roles,
            "all_areas" => $all_areas,
            "area" => $area,
            "pilars" => $pilars
        ]);
    }

    public function getPilarMatrix(Request $request)
    {
        $data = [];
        $pilar = Pilars::where('id',$request->pilar_id);
        $pilar->with(['dimensions' => function($qDim){
            $qDim->where('estado', 1);
            $qDim->with(['stratObjectives' => function($qStrObj){
                $qStrObj->where('estado', 1);
                $qStrObj->whereNull('obj_estrategico_id');
                $qStrObj->with(['kpis' => function($qKpi){
                    $qKpi->where('estado',1);
                    $qKpi->with(['kpiDates'=>function($qDates){
                        $qDates->where('estado', 1);
                        $qDates->where('anio', date('Y'));
                        $qDates->orderBy('ciclo', 'asc');
                    }]);
                }]);
            }]);
        }]);
        $pilar = $pilar->first();
        if($pilar){
            $data = ["status"=>"ok","pilar" => $pilar];
        }else{
            $data = ["status"=>"error","msg"=>"pilar not found"];
        }
        $data["cicles"] = Kpis::getCicleDef();
        $data["types"] = Kpis::getTypeDef();
        $view = isset($request->view)?$request->view:'general';
        if($view == "general"){
            return view("intranet.objectives.matrix.pilar", $data);
        }else{
            return view("intranet.objectives.matrix.pilarCicles", $data);
        }
    }

    public function specificsIndex(Request $request)
    {
        $page = "objectives";
        $bcrums = ["Agenda Estrategica","Objetivos"];

        $data = [
            "page"=>$page,
            "bcrums" => $bcrums
        ];
        $objStrat = StratObjective::where('id',$request->strat)->where('estado', 1)->first();
        if($objStrat){
            $data['status'] = "ok";
            $data['strat'] = $objStrat;
        }else{
            $data['status'] = "error";
            $data['msg'] = "Objectivo no encontrado";
        }
        return view("intranet.objectives.indexSpecific", $data);
    }

    public function getStrategicSummMatrix(Request $request)
    {
        $data = [];
        $strat = StratObjective::where('id', $request->strat_id)->where('estado', 1);
        $strat->with(['kpis'=>function($qKpis){
            $qKpis->where('estado', 1);
            $qKpis->with(['kpiDates'=>function($qDates){
                $qDates->where('estado', 1);
                $qDates->where('anio', date('Y'));
                $qDates->orderBy('ciclo', 'asc');
            }]);
        }]);
        $strat = $strat->first();
        if($strat){
            $data = ["status"=>"ok","strat" => $strat];
        }else{
            $data = ["status"=>"error","msg"=>"strat not found"];
        }
        $data["cicles"] = Kpis::getCicleDef();
        $data["types"] = Kpis::getTypeDef();

        return view("intranet.objectives.matrix.strategic", $data);
    }

    public function getspecificsMatrix(Request $request)
    {
        $data = [];
        $strat = StratObjective::where('id',$request->strat_id)->where('estado', 1)->first();
        if($strat){
            $specifics = StratObjective::where('obj_estrategico_id', $request->strat_id)
                ->where('estado', 1);
            $specifics->with(['kpis' => function($qKpi){
                $qKpi->where('estado',1);
                $qKpi->with(['kpiDates'=>function($qDates){
                    $qDates->where('estado', 1);
                    $qDates->where('anio', date('Y'));
                    $qDates->orderBy('ciclo', 'asc');
                }]);
            }]);    
            $specifics = $specifics->get();
            $data = ["status"=>"ok","strat" => $strat,"specifics"=>$specifics];
        }else{
            $data = ["status"=>"error","msg"=>"strat not found"];
        }
        $data["cicles"] = Kpis::getCicleDef();
        $data["types"] = Kpis::getTypeDef();

        $view = isset($request->view)?$request->view:'general';
        if($view == "general"){
            return view("intranet.objectives.matrix.specific", $data);
        }else{
            return view("intranet.objectives.matrix.specificCicles", $data);
        }

    }

    public function actionsIndex(Request $request)
    {
        $page = "objectives";
        $bcrums = ["Agenda Estrategica","Objetivos"];

        $data = [
            "page"=>$page,
            "bcrums" => $bcrums
        ];
        $objSpec = StratObjective::where('id', $request->specific)->where('estado',1)->first();
        if($objSpec){
            $data['status'] = "ok";
            $data['strat'] = $objSpec;
        }else{
            $data['status'] = "error";
            $data['msg'] = "Objectivo no encontrado";
        }
        return view("intranet.objectives.indexActions", $data);
    }

    public function actionsMatrix(Request $request)
    {
        $data = [];
        $strat = StratObjective::where('id', $request->strat_id)->where('estado', 1)->first();
        if($strat){
            $data = ["status"=>"ok","strat" => $strat];
        }else{
            $data = ["status"=>"error","msg"=>"strat not found"];
        }

        return view("intranet.objectives.matrix.actions", $data);
    }

    public function specificMatrixIndex(Request $request)
    {
        $page = "objectives";
        $bcrums = ["Agenda Estrategica","Objetivos"];

        return view("intranet.objectives.specific",[
            "page"=>$page,
            "bcrums" => $bcrums,
        ]);
    }

    public function getSpecificMatrix(Request $request)
    {
        $specObjec = StratObjective::whereNotNull('obj_estrategico_id')
                    ->where('estado', 1);

        $specObjec->with(['stratObjective']);
        
        $specObjec = $specObjec->get();

        $view = isset($request->view)?$request->view:'general';
        if($view == "general"){
            return view("intranet.objectives.matrix.specific2",[
                "specObjec" => $specObjec,
                "cicles" => Kpis::getCicleDef(),
                "types" => Kpis::getTypeDef(),
            ]);
        }else{
            return view("intranet.objectives.matrix.specificCicles2",[
                "specObjec" => $specObjec,
                "cicles" => Kpis::getCicleDef(),
                "types" => Kpis::getTypeDef(),
            ]);
        }

    }

    // Strategic Forms
    public function getNewForm(Request $request)
    {
        $pilar = Pilars::where('estado',1);
        $pilar->with(['dimensions'=>function($qDim){
            $qDim->where('estado', 1)
                 ->orderBy('nombre', 'asc');
        }]);
        $pilar->select('id','nombre');
        $pilar = $pilar->get();
        $areas_arr = [];
        $areas = Area::where('estado', 1)->where('vis_matriz', 1)->orderBy('nombre','asc')->get();
        foreach ($areas as $k => $area) {
            $users = User::join('t_sgcv_posiciones','t_sgcv_posiciones.id','t_sgcv_usuarios.posicion_id')
                    ->where('t_sgcv_usuarios.estado', 1)
                    ->where('t_sgcv_posiciones.estado', 1)
                    ->where('t_sgcv_posiciones.area_id', $area->id)
                    ->select('t_sgcv_usuarios.id','t_sgcv_usuarios.nombre')
                    ->orderBy('t_sgcv_usuarios.nombre','asc')
                    ->get();

            $roles = AreaRoles::where('area_id', $area->id)->where('estado', 1)->get();

            $areas_arr[] = [
                "id" => $area->id,
                "name" => $area->nombre,
                "users" => $users,
                "roles" => $roles
            ];
        }
        return view('intranet.objectives.forms.newObjective', [
            "pilars" => $pilar,
            "areas" => $areas_arr
        ]);
    }

    public function getEditForm(Request $request)
    {
        $obj = StratObjective::find($request->id);

        $areas_arr = [];
        $areas = Area::where('estado', 1)->where('vis_matriz', 1)->orderBy('nombre','asc')->get();
        foreach ($areas as $k => $area) {
            $users = User::join('t_sgcv_posiciones','t_sgcv_posiciones.id','t_sgcv_usuarios.posicion_id')
                    ->where('t_sgcv_usuarios.estado', 1)
                    ->where('t_sgcv_posiciones.estado', 1)
                    ->where('t_sgcv_posiciones.area_id', $area->id)
                    ->select('t_sgcv_usuarios.id','t_sgcv_usuarios.nombre')
                    ->orderBy('t_sgcv_usuarios.nombre','asc')
                    ->get();

            $roles = AreaRoles::where('area_id', $area->id)->where('estado', 1)->get();

            $areas_arr[] = [
                "id" => $area->id,
                "name" => $area->nombre,
                "users" => $users,
                "roles" => $roles
            ];
        }
        return view('intranet.objectives.forms.editObjective', [
            "obj" => $obj,
            "areas" => $areas_arr
        ]);
    }

    // Specific Forms
    public function getNewSpecForm(Request $request)
    {
        $areas_arr = [];
        $areas = Area::where('estado', 1)->where('vis_matriz', 1)->orderBy('nombre','asc')->get();
        foreach ($areas as $k => $area) {
            $users = User::join('t_sgcv_posiciones','t_sgcv_posiciones.id','t_sgcv_usuarios.posicion_id')
                    ->where('t_sgcv_usuarios.estado', 1)
                    ->where('t_sgcv_posiciones.estado', 1)
                    ->where('t_sgcv_posiciones.area_id', $area->id)
                    ->select('t_sgcv_usuarios.id','t_sgcv_usuarios.nombre')
                    ->orderBy('t_sgcv_usuarios.nombre','asc')
                    ->get();

            $roles = AreaRoles::where('area_id', $area->id)->where('estado', 1)->get();

            $areas_arr[] = [
                "id" => $area->id,
                "name" => $area->nombre,
                "users" => $users,
                "roles" => $roles
            ];
        }
        return view('intranet.objectives.forms.newSpecific', [
            "obj_strat" => $request->obj_strat,
            "areas" => $areas_arr
        ]);
    }
    public function getEditSpecForm(Request $request)
    {
        $specific = StratObjective::find($request->id);

        $areas_arr = [];
        $areas = Area::where('estado', 1)->where('vis_matriz', 1)->orderBy('nombre','asc')->get();
        foreach ($areas as $k => $area) {
            $users = User::join('t_sgcv_posiciones','t_sgcv_posiciones.id','t_sgcv_usuarios.posicion_id')
                    ->where('t_sgcv_usuarios.estado', 1)
                    ->where('t_sgcv_posiciones.estado', 1)
                    ->where('t_sgcv_posiciones.area_id', $area->id)
                    ->select('t_sgcv_usuarios.id','t_sgcv_usuarios.nombre')
                    ->orderBy('t_sgcv_usuarios.nombre','asc')
                    ->get();

            $roles = AreaRoles::where('area_id', $area->id)->where('estado', 1)->get();

            $areas_arr[] = [
                "id" => $area->id,
                "name" => $area->nombre,
                "users" => $users,
                "roles" => $roles
            ];
        }
        return view('intranet.objectives.forms.editSpecific', [
            "obj" => $specific,
            "areas" => $areas_arr
        ]);
    }

    // Strat CRUD
    public function storeStrat(Request $request)
    {
        
        $dimension = Dimensions::where('id', $request->dimension_id)->first();
        $area = Area::where('id',$request->area_id)->where('estado', 1)->first();
        $rol = AreaRoles::where('area_id', $request->area_id)->where('id', $request->rol_id)->where('estado', 1)->first();
        if($dimension && $area && $rol){
            // crear codigo
            $code = "";
            $pilar = $dimension->pilar;
            $pilar_words = explode(" ",$pilar->nombre);
            foreach($pilar_words as $word){
                $code .= ucfirst($word[0]);
            }
            $code .= ".";
            $dim_words = explode(" ",$dimension->nombre);
            foreach($dim_words as $word){
                $code .= ucfirst($word[0]);
            }

            // store new objective
            $obj = new StratObjective();
            $obj->codigo = "";
            $obj->dimension_id = $dimension->id;
            $obj->rol_id = $rol->id;
            $obj->area_id = $area->id;
            $obj->nombre = $request->nombre;
            $obj->estado = 1;
            $obj->save();

            // add code
            $obj->codigo = $code.$obj->id;
            $obj->save();

            // add users to objective
            if(isset($request->users)){
                foreach($request->users as $k => $user_id){
                    $user = new ObjectiveUsers();
                    $user->objetivo_id = $obj->id;
                    $user->usuario_id = $user_id;
                    $user->estado = 1;
                    $user->save();
                }
            }

            // add default KPI
            /*
            $kpi = new Kpis();
            $kpi->objetivo_id = $obj->id;
            $kpi->nombre = "";
            $kpi->descripcion = "";
            $kpi->formula = "";
            $kpi->frecuencia = "men";
            $kpi->tipo = "per";
            $kpi->meta = "";
            $kpi->estado = 1;
            $kpi->save();

            for ($i=0; $i < 12; $i++) { 
                $real = 0;
                $plan = 0;
                $kpiDate = new KpiDates();
                $kpiDate->kpi_id = $kpi->id;
                $kpiDate->anio = intval(date('Y'));
                $kpiDate->ciclo = ($i+1);
                $kpiDate->real_cantidad = $real;
                $kpiDate->meta_cantidad = $plan;
                $kpiDate->estado = 1;
                $kpiDate->save();
            }
    
            for ($i=0; $i < 12; $i++) {
                $plan = 0;
                $kpiDate = new KpiDates();
                $kpiDate->kpi_id = $kpi->id;
                $kpiDate->anio = intval(date('Y',strtotime('+1 year')));
                $kpiDate->ciclo = ($i+1);
                $kpiDate->real_cantidad = 0;
                $kpiDate->meta_cantidad = $plan;
                $kpiDate->estado = 1;
                $kpiDate->save();
            }
            */

            return ["status"=>"ok","obj"=>$obj->id];
        }
        return ["status"=>"error","msg"=>"datos no permitidos"];
    }

    public function updateStrat(Request $request)
    {
        $area = Area::where('id',$request->area_id)->where('estado', 1)->first();
        $rol = AreaRoles::where('area_id', $request->area_id)->where('id', $request->rol_id)->where('estado', 1)->first();
        $obj = StratObjective::find($request->id);

        if(!$obj){
            return ["status"=>"error","msg"=>"Objetivo no encontrado"];
        }

        if($area && $rol){
            $obj->area_id = $area->id;
            $obj->nombre = $request->nombre;
            $obj->save();

            // change users to objective
            ObjectiveUsers::where('objetivo_id', $obj->id)->delete();
            if(isset($request->users)){
                foreach($request->users as $k => $user_id){
                    $user = new ObjectiveUsers();
                    $user->objetivo_id = $obj->id;
                    $user->usuario_id = $user_id;
                    $user->estado = 1;
                    $user->save();
                }
            }

            return ["status"=>"ok","obj"=>$obj->id];
        }
        return ["status"=>"error","msg"=>"Datos no permitidos"];
    }

    // Specific CRUD
    public function storeSpecific(Request $request)
    {
        $objStrat = StratObjective::where('id', $request->strat_id)->where('estado', 1)->first();
        $area = Area::where('id',$request->area_id)->where('estado', 1)->first();

        if($objStrat && $area){
            // crear codigo
            $code = $objStrat->codigo;

            // store new objective
            $obj = new StratObjective();
            $obj->codigo = "";
            $obj->obj_estrategico_id = $objStrat->id;
            $obj->nombre = $request->nombre;
            $obj->area_id = $area->id;
            $obj->estado = 1;
            $obj->save();

            // add code
            $obj->codigo = $code.'.'.$obj->id;
            $obj->save();

            // add users to objective
            if(isset($request->users)){
                foreach($request->users as $k => $user_id){
                    $user = new ObjectiveUsers();
                    $user->objetivo_id = $obj->id;
                    $user->usuario_id = $user_id;
                    $user->estado = 1;
                    $user->save();
                }
            }

            return ["status"=>"ok","obj"=>$obj->id];
        }
        return ["status"=>"error","msg"=>"datos no permitidos"];
    }

    public function updateSpecific(Request $request)
    {
        $obj = StratObjective::where('id', $request->id)->where('estado', 1)->first();
        $area = Area::where('id',$request->area_id)->where('estado', 1)->first();
        if($obj && $area){
            // update objective
            $obj->nombre = $request->nombre;
            $obj->area_id = $area->id;
            $obj->save();

            // change users to objective
            ObjectiveUsers::where('objetivo_id', $obj->id)->delete();
            if(isset($request->users)){
                foreach($request->users as $k => $user_id){
                    $user = new ObjectiveUsers();
                    $user->objetivo_id = $obj->id;
                    $user->usuario_id = $user_id;
                    $user->estado = 1;
                    $user->save();
                }
            }

            return ["status"=>"ok","obj"=>$obj->id];
        }
        return ["status"=>"error","msg"=>"datos no permitidos"];
    }

    // -- OLD MATRIX TABLE
    public function index2(Request $request)
    {
        $page = "objectives";
        $bcrums = ["Agenda Estrategica","Objetivos"];

        $area = null;
        $all_areas = Area::where("estado",1)->where("vis_matriz",1)->get();
        $roles = [];

        if(isset($request->area)){

            $area = Area::where('id',$request->area)
                        ->where('estado',1)
                        ->first();
                        
            if($area){

                $roles = Role::whereNotNull('t_sgcv_roles.id');
                if(isset($request->search) && $request->search == "Y"){
                    if(isset($request->search_role) 
                    || isset($request->search_theme) 
                    || isset($request->search_objective) 
                    || isset($request->search_activity) 
                    || isset($request->search_from) 
                    || isset($request->search_to)
                    ){
                        $roles->join('t_sgcv_temas','t_sgcv_temas.rol_id','t_sgcv_roles.id');
                        $roles->join('t_sgcv_objetivos','t_sgcv_objetivos.tema_id','t_sgcv_temas.id');
                        $roles->join('t_sgcv_actividades','t_sgcv_actividades.objetivo_id','t_sgcv_objetivos.id');
                        
                        $roles->where("t_sgcv_temas.estado", 1);
                        $roles->where("t_sgcv_objetivos.estado", 1);
                        $roles->where("t_sgcv_actividades.estado", 1);

                        $role_names = [];
                        $theme_names = [];
                        $obj_names = [];
                        $act_names = [];
                        if(isset($request->search_role)){
                            $role_names = explode(" ", $request->search_role);
                            $roles->where(function($q) use ($role_names){
                                foreach($role_names as $name){
                                    $q->where('t_sgcv_roles.nombre' , 'like' , '%'.$name.'%');
                                }
                            });
                        }
                        if(isset($request->search_theme)){
                            $theme_names = explode(" ", $request->search_theme);
                            $roles->where(function($q) use ($theme_names){
                                foreach($theme_names as $name){
                                    $q->where('t_sgcv_temas.nombre' , 'like' , '%'.$name.'%');
                                }
                            });
                        }
                        if(isset($request->search_objective)){
                            $obj_names = explode(" ", $request->search_objective);
                            $roles->where(function($q) use ($obj_names){
                                foreach($obj_names as $name){
                                    $q->where('t_sgcv_objetivos.nombre' , 'like' , '%'.$name.'%');
                                }
                            });
                        }
                        if(isset($request->search_activity)){
                            $act_names = explode(" ", $request->search_activity);
                            $roles->where(function($q) use ($act_names){
                                foreach($act_names as $name){
                                    $q->where('t_sgcv_actividades.nombre' , 'like' , '%'.$name.'%');
                                }
                            });
                        }
                        if(isset($request->search_from)){
                            $roles->where('t_sgcv_actividades.fecha_comienzo','>=',''.date_format(date_create_from_format('d/m/Y',$request->search_from),'Y-m-d').'');
                            
                        }
                        if(isset($request->search_to)){
                            $roles->where('t_sgcv_actividades.fecha_fin','<=',''.date_format(date_create_from_format('d/m/Y',$request->search_to),'Y-m-d').'');
                        }

                        // eager loading: THEMES
                        $roles->with(['themes'=>function($qTheme) use ($request,$theme_names,$obj_names,$act_names){
                            $qTheme->where('estado', 1);
                            $qTheme->where(function($q) use ($theme_names){
                                foreach($theme_names as $name){
                                    $q->where('nombre' , 'like' , '%'.$name.'%');
                                }
                            });
                            // eager loading: OBJETIVES
                            $qTheme->with(['objectives'=>function($qObj) use ($request, $obj_names,$act_names){
                                $qObj->where('estado', 1);
                                $qObj->where(function($q) use ($obj_names){
                                    foreach($obj_names as $name){
                                        $q->where('nombre' , 'like' , '%'.$name.'%');
                                    }
                                });
                                // eager loading: ACTIVITIES
                                $qObj->with(['activities'=>function($qAct) use ($request, $act_names){
                                    $qAct->where('estado',1);
                                    $qAct->where(function($q) use ($act_names){
                                        foreach($act_names as $name){
                                            $q->where('nombre' , 'like' , '%'.$name.'%');
                                        }
                                    });
                                    if(isset($request->search_from)){
                                        $qAct->where('fecha_comienzo','>=',''.date_format(date_create_from_format('d/m/Y',$request->search_from),'Y-m-d').'');
                                    }
                                    if(isset($request->search_to)){
                                        $qAct->where('fecha_fin','<=',''.date_format(date_create_from_format('d/m/Y',$request->search_to),'Y-m-d').'');
                                    }
                                }]);
                            }]);
                        }]);
                    }
                }
                $roles->where('t_sgcv_roles.area_id', $area->id)
                    ->where("t_sgcv_roles.estado", 1)
                    ->orderBy("t_sgcv_roles.created_at", "desc")
                    ->groupBy('t_sgcv_roles.id','t_sgcv_roles.area_id','t_sgcv_roles.nombre','t_sgcv_roles.descripcion','t_sgcv_roles.estado')
                    ->select('t_sgcv_roles.id','t_sgcv_roles.area_id','t_sgcv_roles.nombre','t_sgcv_roles.descripcion','t_sgcv_roles.estado');
                $roles = $roles->get();
            }
        }

        // return $roles->toArray();
        
        return view("intranet.objectives.index2",[
            "page"=>$page,
            "bcrums" => $bcrums,
            "roles" => $roles,
            "all_areas" => $all_areas,
            "area" => $area,
            "filter" => [
                "active" => (isset($request->search) && $request->search == "Y")?true:false,
                "status" => [
                    "red" => isset($request->s_red), // = 0
                    "yellow" => isset($request->s_yellow), // = 1
                    "green" => isset($request->s_green), // = 2
                    "blue" => isset($request->s_blue), // = 3
                ],
                "role_word" => isset($request->search_role)?$request->search_role:'',
                "theme_word" => isset($request->search_theme)?$request->search_theme:'',
                "obj_word" => isset($request->search_objective)?$request->search_objective:'',
                "act_word" => isset($request->search_activity)?$request->search_activity:'',
                "date_from" => isset($request->search_from)?$request->search_from:'',
                "date_to" => isset($request->search_to)?$request->search_to:'',
            ]
        ]);
    }
    // -- END OLD MATRIX TABLE

    public function viewPDF(Request $request)
    {
        if(isset($request->area)){

            $area = Area::where('id',$request->area)
                        ->where('estado',1)
                        ->first();
                        
            if($area){

                $roles = Role::whereNotNull('t_sgcv_roles.id');
                if(isset($request->search) && $request->search == "Y"){
                    if(isset($request->search_role) 
                    || isset($request->search_theme) 
                    || isset($request->search_objective) 
                    || isset($request->search_activity) 
                    || isset($request->search_from) 
                    || isset($request->search_to)
                    ){
                        $roles->join('t_sgcv_temas','t_sgcv_temas.rol_id','t_sgcv_roles.id');
                        $roles->join('t_sgcv_objetivos','t_sgcv_objetivos.tema_id','t_sgcv_temas.id');
                        $roles->join('t_sgcv_actividades','t_sgcv_actividades.objetivo_id','t_sgcv_objetivos.id');
                        
                        $roles->where("t_sgcv_temas.estado", 1);
                        $roles->where("t_sgcv_objetivos.estado", 1);
                        $roles->where("t_sgcv_actividades.estado", 1);

                        $role_names = [];
                        $theme_names = [];
                        $obj_names = [];
                        $act_names = [];
                        if(isset($request->search_role)){
                            $role_names = explode(" ", $request->search_role);
                            $roles->where(function($q) use ($role_names){
                                foreach($role_names as $name){
                                    $q->where('t_sgcv_roles.nombre' , 'like' , '%'.$name.'%');
                                }
                            });
                        }
                        if(isset($request->search_theme)){
                            $theme_names = explode(" ", $request->search_theme);
                            $roles->where(function($q) use ($theme_names){
                                foreach($theme_names as $name){
                                    $q->where('t_sgcv_temas.nombre' , 'like' , '%'.$name.'%');
                                }
                            });
                        }
                        if(isset($request->search_objective)){
                            $obj_names = explode(" ", $request->search_objective);
                            $roles->where(function($q) use ($obj_names){
                                foreach($obj_names as $name){
                                    $q->where('t_sgcv_objetivos.nombre' , 'like' , '%'.$name.'%');
                                }
                            });
                        }
                        if(isset($request->search_activity)){
                            $act_names = explode(" ", $request->search_activity);
                            $roles->where(function($q) use ($act_names){
                                foreach($act_names as $name){
                                    $q->where('t_sgcv_actividades.nombre' , 'like' , '%'.$name.'%');
                                }
                            });
                        }
                        if(isset($request->search_from)){
                            $roles->where('t_sgcv_actividades.fecha_comienzo','>=',''.date_format(date_create_from_format('d/m/Y',$request->search_from),'Y-m-d').'');
                            
                        }
                        if(isset($request->search_to)){
                            $roles->where('t_sgcv_actividades.fecha_fin','<=',''.date_format(date_create_from_format('d/m/Y',$request->search_to),'Y-m-d').'');
                        }

                        // eager loading: THEMES
                        $roles->with(['themes'=>function($qTheme) use ($request,$theme_names,$obj_names,$act_names){
                            $qTheme->where('estado', 1);
                            $qTheme->where(function($q) use ($theme_names){
                                foreach($theme_names as $name){
                                    $q->where('nombre' , 'like' , '%'.$name.'%');
                                }
                            });
                            // eager loading: OBJETIVES
                            $qTheme->with(['objectives'=>function($qObj) use ($request, $obj_names,$act_names){
                                $qObj->where('estado', 1);
                                $qObj->where(function($q) use ($obj_names){
                                    foreach($obj_names as $name){
                                        $q->where('nombre' , 'like' , '%'.$name.'%');
                                    }
                                });
                                // eager loading: ACTIVITIES
                                $qObj->with(['activities'=>function($qAct) use ($request, $act_names){
                                    $qAct->where('estado',1);
                                    $qAct->where(function($q) use ($act_names){
                                        foreach($act_names as $name){
                                            $q->where('nombre' , 'like' , '%'.$name.'%');
                                        }
                                    });
                                    if(isset($request->search_from)){
                                        $qAct->where('fecha_comienzo','>=',''.date_format(date_create_from_format('d/m/Y',$request->search_from),'Y-m-d').'');
                                    }
                                    if(isset($request->search_to)){
                                        $qAct->where('fecha_fin','<=',''.date_format(date_create_from_format('d/m/Y',$request->search_to),'Y-m-d').'');
                                    }
                                }]);
                            }]);
                        }]);
                    }
                }
                $roles->where('t_sgcv_roles.area_id', $area->id)
                    ->where("t_sgcv_roles.estado", 1)
                    ->orderBy("t_sgcv_roles.created_at", "desc")
                    ->groupBy('t_sgcv_roles.id','t_sgcv_roles.area_id','t_sgcv_roles.nombre','t_sgcv_roles.descripcion','t_sgcv_roles.estado')
                    ->select('t_sgcv_roles.id','t_sgcv_roles.area_id','t_sgcv_roles.nombre','t_sgcv_roles.descripcion','t_sgcv_roles.estado');
                $roles = $roles->get();
            }
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.objectives_report_2', [
            "roles" => $roles,
            "area" => $area,
            "filter" => [
                "active" => (isset($request->search) && $request->search == "Y")?true:false,
                "status" => [
                    "red" => isset($request->s_red), // = 0
                    "yellow" => isset($request->s_yellow), // = 1
                    "green" => isset($request->s_green), // = 2
                    "blue" => isset($request->s_blue), // = 3
                ],
                "role_word" => isset($request->search_role)?$request->search_role:'',
                "theme_word" => isset($request->search_theme)?$request->search_theme:'',
                "obj_word" => isset($request->search_objective)?$request->search_objective:'',
                "act_word" => isset($request->search_activity)?$request->search_activity:'',
                "date_from" => isset($request->search_from)?$request->search_from:'',
                "date_to" => isset($request->search_to)?$request->search_to:'',
            ]
        ]);
        return $pdf->stream();
    }

    public function storeItem(Request $request){

        $alerts = [];
        
        $role_id = 0;
        if(isset($request->new_role_switch)){
            // Add new role
            $role = new Role;
            $role->area_id = $request->area_id;
            $role->nombre = $request->role_name;
            $role->descripcion = "";
            $role->estado = 1;
            $role->save();
            $role_id = $role->id;
        }else{
            $role_id = $request->role_sel;
        }
        
        $theme_id = 0;
        if(isset($request->new_theme_switch)){
            // Add new theme
            $theme = new Theme;
            $theme->rol_id = $role_id;
            $theme->nombre = $request->theme_name;
            $theme->estado = 1;
            $theme->save();
            $theme_id = $theme->id;
        }else{
            $theme_id = $request->theme_sel;
        }

        $objective_id = 0;
        if(isset($request->new_obj_switch)){
            // Add new objective
            $objective = new Objective;
            $objective->nombre = $request->obj_name;
            $objective->tema_id = $theme_id;
            $objective->estado = 1;
            $objective->save();
            $objective_id = $objective->id;
        }else{
            $objective_id = $request->obj_sel;
        }

        $sizeMax = 8388608; // 8MB
        $destinationPath = 'uploads';
        // $valMimes = ["application/pdf"];

        // $pol_file = null;
        // if($request->hasFile("policy_file") && $request->file("policy_file")->isValid()){
        //     $polFile = $request->policy_file;
        //     $ogName = $polFile->getClientOriginalName();
        //     $ogExtension = $polFile->getClientOriginalExtension();
        //     $size = $polFile->getSize();
        //     $mime = $polFile->getMimeType();
        //     if($size <= $sizeMax && in_array($mime, $valMimes)){
        //         //Move Uploaded File
        //         $newName = "file".date("Ymd-His-U")."01.".$ogExtension;
        //         $polFile->move($destinationPath, $newName);
                
        //         $polDoc = new Document();
        //         $polDoc->nombre = substr($ogName, 0, 150);
        //         $polDoc->file = $newName;
        //         $polDoc->estado = 1;
        //         $polDoc->save();

        //         $pol_file = $polDoc->id;
        //     }else{
        //         $alerts[] = "<br>Problemas con el archivo de politicas";
        //     }
        // }

        $valMimes = [
            "image/png", // png
            "image/jpeg", // jpg
            "application/pdf", // pdf
            "application/vnd.ms-powerpoint", // ppt
            "application/vnd.openxmlformats-officedocument.presentationml.presentation", // pptx
            "application/vnd.ms-excel", // xls
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" // xlsx
        ];

        $adj_file = null;
        if($request->hasFile("adjacent_file") && $request->file("adjacent_file")->isValid()){
            $adjFile = $request->adjacent_file;
            
            $ogName = $adjFile->getClientOriginalName();
            $ogExtension = $adjFile->getClientOriginalExtension();
            $size = $adjFile->getSize();
            $mime = $adjFile->getMimeType();

            if($size <= $sizeMax && in_array($mime, $valMimes)){
                //Move Uploaded File
                $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));                
                $newName = "file".$now->format("Ymd-His-u")."02.".$ogExtension;
                $adjFile->move($destinationPath, $newName);

                $adjDoc = new Document();
                $adjDoc->nombre = substr($ogName, 0, 150);
                $adjDoc->file = $newName;
                $adjDoc->estado = 1;
                $adjDoc->save();

                $adj_file = $adjDoc->id;
            }else{
                $alerts[] = "<br>Problemas con el archivo adjunto";
            }
        }        

        $activity = new Activity;
        $activity->nombre           = $request->activity_desc;
        $activity->objetivo_id      = $objective_id;
        $activity->fecha_comienzo   = date_format(date_create_from_format('d/m/Y',$request->act_date_start),'Y-m-d');
        $activity->fecha_fin        = date_format(date_create_from_format('d/m/Y',$request->act_date_end),'Y-m-d');
        $activity->estado           = 1;
        $activity->save();

        if($adj_file){
            $actDoc = new ActivityDocuments;
            $actDoc->actividad_id = $activity->id;
            $actDoc->documento_id = $adj_file;
            $actDoc->estado = 1;
            $actDoc->save();
        }

        return back()->with([
            'item_status' => true, 
            'item_msg' => 'Nuevo item creado'
        ]);
    }

    public function allItems(Request $request){
        $roles = [];
        if(isset($request->area)){
            $area = Area::where('id', $request->area)
                        ->where("vis_matriz",1)
                        ->where('estado', 1)
                        ->first();
            if($area){
                $roles = Role::where('area_id', $area->id)
                            ->where("estado",1);
                $roles->with(['themes'=>function($qTheme){
                    $qTheme->where("estado",1);
                    $qTheme->with(['objectives'=>function($qObj){
                        $qObj->where('estado', 1);
                        $qObj->with(['activities'=>function($qAct){
                            $qAct->where("estado",1);
                        }]);
                    }]);
                }]);

                // foreach ($roles as $i => $role) {
                //     foreach ($role->themes->where("estado",1) as $x => $theme) {
                //         foreach ($theme->objectives->where("estado",1) as $y => $objective) {
                //             $objective->activities->where("estado",1);
                //         }
                //     }
                // }

                $roles = $roles->get();
            }
        }
        return $roles;
    }
}
