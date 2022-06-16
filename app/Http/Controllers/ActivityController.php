<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Area;
use App\Models\Document;
use App\Models\Role;
use Illuminate\Http\Request;

class ActivityController extends Controller
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

    public function popupEdit(Request $request){
        $activity = Activity::find($request->id);
        return view("intranet.activities.popup_edit",["activity"=>$activity]);
    }

    public function popupUpdate(Request $request){
        $activity = Activity::find($request->act_upd_id);
        if($activity){
            $activity->nombre = $request->upd_activity_desc;
            $activity->fecha_comienzo = $request->act_upd_date_start;
            $activity->fecha_fin = $request->act_upd_date_end;
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
                        "msg" => "Error: Archivo de tipo de aceptado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "msg" => "Error: Archivo muy grande"
                ];
            }
        }

        $activity = Activity::find($request->a_act_id);
        if($activity && $request->a_edit == "true"){
            $activity->doc_adjunto_id = $adj_file;
            $activity->save();
    
            return [
                "status" => "ok",
                "msg" => "Archivo correctamente guardado",
                "doc_id" => $adj_file,
                "doc_name" => $ogName
            ];
        }
        
        return [
            "status" => "error",
            "msg" => "Error: Actividad no encontrada"
        ];
    }

    // ------------- FRONT FUNCTIONS --------------//

    public function showMenu(Request $request){
        $page = [];
        $m_areas = Area::where('vis_matriz', 1)
                    ->where('estado',1)
                    ->get();

        return view('front.menu',[
            "page" => $page,
            "m_areas" => $m_areas,
        ]);
    }

    public function showMatrix(Request $request){
        $page = [];
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
                $roles = Role::where('area_id', $area->id)
                            ->where("estado", 1)
                            ->get();
            }
        }

        return view('front.matrix.index',[
            "page" => $page,
            "m_areas" => $m_areas,
            "roles" => $roles,
            "area" => $area
        ]);
    }
}
