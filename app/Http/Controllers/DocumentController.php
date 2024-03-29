<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
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

    public function delete(Request $request){
        $document = Document::find($request->id);
        if($document){
            $document->estado = 0;
            $document->save();
            return [
                "status"=> "ok",
                "msg" => "El documento ha sido eliminado",
                "doc" => $document->id
            ];
        }else{
            return [
                "status" => "error",
                "msg" => "Error: No se encontro el documento"
            ];
        }
    }

    public function download(Request $request){
        $document = Document::find($request->id);
        if($document){
            //file is stored under project/public/uploads/
            $file= public_path(). "/uploads/".$document->file;
            return response()->download($file, $document->nombre);
        }else{
            return back()->with("document wasn't found");
        }
    }
}
