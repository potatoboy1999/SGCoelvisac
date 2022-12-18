<?php

namespace App\Http\Controllers;

use App\Models\Area;
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
        return ["status"=>"ok"];
    }

    public function popUpEdit(Request $request)
    {
        return ["status"=>"ok"];
    }

    public function update(Request $request)
    {
        return ["status"=>"ok"];
    }

    public function delete(Request $request)
    {
        return ["status"=>"ok"];
    }
}
