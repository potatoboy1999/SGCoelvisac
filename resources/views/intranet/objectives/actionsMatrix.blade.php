@php
    // ['t_red', 't_gray', 't_yellow','t_green','t_blue'];
    function progressStatus($action){
        $status = 0; // not done = RED
        if($action->fecha_final != null){
            $status = 4; // done = BLUE
        }else{
            $status = 1; // Not Started = GRAY
            if($action->estado == 2){ // "En Curso"
                $status = 3; //status OK = GREEN
            }
            $today = time();
            $d_start = strtotime($action->inicio);
            $d_end = strtotime($action->fin);
            if($d_start <= $today && $today <= $d_end){
                // calculate 25% of time remaining
                $diff = ($d_end - $d_start)*0.25;
                $d_limit = $d_start + $diff;

                if($today < $d_limit){
                    $status = 3; // if today is within 25% of start, status OK = GREEN
                }
                
                if($d_limit <= $today){
                    $status = 2; // if today is past 25%, status warning = YELLOW
                }

            }else if($d_end < $today){
                $status = 0; // time expired, not done = RED
            }
        }
        return $status;
    }
@endphp
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
                    @foreach ($strat->actions->where('estado','>=',1) as $action)
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
                            <td class="text-center align-middle t-docs" align="center">
                                @php
                                    $docs = $action->documents;
                                @endphp
                                <a href="javascript:;" class="btn {{sizeof($docs)>0?'btn-success':'btn-secondary'}} btn-sm text-white btn-show-doc" data-route="" data-id="{{$action->id}}">
                                    <svg class="icon">
                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#{{sizeof($docs)>0?'cil-file':'cil-arrow-thick-from-bottom'}}"></use>
                                    </svg>
                                </a>
                            </td>
                            @php
                                $colors = ['t_red', 't_gray', 't_yellow','t_green','t_blue'];
                                $status = ['','No Iniciado', 'En Curso', 'Terminado', 'No terminado'];
                            @endphp
                            <td class="align-middle {{ $colors[progressStatus($action)] }}" align="center">{{$status[$action->estado]}}</td>
                            <td class="align-middle" align="center">
                                @php
                                    $deadline = strtotime($action->fin);
                                    $diff = 0;
                                    if($action->fecha_final != null){
                                        $final_date = strtotime($action->fecha_final);
                                        $diff = $deadline - $final_date;
                                    }else{
                                        $now = time(); // or your date as well
                                        $diff = $deadline - $now;
                                    }
                                    echo round($diff / (60 * 60 * 24));
                                @endphp
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
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="card-body">
            <h3 class="text-danger">Objetivo no encontrado</h3>
        </div>
    @endif