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
@endphp

<div class="card mb-4">
@if ($status == "ok")
    <div class="card-body p-0">
        <table class="table table-bordered m-0" pilar="{{$pilar->id}}">
            <thead>
                <tr>
                    <th class="text-center align-middle t-head-dimension" width="50">Dimensión</th>
                    <th class="text-center align-middle t-head-code" width="50">Código</th>
                    <th class="text-center align-middle t-head-objective" width="180">Objetivo Estratégico</th>
                    <th class="text-center align-middle t-head-sponsor" width="50">Sponsor</th>
                    <th class="text-center align-middle t-head-kpi" width="50">KPI</th>
                    <th class="text-center align-middle t-head-formula" width="120">Fórmula</th>
                    <th class="text-center align-middle t-head-frequency" width="50">Frecuencia</th>
                    <th class="text-center align-middle t-head-type" width="50">Tipo</th>
                    <th class="text-center align-middle t-head-goal" width="50">Meta</th>
                    <th class="text-center align-middle t-head-curryear" width="50">2022</th>
                    <th class="text-center align-middle t-head-nextyear" width="50">2023</th>
                    <th class="text-center align-middle t-head-resmes" width="50">Res. Mes</th>
                    <th class="text-center align-middle t-head-resacum" width="50">Res. Acum.</th>
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
                                <td class="align-middle">{{$kpi->formula}}</td>
                                <td class="align-middle">{{$kpi->frecuencia}}</td>
                                <td class="align-middle">{{$kpi->tipo}}</td>
                                <td class="align-middle">{{$kpi->meta}}</td>
                                <td class="align-middle" align="center">0</td>
                                <td class="align-middle" align="center">100</td>
                                <td class="align-middle" align="center">
                                    <div class="dropdown">
                                        <div class="circle c-red" href="#" role="button" data-coreui-toggle="dropdown" aria-expanded="false"></div>
                                        <ul class="dropdown-menu p-2">
                                            <li class="info-pop">
                                                <div class="pop-banner pop-red"></div>
                                                <span><strong>Meta %</strong>: 100%</span><br>
                                                <span><strong>Meta</strong>: 7,484,790.73</span>
                                                <hr>
                                                <span><strong>Real %</strong>: 80%</span><br>
                                                <span><strong>Real</strong>: 6,022,325.34</span>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="align-middle" align="center">
                                    <div class="dropdown">
                                        <div class="circle c-green" href="#" role="button" data-coreui-toggle="dropdown" aria-expanded="false"></div>
                                        <ul class="dropdown-menu p-2">
                                            <li class="info-pop">
                                                <div class="pop-banner pop-green"></div>
                                                <span><strong>Meta %</strong>: 100%</span><br>
                                                <span><strong>Meta</strong>: 7,484,790.73</span>
                                                <hr>
                                                <span><strong>Real %</strong>: 80%</span><br>
                                                <span><strong>Real</strong>: 6,022,325.34</span>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="align-middle" align="center">
                                    <div class="dropdown">
                                        <span class="badge bg-secondary btn-more text-black" href="#" role="button" data-coreui-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </span>
                                        <ul class="dropdown-menu p-0">
                                            <li>
                                                <a class="dropdown-item" href="">
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
@else
    <div class="card-body">
        <h3 class="text-danger">Pilar no encontrado</h3>
    </div>
@endif
</div>