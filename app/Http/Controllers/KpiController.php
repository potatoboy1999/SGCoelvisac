<?php

namespace App\Http\Controllers;

use App\Models\Kpis;
use App\Models\StratObjective;
use Illuminate\Http\Request;

class KpiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $kpi = null;
        $obj = null;
        if(isset($request->id)){
            $kpi = Kpis::where('id',$request->id)->where('estado',1)->first();
        }else{
            $obj = StratObjective::where('id',$request->obj)->where('estado',1)->first();
        }
        return view('intranet.kpis.index',[
            'kpi' => $kpi,
            'obj' => $obj
        ]);
    }

    public function getMatrixNow(Request $request)
    {
        $kpi = Kpis::find($request->id);
        return view('intranet.kpis.matrix.now',[
            "kpi" => $kpi,
            "frequency" => $request->frequency,
            "type" => $request->type
        ]);
    }

    public function getMatrixFuture(Request $request)
    {
        $kpi = Kpis::find($request->id);
        return view('intranet.kpis.matrix.future',[
            "kpi" => $kpi,
            "frequency" => $request->frequency,
            "type" => $request->type
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
}
