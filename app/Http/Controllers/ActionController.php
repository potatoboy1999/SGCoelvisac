<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\ActionDocuments;
use App\Models\Area;
use App\Models\Document;
use App\Models\StratObjective;
use App\Models\User;
use DateTime;
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
        $obj->with(['actions'=> function($qAction){
            $qAction->where('estado','>=',1);
            $qAction->with(['documents'=>function($qDocs){
                $qDocs->where('t_sgcv_documentos.estado', 1);
            }]);
        }]);
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
            $action->estado = $request->status;
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

    public function popupDocs(Request $request){
        $action = Action::where('id',$request->id)->where('estado',1);
        $action->with(['documents' => function($qDoc){
            $qDoc->where('t_sgcv_documentos.estado', 1);
        }]);
        $action = $action->first();
        return view('intranet.actions.forms.docs',[
            "action" => $action,
        ]);
    }

    public function addDocuments(Request $request)
    {
        $adj_file = null;

        $sizeMax = 8388608; // 8MB
        $valMimes = [
            "image/png", // png
            "image/jpeg", // jpg
            "application/pdf", // pdf
            "application/vnd.ms-powerpoint", // ppt
            "application/vnd.openxmlformats-officedocument.presentationml.presentation", // pptx
            "application/vnd.ms-excel", // xls
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" // xlsx
        ];
        $destinationPath = 'uploads';

        if($request->hasFile("a_file") && $request->file("a_file")->isValid()){
            $adjFile = $request->a_file;
            
            $ogName = $adjFile->getClientOriginalName();
            $ogExtension = $adjFile->getClientOriginalExtension();
            $size = $adjFile->getSize();
            $mime = $adjFile->getMimeType();

            if($size <= $sizeMax){
                if(in_array($mime, $valMimes)){
                    //Move Uploaded File
                    $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
                    $newName = "adjacent".$now->format("Ymd-His-u").".".$ogExtension;
                    $adjFile->move($destinationPath, $newName);
    
                    $adjDoc = new Document();
                    $adjDoc->nombre = substr($ogName, 0, 150);
                    $adjDoc->file = $newName;
                    $adjDoc->estado = 1;
                    $adjDoc->save();
    
                    $adj_file = $adjDoc->id;
                }else{
                    return [
                        "status" => "error",
                        "msg" => "Error: Tipo de archivo no aceptado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "msg" => "Error: Archivo muy grande"
                ];
            }
        }

        $action = Action::where('id',$request->action_id)
                            ->where('estado',1)
                            ->first();
        if($action && $request->a_edit == "true"){
            if($adj_file){
                $actionDoc = new ActionDocuments();
                $actionDoc->accion_id = $action->id;
                $actionDoc->documento_id = $adj_file;
                $actionDoc->estado = 1;
                $actionDoc->save();
            }
    
            return [
                "status" => "ok",
                "msg" => "Archivo correctamente guardado"
            ];
        }
        
        return [
            "status" => "error",
            "msg" => "Error: Actividad no encontrada"
        ];
    }

    public function deleteDocuments(Request $request)
    {
        # code...
    }

    // front

    public function frontIndex(Request $request)
    {
        $page = "objectives";

        $data = [
            "page"=>$page,
        ];
        $objSpec = StratObjective::where('id', $request->specific)->where('estado', 1);
        $objSpec->with(['stratObjective' => function($qStratObj){
            $qStratObj->with(['dimension' => function($qDimension){
                $qDimension->with(['pilar' => function($qPilar){}]);
            }]);
        }]);
        $objSpec = $objSpec->first();
        if($objSpec){
            return view("front.actions.index", [
                "page" => $page,
                "obj" => $objSpec
            ]);
        }
        return back();
    }

    public function frontMatrix(Request $request)
    {
        $data = [];
        $obj = StratObjective::where('id', $request->strat_id)->where('estado', 1);
        $obj->with(['area' => function($qArea){}]);
        $obj->with(['actions'=> function($qAction){
            $qAction->where('estado','>=',1);
            $qAction->with(['documents'=>function($qDocs){
                $qDocs->where('t_sgcv_documentos.estado', 1);
            }]);
        }]);
        $obj = $obj->first();

        if($obj){
            $data = ["status"=>"ok","obj" => $obj];
        }else{
            $data = ["status"=>"error","msg"=>"strat not found"];
        }

        return view("front.actions.matrix.actions", $data);
    }

    public function frontPopupDocs(Request $request)
    {
        $action = Action::where('id',$request->id)->where('estado',1);
        $action->with(['documents' => function($qDoc){
            $qDoc->where('t_sgcv_documentos.estado', 1);
        }]);
        $action = $action->first();
        return view('front.actions.forms.docs',[
            "action" => $action,
        ]);
    }
}
