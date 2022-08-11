<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = "branches";
        $bcrums = ["Sedes"];
        $branches = Branch::where('estado', 1)
                    ->orderby('nombre','asc')
                    ->get();
        // return $branches->toArray();

        return view('intranet.branches.index',[
            "page"      => $page,
            "bcrums"    => $bcrums,
            "branches"  => $branches,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('intranet.branches.branches_popup');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $branch = new Branch();
        $branch->nombre = $request->nombre;
        $branch->color = $request->color;
        $branch->estado = 1;
        $branch->save();
        return ['status'=>'ok'];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $branch = Branch::find($request->id);
        if($branch){
            return view('intranet.branches.branches_popup',[
                'branch' => $branch
            ]);
        }
        return response()->json(
            [
                'status'=>'error',
                'message'=>'No branch found'
            ], 500
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $branch = Branch::find($request->id);
        if($branch){
            $branch->nombre = $request->nombre;
            $branch->color = $request->color;
            $branch->save();
            return ['status'=>'ok'];
        }
        return ['status'=>'error', 'message'=>'No branch found'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $branch = Branch::find($request->reunion_id);
        if($branch){
            $branch->estado = 0;
            $branch->save();
        }
        return back();
    }
}
