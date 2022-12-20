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

    public function getMatrixFuture(Request $request)
    {
        $kpi = Kpis::where('id',$request->id);
        $kpi->with(['kpiDates' => function($qDates){
            $qDates->where('estado', 1);
            $qDates->where('anio', intval(date('Y',strtotime('+1 year'))));
            $qDates->orderBy('ciclo', 'asc');
        }]);
        $kpi = $kpi->first();
        return view('intranet.kpis.matrix.future',[
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
            $qDates->where('anio', date('Y'));
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
                "plan_accum" => 0
            ];
        }

        if($kpi){
            $realAccum = 0;
            $planAccum = 0;
            $kpiDates = $kpi->kpiDates;
            foreach ($kpiDates as $k => $kpiDate) {
                $realAccum += ($kpiDate->real_cantidad?:0)+0;
                $planAccum += ($kpiDate->meta_cantidad?:0)+0;
                $cicles_data[$k]["real"] = ($kpiDate->real_cantidad?:0)+0; // add +0 to remove excess of ceros
                $cicles_data[$k]["plan"] = ($kpiDate->meta_cantidad?:0)+0; // add +0 to remove excess of ceros
                $cicles_data[$k]["real_accum"] = $realAccum; // add +0 to remove excess of ceros
                $cicles_data[$k]["plan_accum"] = $planAccum; // add +0 to remove excess of ceros
            }
        }

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

        $datasets[] = [
            "label" => 'Monto Real'.(isset($request->type)?($request->type == 'simple'?'':' Acumulado'):''),
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
        $datasets[] = [
            "label" => 'Monto Planificado'.(isset($request->type)?($request->type == 'simple'?'':' Acumulado'):''),
            "data" => $data,
            "borderWidth" => 1
        ];

        return [
            "labels" => $labels,
            "datasets" => $datasets
        ];
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

        KpiDates::where('kpi_id', $kpi->id)->where('anio', date('Y'))->delete();
        KpiDates::where('kpi_id', $kpi->id)->where('anio', date('Y',strtotime('+1 year')))->delete();

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

        for ($i=0; $i < sizeOf($request->plan_futurecicle); $i++) {
            $plan = floatval($request->plan_futurecicle[$i]);
            $kpiDate = new KpiDates();
            $kpiDate->kpi_id = $kpi->id;
            $kpiDate->anio = intval(date('Y',strtotime('+1 year')));
            $kpiDate->ciclo = ($i+1);
            $kpiDate->real_cantidad = 0;
            $kpiDate->meta_cantidad = $plan;
            $kpiDate->estado = 1;
            $kpiDate->save();
        }

        return redirect()->route('kpi', ['id'=>$kpi->id]);
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

    public function update(Request $request)
    {
        // return $request->all();
        $kpi = Kpis::find($request->id);
        if($kpi){
            $kpi->nombre = $request->kpi;
            $kpi->descripcion = $request->description;
            $kpi->formula = $request->formula;
            $kpi->frecuencia = $request->frequency;
            $kpi->tipo = $request->type;
            $kpi->meta = $request->meta;
            $kpi->save();

            KpiDates::where('kpi_id', $kpi->id)->where('anio', date('Y'))->delete();
            KpiDates::where('kpi_id', $kpi->id)->where('anio', date('Y',strtotime('+1 year')))->delete();

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

            for ($i=0; $i < sizeOf($request->plan_futurecicle); $i++) {
                $plan = floatval($request->plan_futurecicle[$i]);
                $kpiDate = new KpiDates();
                $kpiDate->kpi_id = $kpi->id;
                $kpiDate->anio = intval(date('Y',strtotime('+1 year')));
                $kpiDate->ciclo = ($i+1);
                $kpiDate->real_cantidad = 0;
                $kpiDate->meta_cantidad = $plan;
                $kpiDate->estado = 1;
                $kpiDate->save();
            }
        }
        return back();
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
