<?php

namespace App\Http\Controllers;

use App\Models\Highlight;
use App\Models\KpiDates;
use Illuminate\Http\Request;

class HighlightController extends Controller
{
    public function getMatrix(Request $request)
    {
        $kpi_date = KpiDates::where('id',$request->kpi_date);
        $kpi_date->with(['highlights' => function($qHigh){
            $qHigh->where('status', 1);
        }]);
        $kpi_date = $kpi_date->first();

        return view('intranet.highlights.matrix.highlights',[
            "kpi_date" => $kpi_date
        ]);
    }

    public function store(Request $request)
    {
        $high = new Highlight();
        $high->kpi_date = $request->kpidate;
        $high->descripcion = $request->descr;
        $high->status = 1;
        $high->save();
        return ["status"=>"ok"];
    }

    public function delete(Request $request)
    {
        $high = Highlight::find($request->id);
        if($high){
            $high->status = 0;
            $high->save();
        }
        return ["status"=>"ok"];
    }

    public function frontMatrix(Request $request)
    {
        $kpi_date = KpiDates::where('id',$request->kpi_date);
        $kpi_date->with(['highlights' => function($qHigh){
            $qHigh->where('status', 1);
        }]);
        $kpi_date = $kpi_date->first();

        return view('front.highlights.matrix.highlights',[
            "kpi_date" => $kpi_date
        ]);
    }
}
