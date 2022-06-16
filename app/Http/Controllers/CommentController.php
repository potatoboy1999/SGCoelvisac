<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
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

    public function popupShow(Request $request){
        if(isset($request->activity)){
            $activity = Activity::where('id',$request->activity)->first();
            if($activity){
                $comments = Comment::where('actividad_id', $activity->id)
                                    ->where('estado',1)
                                    ->orderBy('created_at','desc')
                                    ->get();
                return view('intranet.comments.popup_comments',[
                    "activity"=>$activity,
                    'comments'=>$comments
                ]);
            }
        }
        return "";
    }
    public function popupDelete(Request $request){
        $comment = Comment::where('id',$request->comment)
                            ->first();
        if($comment){
            $comment->estado = 0;
            $comment->save();

            return ['status'=>'ok','msg'=>'Comentario eliminado correctamente'];
        }

        return ['status'=>'error','msg'=>'No se encontro este comentario'];
    }
    public function popupUpdate(Request $request){
        $comments = $request->comm_desc;
        $ids = [];
        if(isset($request->comm_id)){
            $ids = $request->comm_id;
        }

        for ($i=0; $i < sizeof($comments); $i++) { 
            $comm = $comments[$i];
            if($i < sizeof($ids)){
                // update comment
                $t_comm = Comment::find($ids[$i]);
                $t_comm->descripcion = $comm;
                $t_comm->save();
            }else{
                // create comment
                if($comm != null){
                    $t_comm = new Comment;
                    $t_comm->descripcion = $comm;
                    $t_comm->actividad_id = $request->act_id;
                    $t_comm->usuario_id = Auth::user()->id;
                    $t_comm->estado = 1;
                    $t_comm->save();
                }
            }
        }

        return ['status'=>'ok', 'msg'=>'Comentario guardado correctamente'];
    }

}
