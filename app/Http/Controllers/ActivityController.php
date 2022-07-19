<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityDocuments;
use App\Models\Area;
use App\Models\Document;
use App\Models\Role;
use Illuminate\Http\Request;

class ActivityController extends Controller
{

    public function popupEdit(Request $request){
        $activity = Activity::find($request->id);
        return view("intranet.activities.popup_edit",["activity"=>$activity]);
    }

    public function popupUpdate(Request $request){
        $activity = Activity::find($request->act_upd_id);
        if($activity){
            $activity->nombre = $request->upd_activity_desc;

            $activity->fecha_comienzo = date_format(date_create_from_format('d/m/Y',$request->act_upd_date_start),'Y-m-d');
            $activity->fecha_fin = date_format(date_create_from_format('d/m/Y',$request->act_upd_date_end),'Y-m-d');

            $activity->cumplido = isset($request->act_done)?1:0;
            $activity->save();

            return back()->with([
                "status" => "ok",
                "msg" => "Actividad editada correctamente"
            ]);
        }else{
            return back()->with([
                "status" => "error",
                "msg" => "ERROR: La actividad no existe"
            ]);
        }
    }

    public function popupDelete(Request $request){

        $activity = Activity::find($request->id);
        if($activity){
            $activity->estado = 0;
            $activity->save();
            return [
                'status'=>'ok',
                'msg'=>'actividad eliminada correctamente'
            ];
        }
        return [
            'status'=>'error',
            'msg'=>'actividad no encontrada'
        ];
    }

    public function updatePolicy(Request $request){
        $pol_file = null;

        $sizeMax = 8388608; // 8MB
        $valMimes = ["application/pdf"]; // pdf
        $destinationPath = 'uploads';

        if($request->hasFile("p_file") && $request->file("p_file")->isValid()){
            $polFile = $request->p_file;
            
            $ogName = $polFile->getClientOriginalName();
            $ogName = substr($ogName, 0, 150);
            $ogExtension = $polFile->getClientOriginalExtension();
            $size = $polFile->getSize();
            $mime = $polFile->getMimeType();

            if($size <= $sizeMax){
                if(in_array($mime, $valMimes)){
                    //Move Uploaded File
                    $newName = "policy".date("Ymd-His-U").".".$ogExtension;
                    $polFile->move($destinationPath, $newName);
    
                    $polDoc = new Document();
                    $polDoc->nombre = $ogName;
                    $polDoc->file = $newName;
                    $polDoc->estado = 1;
                    $polDoc->save();
    
                    $pol_file = $polDoc->id;
                }else{
                    return [
                        "status" => "error",
                        "msg" => "Error: Tipo de archivo no aceptado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "msg" => "Error: Tamaño de archivo muy grande"
                ];
            }
        }else{
            return [
                "status" => "error",
                "msg" => "No ha elegido un archivo o hubo problemas al subirlo"
            ];
        }

        $activity = Activity::find($request->p_act_id);
        if($activity && $request->p_edit == "true"){
            $activity->doc_politicas_id = $pol_file;
            $activity->save();
    
            return [
                "status" => "ok",
                "msg" => "Archivo correctamente guardado",
                "doc_id" => $pol_file,
                "doc_name" => $ogName
            ];
        }
        
        return [
            "status" => "error",
            "msg" => "Error: Actividad no encontrada"
        ];
    }

    public function updateAdjacent(Request $request){
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
                    $newName = "adjacent".date("Ymd-His-U").".".$ogExtension;
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

        $activity = Activity::where('id',$request->a_act_id)
                            ->where('estado',1)
                            ->first();
        if($activity && $request->a_edit == "true"){
            if($adj_file){
                $actDoc = new ActivityDocuments;
                $actDoc->actividad_id = $activity->id;
                $actDoc->documento_id = $adj_file;
                $actDoc->estado = 1;
                $actDoc->save();
    
                $activity->save();
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

    // ------------- FRONT FUNCTIONS --------------//

    public function showMenu(Request $request){
        $page = 'matrix';
        $m_areas = Area::where('vis_matriz', 1)
                    ->where('estado',1)
                    ->get();

        return view('front.menu',[
            "page" => $page,
            "m_areas" => $m_areas,
        ]);
    }

    public function showMatrix(Request $request){
        $page = 'matrix';
        $m_areas = Area::where('vis_matriz', 1)
                    ->where('estado',1)
                    ->get();
        $area = null;
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
                    ->select('t_sgcv_roles.id','t_sgcv_roles.area_id','t_sgcv_roles.nombre','t_sgcv_roles.descripcion','t_sgcv_roles.estado')
                    ->groupBy('t_sgcv_roles.id','t_sgcv_roles.area_id','t_sgcv_roles.nombre','t_sgcv_roles.descripcion','t_sgcv_roles.estado')
                    ->orderBy("t_sgcv_roles.created_at", "desc");
                $roles = $roles->get();
            }
        }

        return view('front.matrix.index',[
            "page" => $page,
            "m_areas" => $m_areas,
            "roles" => $roles,
            "area" => $area,
            "filter" => [
                "active" => (isset($request->search) && $request->search == "Y")?true:false,
                "status" => [
                    "red" => isset($request->s_red), // = 0
                    "yellow" => isset($request->s_yellow), // = 1
                    "green" => isset($request->s_green), // = 2
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

    public function popupAdjacentDocs(Request $request){
        $activity = Activity::where('id',$request->id)
                            ->where('estado',1)
                            ->first();
        if($activity){
            return view('intranet.activities.popup_adjacents',[
                "activity" => $activity,
            ]);
        }
        return view('intranet.errors.popup_error');
    }

    public function popupFrontAdjacentDocs(Request $request){
        $activity = Activity::where('id',$request->id)
                            ->where('estado',1)
                            ->first();
        if($activity){
            return view('front.matrix.popup_adjacents',[
                "activity" => $activity,
            ]);
        }
        return view('intranet.errors.popup_error');
    }
}
