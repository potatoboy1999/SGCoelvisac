@php
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
@if ($status == "ok")
    <div class="table-responsive">
        <table class="table table-bordered m-0">
            <thead>
                <tr>
                    <th class="text-center align-middle t-head-code" width="50">Código</th>
                    <th class="text-center align-middle t-head-objective" width="180">Objetivo Específico</th>
                    <th class="text-center align-middle t-head-sponsor" width="50">Responsable</th>
                    <th class="text-center align-middle t-head-kpi" width="50">KPI</th>
                    @for ($x = 0; $x < 12; $x++)
                    <th class="text-center align-middle t-head-cicle" width="50">Ciclo {{$x+1}}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                <?php $y = 0; ?>
                @foreach ($specifics as $spec)
                    <?php 
                        $kpis = $spec->kpis;
                        $k = 0;
                    ?>
                    @foreach ($kpis as $kpi)
                        <tr class="obj-{{$spec->id}} kpi-{{$kpi->id}}" strat="{{$spec->id}}" kpi="{{$kpi->id}}">
                            <td class="align-middle rowspan-bound td-speccode" rowspan="{{sizeOf($kpis)}}" align="center" style="{{($k == 0)?'':'display: none;'}}">
                                <a href="{{route('front.actions')}}?specific={{$spec->id}}"><span class="badge bg-primary obj-code">{{$spec->codigo}}</span></a>
                            </td>
                            <td class="align-middle rowspan-bound td-spec" rowspan="{{sizeOf($kpis)}}" style="{{($k == 0)?'':'display: none;'}}">
                                <a href="{{route('front.actions')}}?specific={{$spec->id}}">{{$spec->nombre}}</a>
                            </td>
                            <td class="align-middle rowspan-bound td-area" rowspan="{{sizeOf($kpis)}}" style="{{($k == 0)?'':'display: none;'}}">
                                {{$spec->area->nombre}}
                            </td>
                            <td class="align-middle kpi-name">{{$kpi->nombre}}</td>
                            @for ($cicle_i = 0; $cicle_i < 12; $cicle_i++)
                                @php
                                    $cicles_groups = $cicles[$kpi->frecuencia]["cicles"];
                                    $tracker = 'temp'.$spec->id.$k.$cicle_i;
                                    $real = 0;
                                    $plan = 0;
                                    $perc = 0;
                                    $na = false;
                                    if($kpi->kpiDates && sizeOf($kpi->kpiDates) > 0){
                                        if(sizeOf($kpi->kpiDates) > $cicle_i){
                                            $date = $kpi->kpiDates[$cicle_i];
                                            if($date->ciclo == ($cicle_i+1)){
                                                $tracker = $date->id;
                                                $real = $date->real_cantidad + 0;
                                                $plan = $date->meta_cantidad + 0;
                                            }
                                        }else{
                                            $na = true;
                                        }
                                    }
                                    if($plan != 0){
                                        $perc = round(($real/$plan),2)*100;
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
                            @endfor
                        </tr>
                        <?php $k++; ?>
                    @endforeach
                    <?php $y++; ?>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <h3 class="text-danger">Objetivo no encontrado</h3>
@endif