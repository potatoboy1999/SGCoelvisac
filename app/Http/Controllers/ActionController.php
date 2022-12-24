<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Area;
use App\Models\StratObjective;
use App\Models\User;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    
    public function index(Request $request)
    {
        $page = "objectives";
        $bcrums = ["Agenda Estrategica","Objetivos"];
        
        $objSpec = StratObjective::where('id', $request->specific)->where('estado', 1);
        $objSpec->with(['stratObjective' => function($qStratObj){
            $qStratObj->with(['dimension' => function($qDimension){
                $qDimension->with(['pilar' => function($qPilar){}]);
            }]);
        }]);
        $objSpec = $objSpec->first();
        if($objSpec){
            return view("intranet.actions.index", [
                "page"=>$page,
                "bcrums" => $bcrums,
                "obj" => $objSpec
            ]);
        }
        return back();
    }

    public function getMatrix(Request $request)
    {
        $data = [];
        $obj = StratObjective::where('id', $request->strat_id)->where('estado', 1);
        $obj->with(['area' => function($qArea){}]);
        $obj = $obj->first();

        if($obj){
            $data = ["status"=>"ok","obj" => $obj];
        }else{
            $data = ["status"=>"error","msg"=>"strat not found"];
        }

        return view("intranet.actions.matrix.actions", $data);
    }

    public function create(Request $request)
    {
        $areas = Area::where('estado', 1)
                ->where('vis_matriz', 1)
                ->orderBy('nombre','asc')
                ->get();
        return view('intranet.actions.forms.new',[
            'areas' => $areas,
            'obj' => $request->obj_id
        ]);
    }

    public function edit(Request $request)
    {
        $action = Action::find($request->id);
        $areas = Area::where('estado', 1)
                ->where('vis_matriz', 1)
                ->orderBy('nombre','asc')
                ->get();
        return view('intranet.actions.forms.edit',[
            'areas' => $areas,
            'action' => $action
        ]);
    }

    public function store(Request $request)
    {
        $obj = StratObjective::find($request->obj_id);
        $area = Area::find($request->area_id);
        if($obj){
            $action = new Action();
            $action->objetivo_id = $obj->id;
            $action->area_id = $area->id;
            $action->hito = $request->hito;
            $action->nombre = $request->name;
            $action->inicio = date_format(date_create_from_format('d/m/Y',$request->start_date),'Y-m-d');
            $action->fin = date_format(date_create_from_format('d/m/Y',$request->end_date),'Y-m-d');
            $action->estado = $request->status;
            $action->save();

            return ["status"=>"ok","action"=>$action->id];
        }
        return ["status"=>"error","msg"=>"datos no permitidos"];
    }

    public function update(Request $request)
    {
        $action = Action::find($request->id);
        if($action){
            $action->hito = $request->hito;
            $action->nombre = $request->name;
            $action->inicio = date_format(date_create_from_format('d/m/Y',$request->start_date),'Y-m-d');
            $action->fin = date_format(date_create_from_format('d/m/Y',$request->end_date),'Y-m-d');
            $action->save();
            return ["status"=>"ok","action"=>$action->id];
        }
        return ["status"=>"error","msg"=>"Acción no encontrada"];
    }

    public function terminateAction(Request $request)
    {
        $action = Action::find($request->id);
        if($action){
            $action->fecha_final = date('Y-m-d');
            $action->estado = 3;
            $action->save();
            return ["status"=>"ok","action"=>$action->id];
        }
        return ["status"=>"error","msg"=>"Acción no encontrada"];
    }

    public function delete(Request $request)
    {
        $action = Action::find($request->id);
        if($action){
            $action->estado = 0;
            $action->save();
            return ["status"=>"ok","action"=>$action->id];
        }
        return ["status"=>"error","msg"=>"Acción no encontrada"];
    }
}
