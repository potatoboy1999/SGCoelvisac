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

{{-- <div class="card mb-4"> --}}
@if ($status == "ok")
    {{-- <div class="card-body p-0"> --}}
        <div class="table-responsive">
            <table class="table table-bordered m-0" pilar="{{$pilar->id}}">
                <thead>
                    <tr>
                        <th class="text-center align-middle t-head-dimension" width="50">Dimensión</th>
                        <th class="text-center align-middle t-head-code" width="50">Código</th>
                        <th class="text-center align-middle t-head-rol" width="150">Rol</th>
                        <th class="text-center align-middle t-head-objective" width="180">Objetivo Estratégico</th>
                        <th class="text-center align-middle t-head-sponsor" width="50">Sponsor</th>
                        <th class="text-center align-middle t-head-kpi" width="50">KPI</th>
                        <th class="text-center align-middle t-head-formula" width="120">Fórmula</th>
                        <th class="text-center align-middle t-head-frequency" width="50">Frecuencia</th>
                        <th class="text-center align-middle t-head-type" width="50">Tipo</th>
                        <th class="text-center align-middle t-head-nextyear" width="50">{{date('Y', strtotime('-1 year'))}}</th>
                        <th class="text-center align-middle t-head-goal" width="50">Meta</th>
                        <th class="text-center align-middle t-head-resmes" width="50">Res. Mes</th>
                        <th class="text-center align-middle t-head-resacum" width="50">Res. Acum.</th>
                    </tr>
                </thead>
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
                                    <td class="align-middle" align="center" style="">
                                        <a href="{{route('front.specifics')}}?strat={{$stratObj->id}}"><span class="badge bg-primary obj-code">{{$stratObj->codigo}}</span></a>
                                    </td>
                                    <td class="align-middle">{{$stratObj->rol?$stratObj->rol->nombres:'No Aplica'}}</td>
                                    <td class="align-middle" style="">
                                        <a href="{{route('front.specifics')}}?strat={{$stratObj->id}}">{{$stratObj->nombre}}</a>
                                    </td>
                                    <td class="align-middle" style="">
                                        {{$stratObj->area->nombre}}
                                    </td>
                                    <td class="align-middle"></td>
                                    <td class="align-middle"></td>
                                    <td class="align-middle"></td>
                                    <td class="align-middle"></td>
                                    <td class="align-middle"></td>
                                    <td class="align-middle" align="center"></td>
                                    <td class="align-middle" align="center"></td>
                                    <td class="align-middle" align="center"></td>
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
                                    <td class="align-middle rowspan-bound td-stratcode" rowspan="{{sizeOf($kpis)}}" align="center" style="{{($k == 0)?'':'display: none;'}}">
                                        <a href="{{route('front.specifics')}}?strat={{$stratObj->id}}"><span class="badge bg-primary obj-code">{{$stratObj->codigo}}</span></a>
                                    </td>
                                    <td class="align-middle rowspan-bound td-rol" rowspan="{{sizeOf($kpis)}}" style="{{($k == 0)?'':'display: none;'}}">
                                        {{$stratObj->rol?$stratObj->rol->nombres:'No Aplica'}}
                                    </td>
                                    <td class="align-middle rowspan-bound td-strat" rowspan="{{sizeOf($kpis)}}" style="{{($k == 0)?'':'display: none;'}}">
                                        <a href="{{route('front.specifics')}}?strat={{$stratObj->id}}">{{$stratObj->nombre}}</a>
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
                                    <td class="align-middle" align="center">{{$p_real_acumm}}</td>
                                    <td class="align-middle">{{$kpi->meta}}</td>
                                    @php
                                        $tracker = 'temp'.$stratObj->id.$k.$cicle_i;
                                        $real = 0;
                                        $real_acumm = 0;
                                        $plan = 0;
                                        $plan_acumm = 0;
                                        $perc = 0;
                                        $perc_acumm = 0;
                                        $na = false;
                                        // if this is the first cicle and is not anually -> DONT SHOW BUTTON INFO
                                        if($cicle_i == 0 && $kpi->frecuencia != "anu"){
                                            $na = true;
                                        }else{
                                            // else show button info from previous cicle
                                            $cicle_i -= 1;
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
                                                        if($date->ciclo == ($cicle_i+1)){
                                                            $real = $t_real;
                                                            $plan = $t_plan;
                                                        }
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
                                    <td class="align-middle" align="center" style="{{$na?'background-color:#ccc':''}}">
                                        @if (!$na)
                                        <div class="dropdown" ddTrack="{{$tracker}}">
                                            <div class="circle c-{{progressColor($perc)}}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"></div>
                                            <ul class="dropdown-menu p-2" ddTrack="{{$tracker}}">
                                                <li class="info-pop">
                                                    <div class="pop-banner pop-{{progressColor($perc)}}"></div>
                                                    {{-- <span><strong>Meta %</strong>: {{$plan}}</span><br> --}}
                                                    <span><strong>Meta</strong>: {{$plan}}</span>
                                                    <hr>
                                                    <span><strong>Real %</strong>: {{$perc}}%</span><br>
                                                    <span><strong>Real</strong>: {{$real}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        @endif
                                    </td>
                                    <td class="align-middle" align="center" style="{{$na?'background-color:#ccc':''}}">
                                        @if (!$na)
                                        <div class="dropdown" ddTrack="accum-{{$tracker}}">
                                            <div class="circle c-{{progressColor($perc_acumm)}}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"></div>
                                            <ul class="dropdown-menu p-2" ddTrack="accum-{{$tracker}}">
                                                <li class="info-pop">
                                                    <div class="pop-banner pop-{{progressColor($perc_acumm)}}"></div>
                                                    {{-- <span><strong>Meta %</strong>: {{$plan}}</span><br> --}}
                                                    <span><strong>Meta</strong>: {{$plan_acumm}}</span>
                                                    <hr>
                                                    <span><strong>Real %</strong>: {{$perc_acumm}}%</span><br>
                                                    <span><strong>Real</strong>: {{$real_acumm}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                <?php $k++; ?>
                            @endforeach
                            <?php $x++; ?>
                        @endforeach
                        <?php $y++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    {{-- </div> --}}
@else
    {{-- <div class="card-body"> --}}
        <h3 class="text-danger">Pilar no encontrado</h3>
    {{-- </div> --}}
@endif
{{-- </div> --}}