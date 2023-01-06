<?php

namespace App\Http\Controllers;

use App\Models\ObjectiveComment;
use App\Models\StratObjective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ObjectiveComments extends Controller
{
    public function list(Request $request)
    {
        $objective = StratObjective::where('id',$request->obj_id);
        $objective->with(['comments' => function($qComment){
            $qComment->where('estado', 1);
        }]);
        $objective = $objective->first();
        // return $objective;
        return view('intranet.obj_comments.popup_comments', [
            "objective" => $objective
        ]);
    }

    public function store(Request $request)
    {
        $objective = StratObjective::find($request->objective_id);
        if($objective){
            $objComment = new ObjectiveComment();
            $objComment->usuario_id = Auth::user()->id;
            $objComment->objetivo_id = $objective->id;
            $objComment->descripcion = $request->description;
            $objComment->estado = 1;
            $objComment->save();
            return ["status"=>"ok"];
        }
        return ["status"=>"error", "msg"=>"No se encontro el comentario"];
    }

    public function delete(Request $request)
    {
        $objComment = ObjectiveComment::find($request->id);
        if($objComment){
            $objComment->estado = 0;
            $objComment->save();
            return ["status"=>"ok"];
        }
        return ["status"=>"error", "msg"=>"No se encontro el comentario"];
    }
}
