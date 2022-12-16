<div class="card mb-4">
    @if ($status == "ok")
        <div class="card-body p-0">
            <table class="table table-bordered m-0">
                <thead>
                    <tr>
                        <th class="text-center align-middle t-head-code" width="50">Hitos</th>
                        <th class="text-center align-middle t-head-objective" width="180">Acción Específica</th>
                        <th class="text-center align-middle t-head-sponsor" width="50">Responsable</th>
                        <th class="text-center align-middle t-head-kpi" width="50">Inicio</th>
                        <th class="text-center align-middle t-head-curryear" width="50">Fin</th>
                        <th class="text-center align-middle t-head-nextyear" width="50">Archivos</th>
                        <th class="text-center align-middle t-head-resmes" width="50">Estado</th>
                        <th class="text-center align-middle t-head-resacum" width="50">Tiempo para<br>Finalizar</th>
                        <th class="text-center align-middle t-head-actions" width="20"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($strat->actions as $action)
                        <tr>
                            <td class="align-middle" align="center">
                                {{$action->hito}}
                            </td>
                            <td class="align-middle">
                                {{$action->nombre}}
                            </td>
                            <td class="align-middle">
                                {{$strat->area->nombre}}
                            </td>
                            <td class="align-middle" align="center">{{date("d-m-Y",strtotime($action->inicio))}}</td>                            
                            <td class="align-middle" align="center">{{date("d-m-Y",strtotime($action->fin))}}</td>
                            <td class="align-middle" align="center"></td>
                            <td class="align-middle" align="center">{{$action->estado}}</td>
                            <td class="align-middle">0</td>
                            <td class="align-middle" align="center"><span class="badge bg-secondary btn-more">...</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="card-body">
            <h3 class="text-danger">Objetivo no encontrado</h3>
        </div>
    @endif