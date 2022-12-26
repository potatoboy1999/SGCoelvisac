<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\AreaRoles;
use Illuminate\Http\Request;

class AreaRoleController extends Controller
{
    public function index(Request $request)
    {
        $areas = Area::where("estado", 1)->where("vis_matriz",1);
        $areas->with(['roles' => function($qRole){
            $qRole->where('estado',1);
            $qRole->with(['stratObjectives'=>function($qStrat){
                $qStrat->where('estado', 1);
            }]);
        }]);
        $areas = $areas->get();
        return view("intranet.roles.index",[
            "areas" => $areas,
        ]);
    }

    public function storeItem(Request $request)
    {
        $area = Area::find($request->area_id);
        if($area){
            $role = new AreaRoles();
            $role->area_id = $area->id;
            $role->nombres = $request->name;
            $role->estado = 1;
            $role->save();
            return ["status"=>"ok"];
        }
        return ["status"=>"error","msg"=>"Area no encontrada"];
    }

    public function popUpEdit(Request $request)
    {
        $role = AreaRoles::find($request->id);
        $areas = Area::where("estado", 1)->where("vis_matriz",1)->get();
        return view('intranet.roles.forms.edit',[
            "role"=>$role,
            "areas"=>$areas
        ]);
    }

    public function update(Request $request)
    {
        $role = AreaRoles::find($request->id);
        if($role){
            $role->nombres = $request->name;
            $role->estado = 1;
            $role->save();
            return ["status"=>"ok"];
        }
        return ["status"=>"error", "msg"=>"Parametros no permitidos"];
    }

    public function delete(Request $request)
    {
        $role = AreaRoles::find($request->id);
        if($role){
            $role->estado = 0;
            $role->save();
            return ["status"=>"ok"];
        }
        return ["status"=>"error", "msg"=>"Rol no encontrado"];
    }
}
