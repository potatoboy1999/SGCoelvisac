@php
    function countAllKpis($dimension)
    {
        $count = 0;
        $objs = $dimension->stratObjectives->whereNull('obj_estrategico_id');
        foreach ($objs as $obj) {
            $kpis = $obj->kpis->where('estado', 1);
            $count += sizeOf($kpis);
        }
        return $count;
    }

    function progressColor($progress)
    {
        $colors = ["red","green","blue"];
        $color = 0;
        if($progress < 80){
            $color = 0;
        }else if(80 <= $progress && $progress < 100){
            $color = 1;
        }else if($progress >= 100){
            $color = 2;
        }
        return $colors[$color];
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Strategic Objectives Report</title>
    <style>
        .page-break {
            page-break-after: always;
        }
        .pilar-name{
            font-size: 11px;
        }
        body{
            font-family: arial, sans-serif!important;
        }
        .align-middle {
            vertical-align: middle !important;
        }
        html:not([dir=rtl]) .text-center {
            text-align: center !important;
        }
        *[dir=rtl] .text-center {
            text-align: center !important;
        }
        table {
            caption-side: bottom;
            border-collapse: collapse;
        }

        th {
            /* font-weight: 600; */
            /* text-align: inherit; */
            /* text-align: -webkit-match-parent; */
        }

        thead,
        tbody,
        tfoot,
        tr,
        td,
        th {
            font-family: arial, sans-serif!important;
            border-color: inherit;
            border-style: solid;
            border-width: 1;
            font-size: 8px;
        }
        
        td,
        th {
            padding: 2px;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: rgba(44, 56, 74, 0.95);
            vertical-align: top;
            border-color: #d8dbe0;
        }
        .table > :not(caption) > * > * {
            padding: 0.5rem 0.5rem;
            color: rgba(44, 56, 74, 0.95);
            background-color: transparent;
            border-bottom-color: #d8dbe0;
            border-bottom-width: 1px;
            box-shadow: inset 0 0 0 9999px transparent;
        }
        .table > tbody {
            vertical-align: inherit;
        }
        .table > thead {
            vertical-align: bottom;
        }

        .caption-top {
            caption-side: top;
        }

        .table-bordered > :not(caption) > * {
            border-width: 1px 0;
        }
        .table-bordered > :not(caption) > * > * {
            border-width: 0 1px;
        }

        .pilar-header {
            margin-bottom: 10px;
        }

        thead tr th{
            background-color: #51607c!important;
            color: white!important;
        }

        .circle{
            height: 10px;
            width: 10px;
            border-radius: 50%;
            background-color: #cbcbcb;
        }
        .circle.c-red{
            background-color: rgba(240, 62, 62, 1);
            border: 2px solid rgba(165, 0, 0, 1);
        }
        .circle.c-green{
            background-color: rgba(4, 192, 0, 1);
            border: 2px solid rgba(0, 150, 15, 1);
        }
        .circle.c-blue{
            background-color: rgb(17, 63, 189, 1);
            border: 2px solid rgb(2, 0, 150, 1);
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- PAGE 1: SCHEDULE DETAILS --}}
        {{-- <img class="logo" src="img/logo.png" height="50" alt=""> --}}
        <h3 style="font-family: sans-serif; color:#008cff">CVC ENERGIA</h3>
        <h2 style="text-align: center;">Objetivos Estratégicos</h2>
        <P style="font-size: 10px; margin: 0;">Fecha: {{date('d/m/Y')}}</P>
        <div id="matrix_content">
            @foreach ($pilars as $pilar)
            <div class="pilar" pilar="{{$pilar->id}}">
                <div class="pilar-header">
                    <span class="pilar-name">{{mb_strtoupper($pilar->nombre)}}</span>&nbsp;
                </div>
                <div class="pilar-body pilar-{{$pilar->id}} collapse show" id="collapsePilar-{{$pilar->id}}" pilar="{{$pilar->id}}">
                    <div class="card mb-4">
                        <div class="card-body p-0">
                            <div class="">
                                <table border="1" class="table table-bordered m-0" pilar="{{$pilar->id}}" style="">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle t-head-dimension" >Dimensión</th>
                                            <th class="text-center align-middle t-head-code" >Código</th>
                                            <th class="text-center align-middle t-head-rol" >Rol</th>
                                            <th class="text-center align-middle t-head-objective" >Objetivo Estratégico</th>
                                            <th class="text-center align-middle t-head-sponsor" >Sponsor</th>
                                            <th class="text-center align-middle t-head-kpi" >KPI</th>
                                            <th class="text-center align-middle t-head-formula" >Fórmula</th>
                                            <th class="text-center align-middle t-head-frequency" >Frecuencia</th>
                                            <th class="text-center align-middle t-head-type" >Tipo</th>
                                            <th class="text-center align-middle t-head-nextyear">{{date('Y', strtotime('-1 year'))}}</th>
                                            <th class="text-center align-middle t-head-goal" >Meta</th>
                                            <th class="text-center align-middle t-head-resmes" >Res. Mes</th>
                                            <th class="text-center align-middle t-head-resacum" >Res. Acum.</th>
                                        </tr>
                                        <tbody>
                                            <?php $y = 0; ?>
                                            @foreach ($pilar->dimensions as $dimension)
                                                <?php 
                                                    $rowSpan = countAllKpis($dimension);
                                                    $stratObjectives = $dimension->stratObjectives->whereNull('obj_estrategico_id');
                                                    $x = 0;
                                                ?>
                                                @foreach ($stratObjectives as $stratObj)
                                                    <?php 
                                                        $kpis = $stratObj->kpis;
                                                        $k = 0;
                                                    ?>
                                                    @if (sizeof($kpis) == 0)
                                                        <tr class="dim-{{$dimension->id}} obj-{{$stratObj->id}}" dim="{{$dimension->id}}" strat="{{$stratObj->id}}">
                                                            <td class="align-middle">{{$dimension->nombre}}</td>
                                                            <td class="align-middle text-center" align="center" style="">
                                                                <span class="badge bg-primary obj-code">{{$stratObj->codigo}}</span>
                                                            </td>
                                                            <td class="align-middle" style="">
                                                                {{$stratObj->rol->nombres}}
                                                            </td>
                                                            <td class="align-middle" style="">
                                                                {{$stratObj->nombre}}
                                                            </td>
                                                            <td class="align-middle" style="">
                                                                {{$stratObj->area->nombre}}
                                                            </td>
                                                            <td class="align-middle"></td>
                                                            <td class="align-middle"></td>
                                                            <td class="align-middle"></td>
                                                            <td class="align-middle"></td>
                                                            <td class="align-middle"></td>
                                                            <td class="align-middle"></td>
                                                            <td class="align-middle text-center" align="center"></td>
                                                            <td class="align-middle text-center" align="center"></td>
                                                        </tr>
                                                    @endif
                                                    @foreach ($kpis as $kpi)
                                                        @php
                                                            $month = intval(date('m'));
                                                            $cicles_groups = $cicles[$kpi->frecuencia]["cicles"];
                                                            $cicle_i = 0;
                                                            for($i = 0; $i < sizeOf($cicles_groups); $i++){
                                                                $group = $cicles_groups[$i];
                                                                if(array_search($month, $group) !== false){
                                                                    $cicle_i = $i;
                                                                    break;
                                                                };
                                                            }
                                                        @endphp
                                                        <tr class="dim-{{$dimension->id}} obj-{{$stratObj->id}} kpi-{{$kpi->id}}" dim="{{$dimension->id}}" strat="{{$stratObj->id}}" kpi="{{$kpi->id}}">
                                                            <td class="align-middle rowspan-bound td-dimension" rowspan="{{$rowSpan}}" style="{{($x == 0 && $k == 0)?'':'display: none;'}}">{{$dimension->nombre}}</td>
                                                            <td class="align-middle rowspan-bound td-stratcode text-center" rowspan="{{sizeOf($kpis)}}" align="center" style="{{($k == 0)?'':'display: none;'}}">
                                                                <span class="badge bg-primary obj-code">{{$stratObj->codigo}}</span>
                                                            </td>
                                                            <td class="align-middle rowspan-bound td-rol" rowspan="{{sizeOf($kpis)}}" style="{{($k == 0)?'':'display: none;'}}">
                                                                {{$stratObj->rol->nombres}}
                                                            </td>
                                                            <td class="align-middle rowspan-bound td-strat" rowspan="{{sizeOf($kpis)}}" style="{{($k == 0)?'':'display: none;'}}">
                                                                {{$stratObj->nombre}}
                                                            </td>
                                                            <td class="align-middle rowspan-bound td-area" rowspan="{{sizeOf($kpis)}}" style="{{($k == 0)?'':'display: none;'}}">
                                                                {{$stratObj->area->nombre}}
                                                            </td>
                                                            <td class="align-middle kpi-name">{{$kpi->nombre}}</td>
                                                            <td class="align-middle">{{$kpi->formula}}</td>
                                                            <td class="align-middle">{{$cicles[$kpi->frecuencia]["name"]}}</td>
                                                            <td class="align-middle">{{$types[$kpi->tipo]["name"]}}</td>
                                                            @php
                                                                $p_real_acumm = 0;
                                                                $p_real_count = 0;
                                                                if($kpi->kpiDates){
                                                                    foreach ($kpi->kpiDates as $kd => $date) {
                                                                        if($date->anio == date('Y', strtotime('-1 years'))){
                                                                            // get acummulated
                                                                            $t_real = $date->real_cantidad + 0;
                                                                            if($date->ciclo <= ($cicle_i+1)){
                                                                                $p_real_acumm += $t_real;
                                                                                $p_real_count++;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                // if kpi type "percentage" or "ratio" get average
                                                                if($kpi->tipo == "per" || $kpi->tipo == "rat"){
                                                                    $p_real_acumm = ($p_real_acumm/$p_real_count)+0;
                                                                }
                                                            @endphp
                                                            <td class="align-middle">{{$p_real_acumm}}</td>
                                                            <td class="align-middle">{{$kpi->meta}}</td>
                                                            @php
                                                                $tracker = 'temp'.$stratObj->id.$k.$cicle_i;
                                                                $real = 0;
                                                                $real_acumm = 0;
                                                                $plan = 0;
                                                                $plan_acumm = 0;
                                                                $perc = 0;
                                                                $perc_acumm = 0;
                                                                if($kpi->kpiDates){
                                                                    foreach ($kpi->kpiDates as $kd => $date) {
                                                                        if($date->anio == date('Y')){
                                                                            // get acummulated
                                                                            $t_real = $date->real_cantidad + 0;
                                                                            $t_plan = $date->meta_cantidad + 0;
                                                                            if($date->ciclo <= ($cicle_i+1)){
                                                                                $real_acumm += $t_real;
                                                                                $plan_acumm += $t_plan;
                                                                            }
                                                                            // get current
                                                                            if($kd == $cicle_i && $date->ciclo == ($cicle_i+1)){
                                                                                $real = $t_real;
                                                                                $plan = $t_plan;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                if($plan != 0){
                                                                    $perc = round(($real/$plan),2)*100;
                                                                }
                                                                if($plan_acumm != 0){
                                                                    $perc_acumm = round(($real_acumm/$plan_acumm),2)*100;
                                                                }
                                                            @endphp
                                                            <td class="align-middle text-center" align="center">
                                                                <div class="circle c-{{progressColor($perc)}}" href="#" role="button" style="margin: auto"></div>
                                                            </td>
                                                            <td class="align-middle text-center" align="center">
                                                                <div class="circle c-{{progressColor($perc_acumm)}}" href="#" role="button" style="margin: auto"></div>
                                                            </td>
                                                        </tr>
                                                        <?php $k++; ?>
                                                    @endforeach
                                                    <?php $x++; ?>
                                                @endforeach
                                                <?php $y++; ?>
                                            @endforeach
                                        </tbody>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>