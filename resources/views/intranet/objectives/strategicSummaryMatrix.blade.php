<div class="card mb-4">
    @if ($status == "ok")
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered m-0">
                    <thead>
                        <tr>
                            <th class="text-center align-middle t-head-kpi" width="50">KPI</th>
                            <th class="text-center align-middle t-head-curryear" width="50">2022</th>
                            <th class="text-center align-middle t-head-nextyear" width="50">2023</th>
                            <th class="text-center align-middle t-head-resmes" width="50">Res. Mes</th>
                            <th class="text-center align-middle t-head-resacum" width="50">Res. Acum.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $y = 0; ?>
                        @foreach ($strat->kpis as $kpi)
                            <tr>
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
                            </tr>
                            <?php $y++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="card-body">
            <h3 class="text-danger">Objetivo no encontrado</h3>
        </div>
    @endif
</div>