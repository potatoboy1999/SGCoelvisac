<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Objective;
use App\Models\Role;
use App\Models\Theme;
use Illuminate\Http\Request;

class RoleController extends Controller
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
        $role = Role::find($request->id);
        return view("intranet.roles.popup_edit",["role"=>$role]);
    }

    public function popupUpdate(Request $request){
        $role = Role::find($request->role_upd_id);
        if($role){
            $role->nombre = $request->upd_role_desc;
            $role->save();

            return back()->with([
                "status" => "ok",
                "msg" => "Rol editado correctamente"
            ]);
        }else{
            return back()->with([
                "status" => "error",
                "msg" => "ERROR: El Rol no existe"
            ]);
        }
    }

    public function popupDelete(Request $request){
        $role = Role::find($request->id);
        if($role){
            $role->estado = 0;
            $role->save();

            $themes = $role->themes->where('estado', 1);

            $themes_id = [];
            $obj_id = [];
            $act_id = [];
            foreach ($themes as $k => $theme) {
                $themes_id[] = $theme->id;
                $objectives = $theme->objectives->where('estado', 1);

                foreach ($objectives as $x => $obj) {
                    $obj_id[] = $obj->id;
                    $activities = $obj->activities->where('estado', 1);

                    foreach ($activities as $y => $act) {
                        $act_id[] = $act->id;
                    }
                }
            }

            Theme::whereIn('id',$themes_id)->update(["estado" => 0]);
            Objective::whereIn('id',$obj_id)->update(["estado" => 0]);
            Activity::whereIn('id',$act_id)->update(["estado" => 0]);

            return [
                "status" => "ok",
                "msg" => "El rol y sus elementos vinculados han sido eliminado correctamente"
            ];
        }else{
            return [
                "status" => "error",
                "msg" => "ERROR: El Rol no existe"
            ];
        }
    }
}
