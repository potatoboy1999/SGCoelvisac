<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Document;
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

    public function getDownload(){
        //PDF file is stored under project/public/download/info.pdf
        //$file= public_path(). "/uploads/file20220612-210506-1655085906.pdf";
        $file= public_path(). "/uploads/Diseño de sistema de seguimiento - Fase I.pptx";
        
        return response()->download($file);
        //return "hi";
    }
}
