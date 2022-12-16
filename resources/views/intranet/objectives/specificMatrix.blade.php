<div class="card mb-4">
@if ($status == "ok")
    <div class="card-body p-0">
        <table class="table table-bordered m-0">
            <thead>
                <tr>
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
                @foreach ($specifics as $spec)
                    <?php 
                        $kpis = $spec->kpis;
                        $k = 0;
                    ?>
                    @foreach ($kpis as $kpi)
                        <tr>
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
                            <td class="align-middle" align="center"></td>
                            <td class="align-middle" align="center"></td>
                            <td class="align-middle" align="center"><span class="badge bg-secondary btn-more">...</span></td>
                        </tr>
                        <?php $k++; ?>
                    @endforeach
                    <?php $y++; ?>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="card-body">
        <h3 class="text-danger">Objetivo no encontrado</h3>
    </div>
@endif