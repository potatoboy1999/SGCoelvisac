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

<div class="card mb-4">
@if ($status == "ok")
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered m-0" pilar="{{$pilar->id}}">
                <thead>
                    <tr>
                        <th class="text-center align-middle t-head-dimension" width="50">Dimensión</th>
                        <th class="text-center align-middle t-head-code" width="50">Código</th>
                        <th class="text-center align-middle t-head-objective" width="180">Objetivo Estratégico</th>
                        <th class="text-center align-middle t-head-sponsor" width="50">Sponsor</th>
                        <th class="text-center align-middle t-head-kpi" width="50">KPI</th>
                        @for ($x = 0; $x < 12; $x++)
                        <th class="text-center align-middle t-head-cicle" width="50">Ciclo {{$x+1}}</th>
                        @endfor
                        <th class="text-center align-middle t-head-actions" width="20"></th>
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
                            @foreach ($kpis as $kpi)
                                <tr>
                                    <td class="align-middle" rowspan="{{$rowSpan}}" style="{{($x == 0 && $k == 0)?'':'display: none;'}}">{{$dimension->nombre}}</td>
                                    <td class="align-middle" rowspan="{{sizeOf($kpis)}}" align="center" style="{{($k == 0)?'':'display: none;'}}">
                                        <a href="{{route('specifics')}}?strat={{$stratObj->id}}"><span class="badge bg-primary obj-code">{{$stratObj->codigo}}</span></a>
                                    </td>
                                    <td class="align-middle" rowspan="{{sizeOf($kpis)}}" style="{{($k == 0)?'':'display: none;'}}">
                                        <a href="{{route('specifics')}}?strat={{$stratObj->id}}">{{$stratObj->nombre}}</a>
                                    </td>
                                    <td class="align-middle" rowspan="{{sizeOf($kpis)}}" style="{{($k == 0)?'':'display: none;'}}">
                                        {{$stratObj->area->nombre}}
                                    </td>
                                    <td class="align-middle">{{$kpi->nombre}}</td>
                                    @for ($cicle_i = 0; $cicle_i < 12; $cicle_i++)
                                        @php
                                            $cicles_groups = $cicles[$kpi->frecuencia]["cicles"];
                                            $tracker = 'temp'.$stratObj->id.$k.$cicle_i;
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
                                                <div class="circle c-{{progressColor($perc)}}" href="#" role="button" data-coreui-toggle="dropdown" aria-expanded="false"></div>
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
                                    <td class="align-middle" align="center">
                                        <div class="dropdown" ddTrack="{{'act'.$stratObj->id.$k}}">
                                            <span class="badge bg-secondary btn-more text-black" href="#" role="button" data-coreui-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis"></i>
                                            </span>
                                            <ul class="dropdown-menu p-0" ddTrack="{{'act'.$stratObj->id.$k}}">
                                                <li>
                                                    <a class="dropdown-item" href="{{route('kpi')}}?id={{$kpi->id}}">
                                                        Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item bg-danger text-white" href="">
                                                        <svg class="icon">
                                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-trash"></use>
                                                        </svg> Eliminar
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
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
    </div>
@else
    <div class="card-body">
        <h3 class="text-danger">Pilar no encontrado</h3>
    </div>
@endif
</div>