<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Objective;
use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
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
        $theme = Theme::find($request->id);
        return view("intranet.themes.popup_edit",["theme"=>$theme]);
    }

    public function popupUpdate(Request $request){
        $theme = Theme::find($request->theme_upd_id);
        if($theme){
            $theme->nombre = $request->upd_theme_desc;
            $theme->save();

            return back()->with([
                "status" => "ok",
                "msg" => "Tema editado correctamente"
            ]);
        }else{
            return back()->with([
                "status" => "error",
                "msg" => "ERROR: El Tema no existe"
            ]);
        }
    }

    public function popupDelete(Request $request){
        $theme = Theme::find($request->theme_upd_id);
        if($theme){
            $theme->estado = 0;
            $theme->save();

            $objectives = $theme->objectives->where('estado', 1);
            $obj_id = [];
            $act_id = [];
            foreach ($objectives as $k => $obj) {
                $obj_id[] = $obj->id;
                $activities = $obj->activities->where('estado', 1);
                foreach ($activities as $k => $act) {
                    $act_id[] = $act->id;
                }
            }

            Objective::whereIn('id',$obj_id)->update(["estado" => 0]);
            Activity::whereIn('id',$act_id)->update(["estado" => 0]);

            return back()->with([
                "status" => "ok",
                "msg" => "El tema y sus elementos vinculados han sido eliminado correctamente"
            ]);
        }else{
            return back()->with([
                "status" => "error",
                "msg" => "ERROR: El Tema no existe"
            ]);
        }
    }
}
