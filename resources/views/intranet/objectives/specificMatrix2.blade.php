<div class="card mb-4">
    <div class="card-body p-0">
        <table class="table table-bordered m-0">
            <thead>
                <tr>
                    <th class="text-center align-middle t-head-code" width="100">Objetivo Estrategico</th>
                    <th class="text-center align-middle t-head-code" width="50">Código</th>
                    <th class="text-center align-middle t-head-objective" width="180">Objetivo Específico</th>
                    <th class="text-center align-middle t-head-sponsor" width="50">Responsable</th>
                    <th class="text-center align-middle t-head-kpi" width="50">KPI</th>
                    <th class="text-center align-middle t-head-curryear" width="50">2022</th>
                    <th class="text-center align-middle t-head-nextyear" width="50">2023</th>
                    <th class="text-center align-middle t-head-resmes" width="50">Res. Mes</th>
                    <th class="text-center align-middle t-head-resacum" width="50">Res. Acum.</th>
                    <th class="text-center align-middle t-head-actions" width="20"></th>
                </tr>
            </thead>
            <tbody>
                <?php $y = 0; ?>
                @foreach ($specObjec as $spec)
                    <?php 
                        $kpis = $spec->kpis;
                        $k = 0;
                    ?>
                    @foreach ($kpis as $kpi)
                        <tr>
                            <td class="align-middle" rowspan="{{sizeOf($kpis)}}" align="center" style="{{($k == 0)?'':'display: none;'}}">
                                {{$spec->stratObjective->nombre}}
                            </td>
                            <td class="align-middle" rowspan="{{sizeOf($kpis)}}" align="center" style="{{($k == 0)?'':'display: none;'}}">
                                <a href="{{route('actions')}}?specific={{$spec->id}}"><span class="badge bg-primary obj-code">{{$spec->codigo}}</span></a>
                            </td>
                            <td class="align-middle" rowspan="{{sizeOf($kpis)}}" style="{{($k == 0)?'':'display: none;'}}">
                                <a href="{{route('actions')}}?specific={{$spec->id}}">{{$spec->nombre}}</a>
                            </td>
                            <td class="align-middle" rowspan="{{sizeOf($kpis)}}" style="{{($k == 0)?'':'display: none;'}}">
                                {{$spec->area->nombre}}
                            </td>
                            <td class="align-middle">{{$kpi->nombre}}</td>                            
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
                    <?php $y++; ?>
                @endforeach
            </tbody>
        </table>
    </div>
</div>