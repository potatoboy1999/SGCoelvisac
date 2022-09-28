@php
    function progressStatus($activity){
        // ['t_red','t_gray','t_blue','t_yellow','t_green'];
        $status = 0; // not done = RED
        if($activity->estado == 1){
            $status = 1; // not started = GRAY
        }elseif($activity->estado == 3){
            $status = 2; // done = BLUE
        }elseif($activity->estado == 4){
            $status = 0; // not done = RED
        }else{
            $status = 4; // working on it = GREEN
            $today = time();
            $d_start = strtotime($activity->fecha_comienzo);
            $d_end = strtotime($activity->fecha_fin);
            if($d_start <= $today && $today <= $d_end){
                // calculate 25% of time remaining
                $diff = ($d_end - $d_start)*0.25;
                $d_limit = $d_start + $diff;

                if($today < $d_limit){
                    $status = 4; // if today is within 25% of start, status OK = GREEN
                }
                
                if($d_limit <= $today){
                    $status = 3; // if today is past 25%, status warning = YELLOW
                }

            }else if($d_end < $today){
                $status = 0; // time expired, not done = RED
            }
        }
        return $status;
    }
@endphp

@extends('layouts.admin')

@section('title', 'Seguimiento')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="{{asset('css/intranet/tracking.css')}}">
    <style>
        .nav-item-custom {
            border: 1px solid rgba(86, 61, 124, .2) !important;
        }
        .p-fa{
            padding-right: 10px;
            padding-left: 10px;
        }
        .clear-readonly{
            background-color: white!important;
        }
        #ui-datepicker-div {
            z-index: 10000!important;
        }
        .t_gray {
            background-color: #d8dbe0!important;
        }
        .t_blue {
            background-color: #256ae2!important;
        }
        .t_red {
            background-color: #ec1d1d!important;
        }
        .t_green {
            background-color: #12c212!important;
        }
        .t_yellow {
            background-color: #f9e715!important;
        }
        .ui-autocomplete{
            z-index: 100000!important;
        }
    </style>
@endsection

@section('content')
<div class="modal fade" id="trackingModal" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5>Seguimiento de Actividades</h5></div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="trackingCloseModal" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Cerrar Actividad</h5>
            </div>
            <div class="modal-body">
                <div class="modal-area modal-loading" style="display: none">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
                <div class="modal-area modal-form">
                    @csrf
                    <p><strong>¿Está seguro que quiere cerrar esta actividad?</strong><br>No podrá hacer más modificaciones a las fechas o estado de la actividad</p>
                </div>
                <div class="modal-area modal-success" style="display: none">
                    <p class="m-0">
                        <span class="text-success"><strong>!ÉXITO!</strong></span>
                        <br> La actividad fue cerrada
                    </p>
                </div>
                <div class="modal-area modal-error" style="display: none">
                    <p class="m-0"><span class="text-success"><strong>!ERROR!</strong></span></p>
                    <p class="m-0" id="err_msg"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary text-white btn-action btn-cancel" type="button" data-coreui-dismiss="modal" aria-label="Close">Cancelar</button>
                <a href="#" class="btn btn-danger text-white btn-action" id="confirm-close" actid="">Cerrar Actividad</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="trackingReportModal" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="modal fade" id="trackingFilterModal" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Generar Reporte</h5>
            </div>
            <div class="modal-body">
                <form action="{{route('agenda.tracking')}}" method="get" id="filter_pdf" onkeydown="return event.key != 'Enter';">
                    <input type="hidden" name="search" value="Y">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-2">
                                <label class="form-label">Sede:</label>
                                <div class="p-2 border rounded">
                                    @foreach ($branches as $branch)
                                        <div class="form-check">
                                            <input class="form-check-input" id="branch_check{{$branch->id}}" name="branches[]" value="{{$branch->id}}" type="checkbox" {{$filter['active'] == 'Y'?(in_array($branch->id,$filter['branches'])?'checked':''):'checked'}}>
                                            <label class="form-check-label" for="branch_check{{$branch->id}}">{{$branch->nombre}}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="w-100">
                                <p class="branch_error w-100 text-danger border border-danger rounded m-0 p-2" style="display: none">Seleccione una o más sedes</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-2">
                                <label class="form-label">Area:</label>
                                <div class="p-2 border rounded">
                                    @foreach ($areas as $area)
                                        <div class="form-check">
                                            <input class="form-check-input" id="area_check{{$area->id}}" name="areas[]" value="{{$area->id}}" type="checkbox" {{$filter['active'] == 'Y'?(in_array($area->id,$filter['areas'])?'checked':''):'checked'}}>
                                            <label class="form-check-label" for="area_check{{$area->id}}">{{$area->nombre}}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="w-100">
                                <p class="area_error w-100 text-danger border border-danger rounded m-0 p-2" style="display: none">Seleccione una o más areas</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group mb-2">
                                <label class="form-label" for="filter_from">Buscar desde:</label>
                                <div class="input-group">
                                    <input id="filter_from" class="form-control clear-readonly" type="text" name="search_from" value="{{$filter['active'] == 'Y'?$filter['date_from']:date('d/m/Y', strtotime("-1 month"))}}" onkeydown="return false;" readonly required>
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-calendar"></use>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group mb-2">
                                <label class="form-label" for="filter_to">Buscar hasta:</label>
                                <div class="input-group">
                                    <input id="filter_to" class="form-control clear-readonly" type="text" name="search_to" value="{{$filter['active'] == 'Y'?$filter['date_to']:date('d/m/Y', strtotime("now"))}}" onkeydown="return false;" readonly required>
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-calendar"></use>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary text-white" type="button" data-coreui-dismiss="modal" aria-label="Close">Cerrar</button>
                <input class="btn btn-success text-white form_submit" type="submit" form="filter_pdf" value="Buscar">
            </div>
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-4">
            <div class="card-body">
                <a href="#" id="filter" class="btn btn-{{$filter['active'] == 'Y'?'warning':'secondary'}} text-white">
                    <i class="fa-solid fa-filter"></i> Filtrar
                </a>
                <a href="#" id="pdf_report" class="btn btn-success text-white">
                    <i class="fa-regular fa-file-pdf"></i> Reporte en PDF
                </a>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header"><span>Seguimiento de Actividades</span></div>
            <div class="card-body">
                <div class="overflow-auto">
                    <table class="table table-bordered m-0">
                        <thead>
                            <tr>
                                <th class="th-branch bg-dark text-white" width="100px">Sede</th>
                                <th class="th-area bg-dark text-white" width="100px">Gerencia</th>
                                <th class="th-user bg-dark text-white" width="120px">Usuario</th>
                                <th class="th-travel-from bg-dark text-white" width="120px">Viaje Desde</th>
                                <th class="th-travel-to bg-dark text-white" width="120px">Viaje Hasta</th>
                                <th class="th-activity bg-dark text-white">Actividad</th>
                                <th class="th-deal bg-dark text-white">Acuerdo</th>
                                {{-- <th class="bg-dark text-white" width="100px">Desde</th> --}}
                                <th class="th-to bg-dark text-white" width="120px">Fecha Fin</th>
                                <th class="th-status bg-dark text-white" width="140px">Estado</th>
                                <th class="th-actions bg-dark text-white" width="100px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $s = ['t_red','t_gray','t_blue','t_yellow','t_green'];
                            @endphp
                            @foreach ($activities as $activity)
                            <tr class="act-row" data-actid="{{$activity->id}}">
                                <td class="t-branch align-middle">{{$activity->travelSchedule->branch->nombre}}</td>
                                <td class="t-area align-middle">{{$activity->travelSchedule->user->position->area->nombre}}</td>
                                <td class="t-user align-middle">{{$activity->travelSchedule->user->nombre}}</td>
                                <td class="t-travel-from align-middle">{{date("d-m-y",strtotime($activity->travelSchedule->viaje_comienzo))}}</td>
                                <td class="t-travel-to align-middle">{{date("d-m-y",strtotime($activity->travelSchedule->viaje_fin))}}</td>
                                <td class="t-activity align-middle">{{$activity->descripcion}}</td>
                                <td class="t-deal align-middle">{{$activity->acuerdo}}</td>
                                {{-- <td class="align-middle">{{date("d-m-Y", strtotime($activity->fecha_comienzo))}}</td> --}}
                                <td class="t-to align-middle">{{date("d-m-Y", strtotime($activity->fecha_fin))}}</td>
                                <td class="t-status align-middle {{ $s[progressStatus($activity)] }}"></td>
                                <td class="t-actions align-middle text-center">
                                    <button class="btn btn-sm btn-info edit-activity" data-actid="{{$activity->id}}">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-pencil"></use>
                                        </svg>
                                    </button>
                                    <button class="btn btn-sm btn-danger p-fa close-activity" data-actid="{{$activity->id}}">
                                        <i class="fa-solid fa-lock"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">Leyenda</div>
            <div class="card-body">
                <p>
                    <span class="d-inline-block text-block t_gray" style="width: 20px;">&nbsp;</span> 
                    <strong>Gris:</strong> Actividad no iniciada
                </p>
                <p>
                    <span class="d-inline-block text-block t_blue" style="width: 20px;">&nbsp;</span> 
                    <strong>Verde:</strong> Actividad iniciada. Desde la fecha de inicio hasta faltando 25% de los días para la fecha de término.
                </p>
                <p>
                    <span class="d-inline-block text-block t_yellow" style="width: 20px;">&nbsp;</span>
                    <strong>Amarillo:</strong> Actividad iniciada. Entre el 25% de los días previo a la fecha de vencimiento hasta la fecha de vencimiento.
                </p>
                <p>
                    <span class="d-inline-block text-block t_green" style="width: 20px;">&nbsp;</span> 
                    <strong>Azul:</strong> Actividad Completada.
                </p>
                <p>
                    <span class="d-inline-block text-block t_red" style="width: 20px;">&nbsp;</span>
                    <strong>Rojo:</strong> Cuando no se haya cumplido la accion y se ha vencido el plazo.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
<script src="{{asset("js/intranet/tracking.js")}}"></script>
<script>
    const report_form_route = "{{route('agenda.tracking.form')}}";
    const pop_activity_route = "{{route('agenda.tracking.popup')}}"; 
    const update_route = "{{route('agenda.tracking.update')}}"; 
    const close_route = "{{route('agenda.tracking.close')}}";
    const usernames_route = "{{route('user.name_list')}}";
</script>
@endsection