<?php

namespace App\Http\Controllers;

use App\Models\Reunion;
use Illuminate\Http\Request;

class ReunionController extends Controller
{
    public function backIndex(Request $request)
    {
        $page = "objectives";
        $bcrums = ["Reunión"];
        $year = intval(isset($request->year)?$request->year:date('Y'));
        $month = intval(isset($request->month)?$request->month:date('m'));
        $cal_type = isset($request->cal_type)?$request->cal_type : 1;
        // return $branches->toArray();

        return view('intranet.reunions.index',[
            "page"=>$page,
            "bcrums" => $bcrums,
            "year" => $year,
            "month" => $month,
            "cal_type" => $cal_type,
        ]);
    }

    public function viewCalendar(Request $request)
    {
        $year = intval(isset($request->year)?$request->year:date('Y'));
        $month = intval(isset($request->month)?$request->month:date('m'));
        $cal_type = isset($request->cal_type)?$request->cal_type : 1;
        $endMonth = $month + 1;
        $endYear = $year;
        if($endMonth > 12){
            $endMonth = 1;
            $endYear = $year+1;
        }

        $reunions = Reunion::where('estado','>',0);

        if($cal_type == 1){
            $reunions->where('fecha','>=',$year.'-'.$month.'-01')
                    ->where('fecha','<',($endYear).'-'.$endMonth.'-01')
                    ->orderBy('fecha','asc');
        }else{
            $reunions->where('fecha','>=',$year.'-01-01')
                    ->where('fecha','<',($year+1).'-01-01')
                    ->orderBy('fecha','asc');
        }
        $reunions = $reunions->get();

        return view('intranet.reunions.calendar',[
            "year" => $year,
            "month" => $month,
            "reunions" => $reunions
        ]);
    }

    public function createReunion(Request $request)
    {
        $page   = "objectives";
        $bcrums = ["Reunión"];
        $date   = $request->date;
        return view('intranet.reunions.create_modify',[
            "page"=>$page,
            "bcrums" => $bcrums,
            "date" => $date
        ]);
    }

    public function storeReunion(Request $request)
    {
        # code...
    }
}
