<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Area;
use App\Models\Document;
use App\Models\Objective;
use App\Models\Role;
use App\Models\Theme;
use Illuminate\Http\Request;

class ObjectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
                $roles = Role::where('area_id', $area->id)
                            ->where("estado", 1)
                            ->get();
            }
            
        }
        
        return view("intranet.objectives.index",[
            "page"=>$page,
            "bcrums" => $bcrums,
            "roles" => $roles,
            "all_areas" => $all_areas,
            "area" => $area
        ]);
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
        $valMimes = ["application/pdf"];
        $destinationPath = 'uploads';

        $pol_file = null;
        if($request->hasFile("policy_file") && $request->file("policy_file")->isValid()){
            $polFile = $request->policy_file;

            $ogName = $polFile->getClientOriginalName();
            $ogExtension = $polFile->getClientOriginalExtension();
            $size = $polFile->getSize();
            $mime = $polFile->getMimeType();
            if($size <= $sizeMax && in_array($mime, $valMimes)){
                //Move Uploaded File
                $newName = "file".date("Ymd-His-U")."01.".$ogExtension;
                $polFile->move($destinationPath, $newName);
                
                $polDoc = new Document();
                $polDoc->nombre = substr($ogName, 0, 150);
                $polDoc->file = $newName;
                $polDoc->estado = 1;
                $polDoc->save();

                $pol_file = $polDoc->id;
            }else{
                $alerts[] = "<br>Problemas con el archivo de politicas";
            }


        }

        $adj_file = null;
        if($request->hasFile("adjacent_file") && $request->file("adjacent_file")->isValid()){
            $adjFile = $request->adjacent_file;
            
            $ogName = $adjFile->getClientOriginalName();
            $ogExtension = $adjFile->getClientOriginalExtension();
            $size = $adjFile->getSize();
            $mime = $adjFile->getMimeType();

            if($size <= $sizeMax && in_array($mime, $valMimes)){
                //Move Uploaded File
                $newName = "file".date("Ymd-His-U")."02.".$ogExtension;
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
        $activity->doc_politicas_id = $pol_file;
        $activity->doc_adjunto_id   = $adj_file;
        $activity->estado           = 1;
        $activity->save();

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
                            ->where("estado",1)
                            ->get();
                foreach ($roles as $i => $role) {
                    foreach ($role->themes->where("estado",1) as $x => $theme) {
                        foreach ($theme->objectives->where("estado",1) as $y => $objective) {
                            $objective->activities->where("estado",1);
                        }
                    }
                }
            }
        }
        return $roles;
    }
}
