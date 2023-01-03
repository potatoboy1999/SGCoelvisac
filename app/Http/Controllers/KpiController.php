<?php

namespace App\Http\Controllers;

use App\Models\KpiDates;
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
            'obj' => $obj,
            'cicles' => json_encode(Kpis::getCicleDef())
        ]);
    }

    public function getMatrixNow(Request $request)
    {
        $kpi = Kpis::where('id', $request->id);
        $kpi->with(['kpiDates' => function($qDates){
            $qDates->where('estado', 1);
            $qDates->where('anio', date('Y'));
            $qDates->orderBy('ciclo', 'asc');
        }]);
        $kpi = $kpi->first();
        return view('intranet.kpis.matrix.now',[
            "kpi" => $kpi,
            "frequency" => $request->frequency,
            "type" => $request->type
        ]);
    }

    public function getMatrixPast(Request $request)
    {
        $kpi = Kpis::where('id',$request->id);
        $kpi->with(['kpiDates' => function($qDates){
            $qDates->where('estado', 1);
            $qDates->where('anio', intval(date('Y',strtotime('-1 year'))));
            $qDates->orderBy('ciclo', 'asc');
        }]);
        $kpi = $kpi->first();
        return view('intranet.kpis.matrix.past',[
            "kpi" => $kpi,
            "frequency" => $request->frequency,
            "type" => $request->type
        ]);
    }

    public function getGraphDataNow(Request $request)
    {
        $kpi = Kpis::where('id', $request->id);
        $kpi->with(['kpiDates' => function($qDates){
            $qDates->where('estado', 1);
            $qDates->where(function($qAnio){
                $qAnio->where('anio', date('Y'))
                    ->orWhere('anio', date('Y', strtotime('-1 year')));
            });
            $qDates->orderBy('ciclo', 'asc');
        }]);
        $kpi = $kpi->first();

        $labels = [];
        $datasets = [];

        $cicles = Kpis::getCicleDef();
        $freq = $kpi?$kpi->frecuencia:'men';
        $rows = $kpi?$cicles[$freq]["count"]:12; // default to 12 month basis
        for ($i = 1; $i <= $rows; $i++){
            switch($freq){
                case 'men':
                    $labels[] = Kpis::$months[$i-1];
                    break;
                case 'anu':
                    $labels[] = $cicles[$freq]["label"];
                    break;
                default:
                    $labels[] = $cicles[$freq]["label"]." ".$i;
            }
        }

        $cicles_data = [];
        for ($i=0; $i < 12; $i++) { 
            $cicles_data[] = [
                "real" => 0,
                "plan" => 0,
                "real_accum" => 0,
                "plan_accum" => 0,
                "real_past" => 0,
                "plan_past" => 0,
            ];
        }

        if($kpi){
            $realAccum = 0;
            $planAccum = 0;
            $kpiDates = $kpi->kpiDates;
            $x = -1;
            $lastCicle = 0;
            // check if all planned amount are the same
            $allEqualCheck = true;
            $planCheck = 0;
            $idx = 0;
            foreach ($kpiDates as $kpiDate) {
                if($kpiDate->anio == date('Y')){
                    if($idx == 0){
                        $planCheck = $kpiDate->meta_cantidad;
                    }
                    if($planCheck != $kpiDate->meta_cantidad){
                        $allEqualCheck = false;
                    }
                    $idx++;
                }
            }

            $accumAsAvg = ($kpi->tipo == "per" || $kpi->tipo == "rat") && $allEqualCheck;
            foreach ($kpiDates as $k => $kpiDate) {
                if($lastCicle != $kpiDate->ciclo){
                    $lastCicle = $kpiDate->ciclo;
                    $x++;
                }
                if($kpiDate->anio == date('Y')){
                    $realAccum += ($kpiDate->real_cantidad?:0)+0;
                    $planAccum += ($kpiDate->meta_cantidad?:0)+0;
                    $cicles_data[$x]["real"] = ($kpiDate->real_cantidad?:0)+0; // add +0 to remove excess of ceros
                    $cicles_data[$x]["plan"] = ($kpiDate->meta_cantidad?:0)+0; // add +0 to remove excess of ceros

                    // if all plan amount are the same and tipe "Per" or "Rat"
                    if($accumAsAvg){
                        $cicles_data[$x]["real_accum"] = ($realAccum/($x+1))+0; // add +0 to remove excess of ceros
                        $cicles_data[$x]["plan_accum"] = ($planAccum/($x+1))+0; // add +0 to remove excess of ceros
                    }else{
                        $cicles_data[$x]["real_accum"] = $realAccum; // add +0 to remove excess of ceros
                        $cicles_data[$x]["plan_accum"] = $planAccum; // add +0 to remove excess of ceros
                    }
                }
                if($kpiDate->anio == date('Y',strtotime('-1 year'))){
                    $cicles_data[$x]["real_past"] = ($kpiDate->real_cantidad?:0)+0; // add +0 to remove excess of ceros
                    $cicles_data[$x]["plan_past"] = ($kpiDate->meta_cantidad?:0)+0; // add +0 to remove excess of ceros
                }
            }
        }

        // DATASETS: Current Data
        // Monto Real
        $data = [];
        for ($i = 0; $i < $cicles[$freq]["count"]; $i++){
            if(isset($request->type)){
                switch($request->type){
                    case 'simple':
                        $data[] = $cicles_data[$i]["real"];
                        break;
                    case 'accumulated':
                        $data[] = $cicles_data[$i]["real_accum"];
                        break;
                    default:
                        $data[] = 0;
                }
            }else{
                $data[] = 0;
            }
        }

        $lbl = "";
        if(isset($request->type)){
            $lbl = (($accumAsAvg && $request->type == 'accumulated')?'Promedio':'Monto').' Real '.($request->type == 'simple'?'':'Acumulado');
        }else{
            $lbl = 'Monto Real';
        }

        $datasets[] = [
            "label" => $lbl,
            "data" => $data,
            "borderWidth" => 1
        ];

        // Monto Planificado
        $data = [];
        for ($i = 0; $i < $cicles[$freq]["count"]; $i++){
            if(isset($request->type)){
                switch($request->type){
                    case 'simple':
                        $data[] = $cicles_data[$i]["plan"];
                        break;
                    case 'accumulated':
                        $data[] = $cicles_data[$i]["plan_accum"];
                        break;
                    default:
                        $data[] = 0;
                }
            }else{
                $data[] = 0;
            }
        }
        $lbl = "";
        if(isset($request->type)){
            $lbl = (($accumAsAvg && $request->type == 'accumulated')?'Promedio':'Monto').' Planificado '.($request->type == 'simple'?'':'Acumulado');
        }else{
            $lbl = 'Monto Planificado';
        }
        $datasets[] = [
            "label" => $lbl,
            "data" => $data,
            "borderWidth" => 1
        ];

        $v1 = [
            "labels" => $labels,
            "datasets" => $datasets
        ];

        // DATASET: Past Data

        $data = [];
        for ($i = 0; $i < $cicles[$freq]["count"]; $i++){
            $data[] = $cicles_data[$i]["real_past"];
        }

        $datasets[] = [
            "label" => 'Monto Real Pasado',
            "data" => $data,
            "borderWidth" => 1
        ];

        $v2 = [
            "labels" => $labels,
            "datasets" => $datasets
        ];

        return [
            "v1" => $v1,
            "v2" => $v2,
        ];
    }

    public function store(Request $request)
    {
        $kpi = new Kpis();
        $kpi->objetivo_id = $request->objective;
        $kpi->nombre = $request->kpi;
        $kpi->descripcion = $request->description;
        $kpi->formula = $request->formula;
        $kpi->frecuencia = $request->frequency;
        $kpi->tipo = $request->type;
        $kpi->meta = $request->meta;
        $kpi->estado = 1;
        $kpi->save();

        for ($i=0; $i < sizeOf($request->real_cicle); $i++) { 
            $real = floatval($request->real_cicle[$i]);
            $plan = floatval($request->plan_cicle[$i]);
            $kpiDate = new KpiDates();
            $kpiDate->kpi_id = $kpi->id;
            $kpiDate->anio = intval(date('Y'));
            $kpiDate->ciclo = ($i+1);
            $kpiDate->real_cantidad = $real;
            $kpiDate->meta_cantidad = $plan;
            $kpiDate->estado = 1;
            $kpiDate->save();
        }

        for ($i=0; $i < sizeOf($request->real_pastcicle); $i++) {
            $plan = floatval($request->plan_pastcicle[$i]);
            $real = floatval($request->real_pastcicle[$i]);
            $kpiDate = new KpiDates();
            $kpiDate->kpi_id = $kpi->id;
            $kpiDate->anio = intval(date('Y',strtotime('-1 year')));
            $kpiDate->ciclo = ($i+1);
            $kpiDate->real_cantidad = $real;
            $kpiDate->meta_cantidad = $plan;
            $kpiDate->estado = 1;
            $kpiDate->save();
        }

        return redirect()->route('kpi', ['id'=>$kpi->id]);
    }

    public function update(Request $request)
    {
        //return $request->all();
        $kpi = Kpis::find($request->id);
        if($kpi){
            $freq_changed = ($kpi->frecuencia != $request->frequency);

            $kpi->nombre = $request->kpi;
            $kpi->descripcion = $request->description;
            $kpi->formula = $request->formula;
            $kpi->frecuencia = $request->frequency;
            $kpi->tipo = $request->type;
            $kpi->meta = $request->meta;
            $kpi->save();

            // if frequency changed, create new KpiDates
            if($freq_changed){
                KpiDates::where('kpi_id', $kpi->id)
                        ->where(function($qAnio){
                            $qAnio->where('anio', date('Y'))
                                ->orWhere('anio', date('Y', strtotime('-1 year')));
                        })
                        ->where('estado', 1)
                        ->update(["estado" => 0]);
    
                for ($i=0; $i < sizeOf($request->real_cicle); $i++) { 
                    $real = floatval($request->real_cicle[$i]);
                    $plan = floatval($request->plan_cicle[$i]);
                    $kpiDate = new KpiDates();
                    $kpiDate->kpi_id = $kpi->id;
                    $kpiDate->anio = intval(date('Y'));
                    $kpiDate->ciclo = ($i+1);
                    $kpiDate->real_cantidad = $real;
                    $kpiDate->meta_cantidad = $plan;
                    $kpiDate->estado = 1;
                    $kpiDate->save();
                }
    
                for ($i=0; $i < sizeOf($request->real_pastcicle); $i++) {
                    $real = floatval($request->real_pastcicle[$i]);
                    $plan = floatval($request->plan_pastcicle[$i]);
                    $kpiDate = new KpiDates();
                    $kpiDate->kpi_id = $kpi->id;
                    $kpiDate->anio = intval(date('Y',strtotime('-1 year')));
                    $kpiDate->ciclo = ($i+1);
                    $kpiDate->real_cantidad = $real;
                    $kpiDate->meta_cantidad = $plan;
                    $kpiDate->estado = 1;
                    $kpiDate->save();
                }
            }else{
                if(isset($request->now_id)){
                    for ($i=0; $i < sizeOf($request->now_id); $i++) {
                        $id = $request->now_id[$i];
                        $real = floatval($request->real_cicle[$i]);
                        $plan = floatval($request->plan_cicle[$i]);

                        $kpiDate = KpiDates::find($id);
                        if($kpiDate){
                            $kpiDate->real_cantidad = $real;
                            $kpiDate->meta_cantidad = $plan;
                            $kpiDate->save();
                        }else{
                            $kpiDate = new KpiDates();
                            $kpiDate->kpi_id = $kpi->id;
                            $kpiDate->anio = intval(date('Y'));
                            $kpiDate->ciclo = ($i+1);
                            $kpiDate->real_cantidad = $real;
                            $kpiDate->meta_cantidad = $plan;
                            $kpiDate->estado = 1;
                            $kpiDate->save();
                        }
                    }
                }

                if(isset($request->past_id)){
                    for ($i=0; $i < sizeOf($request->past_id); $i++) {
                        $id = $request->past_id[$i];
                        $real = floatval($request->real_pastcicle[$i]);
                        $plan = floatval($request->plan_pastcicle[$i]);

                        $kpiDate = KpiDates::find($id);
                        if($kpiDate){
                            $kpiDate->real_cantidad = $real;
                            $kpiDate->meta_cantidad = $plan;
                            $kpiDate->save();
                        }else{
                            $kpiDate = new KpiDates();
                            $kpiDate->kpi_id = $kpi->id;
                            $kpiDate->anio = intval(date('Y',strtotime('-1 year')));
                            $kpiDate->ciclo = ($i+1);
                            $kpiDate->real_cantidad = $real;
                            $kpiDate->meta_cantidad = $plan;
                            $kpiDate->estado = 1;
                            $kpiDate->save();
                        }
                    }
                }
            }
        }
        return back();
    }

    public function getAddKpiForm(Request $request)
    {
        $objectives = StratObjective::where('estado',1);
        if(isset($request->strat_id)){
            $objectives->where('obj_estrategico_id', $request->strat_id);
        }
        $objectives = $objectives->orderBy('codigo','asc')
                    ->get();
        return view('intranet.objectives.forms.newKpi',[
            "objectives" => $objectives
        ]);
    }

    public function delete(Request $request)
    {
        $kpi = Kpis::find($request->kpi_id);
        if($kpi){
            $kpi->estado = 0;
            $kpi->save();
            return ['status'=>'ok', 'kpi'=>$kpi->id];
        }
        return ['status'=>'error', 'msg'=>'Kpi no encontrado'];
    }
}
