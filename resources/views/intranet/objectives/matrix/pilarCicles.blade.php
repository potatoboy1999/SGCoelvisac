@php
    function rowsTotal($usr, $dimensions)
    {
        $rowCount = 0;
        foreach ($dimensions as $dimension){
            $rowSpan = countAllKpis($dimension);
            foreach ($dimension->stratObjectives as $stratObj) {
                $isValidObj = userIsAllowed(Auth::user(), $stratObj);
                if($isValidObj){
                    $kpis = $stratObj->kpis;
                    $rowCount += sizeof($kpis) == 0? 1: sizeof($kpis);
                }
            }
        }
        return $rowCount;
    }

    function userIsAllowed($usr, $obj){
        $isValid = false;
        $is_admin = $usr->is_admin;
        $area_id = $usr->position->area_id; // 11 = area gestion
        if($is_admin){
            return true;
        }
        if($area_id != 11){
            foreach ($obj->users as $k => $user) {
                if($user->id == $usr->id){
                    $isValid = true;
                }
            }
        }else{
            return true;
        }
        return $isValid;
    }

    function countAllKpis($dimension)
    {
        $count = 0;
        $objs = $dimension->stratObjectives;
        foreach ($objs as $obj) {
            if(userIsAllowed(Auth::user(), $obj)){
                $kpis = $obj->kpis->where('estado', 1);
                $count += sizeOf($kpis);
            }
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

@if ($status == "ok")
    @if (rowsTotal(Auth::user(), $pilar->dimensions) == 0)
        <div class="card mb-4">
            <div class="card-body">
                <h5>Ningun objetivo estrategico encontrado</h5>
            </div>
        </div>
    @else
        <div class="card mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered m-0" pilar="{{$pilar->id}}">
                        <thead>
                            <tr>
                                <th class="text-center align-middle t-head-dimension" width="50">Dimensión</th>
                                <th class="text-center align-middle t-head-code" width="50">Código</th>
                                <th class="text-center align-middle t-head-rol" width="120">Rol</th>
                                <th class="text-center align-middle t-head-objective" width="180">Objetivo Estratégico</th>
                                <th class="text-center align-middle t-head-sponsor" width="50">Sponsor</th>
                                <th class="text-center align-middle t-head-kpi" width="50">KPI</th>
                                @for ($x = 0; $x < 12; $x++)
                                <th class="text-center align-middle t-head-cicle" width="50">{{$months[$x]}}</th>
                                @endfor
                                <th class="text-center align-middle t-head-actions" width="20"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $y = 0; ?>
                            @foreach ($pilar->dimensions as $dimension)
                                <?php 
                                    $rowSpan = countAllKpis($dimension);
                                    $stratObjectives = $dimension->stratObjectives;
                                    $x = 0;
                                ?>
                                @foreach ($stratObjectives as $stratObj)
                                    <?php 
                                        $isValidObj = userIsAllowed(Auth::user(), $stratObj);
                                        $kpis = $stratObj->kpis;
                                        $k = 0;
                                    ?>
                                    @if ($isValidObj)
                                        @foreach ($kpis as $kpi)
                                            <tr class="dim-{{$dimension->id}} obj-{{$stratObj->id}} kpi-{{$kpi->id}}" dim="{{$dimension->id}}" strat="{{$stratObj->id}}" kpi="{{$kpi->id}}">
                                                <td class="align-middle rowspan-bound td-dimension" rowspan="{{$rowSpan}}" style="{{($x == 0 && $k == 0)?'':'display: none;'}}">{{$dimension->nombre}}</td>
                                                <td class="align-middle rowspan-bound td-stratcode" rowspan="{{sizeOf($kpis)}}" align="center" style="{{($k == 0)?'':'display: none;'}}">
                                                    <a href="{{route('specifics')}}?strat={{$stratObj->id}}"><span class="badge bg-primary obj-code">{{$stratObj->codigo}}</span></a>
                                                </td>
                                                <td class="align-middle rowspan-bound td-rolname" rowspan="{{sizeOf($kpis)}}" style="{{($k == 0)?'':'display: none;'}}">
                                                    {{$stratObj->rol->nombres}}
                                                </td>
                                                <td class="align-middle rowspan-bound td-strat" rowspan="{{sizeOf($kpis)}}" style="{{($k == 0)?'':'display: none;'}}">
                                                    <a href="{{route('specifics')}}?strat={{$stratObj->id}}">{{$stratObj->nombre}}</a>
                                                    {{-- If is GESTION or is manager or user IS_ADMIN --}}
                                                    @if (Auth::user()->position->area_id == 11 || Auth::user()->position->es_gerente == 1 || Auth::user()->is_admin == 1)
                                                    <br>
                                                    <a href="#" class="btn btn-sm btn-success btn-comments" data-obj="{{$stratObj->id}}" data-coreui-toggle="modal" data-coreui-target="#commentModal">
                                                        <i class="fa-solid fa-comment text-white"></i> 
                                                        <span class="text-white">Comentarios</span> 
                                                        <span class="comm-count text-success">{{sizeof($stratObj->comments)}}</span>
                                                    </a> 
                                                    @endif
                                                </td>
                                                <td class="align-middle rowspan-bound td-area" rowspan="{{sizeOf($kpis)}}" style="{{($k == 0)?'':'display: none;'}}">
                                                    {{$stratObj->area->nombre}}
                                                </td>
                                                <td class="align-middle kpi-name">
                                                    <a href="{{route('kpi')}}?id={{$kpi->id}}">
                                                        {{$kpi->nombre}}
                                                    </a>
                                                </td>
                                                @php
                                                    $kpiDates = [];
                                                    if($kpi->kpiDates && sizeOf($kpi->kpiDates) > 0){
                                                        foreach($kpi->kpiDates as $date){
                                                            if($date->anio == date('Y')){
                                                                $kpiDates[] = $date;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                @for ($mon_i = 0; $mon_i < 12; $mon_i++)
                                                    @php
                                                        $cicles_groups = $cicles[$kpi->frecuencia]["cicles"];
                                                        $cicle_i = 0;
                                                        $curr_group = [];
                                                        for($i = 0; $i < sizeOf($cicles_groups); $i++){
                                                            $group = $cicles_groups[$i];
                                                            if(array_search(($mon_i+1), $group) !== false){
                                                                $cicle_i = $i;
                                                                $curr_group = $group;
                                                                break;
                                                            };
                                                        }

                                                        $tracker = 'temp'.$stratObj->id.$k.$mon_i;
                                                        $real = 0;
                                                        $plan = 0;
                                                        $perc = 0;
                                                        $na = false;

                                                        // check if this month is last on group
                                                        $lastIdx = sizeOf($curr_group)-1;
                                                        if($curr_group[$lastIdx] == ($mon_i+1)){
                                                            if(sizeOf($kpiDates) > $cicle_i){
                                                                $date = $kpiDates[$cicle_i];
                                                                if($date->ciclo == ($cicle_i+1)){
                                                                    $tracker = $date->id;
                                                                    $real = $date->real_cantidad + 0;
                                                                    $plan = $date->meta_cantidad + 0;
                                                                }
                                                            }else{
                                                                $na = true;
                                                            }
                                                            if($plan != 0){
                                                                $perc = round(($real/$plan),2)*100;
                                                            }
                                                        }else{
                                                            $na = true;
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
                                                                    Editar KPI
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item edit-obj" obj="{{$stratObj->id}}" href="#" data-coreui-toggle="modal" data-coreui-target="#objectiveEditModal">
                                                                    Editar Objetivo
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item bg-danger text-white dlt-kpi" kpi="{{$kpi->id}}" href="#" data-coreui-toggle="modal" data-coreui-target="#deleteKpiModal">
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
                                    @endif
                                    <?php $x++; ?>
                                @endforeach
                                <?php $y++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@else
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="text-danger">Pilar no encontrado</h3>
        </div>
    </div>
@endif