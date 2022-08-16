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
            <div class="modal-header">
                <h5>Generar Reporte</h5>
            </div>
            <div class="modal-body">
                <form action="{{route('agenda.tracking.pdf')}}" method="post" target="_blank" id="report_pdf">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-2">
                                <label class="form-label">Estado:</label>
                                <select class="form-select" name="status">
                                    <option value="ALL">Todos</option>
                                    <option value="1">No Terminado</option>
                                    <option value="2">Terminado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-2">
                                <label class="form-label">Sede:</label>
                                <select class="form-select" name="branch">
                                    <option value="ALL">Todos</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{$branch->id}}">{{$branch->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group mb-2">
                                <label class="form-label" for="searchFrom">Buscar desde:</label>
                                <div class="input-group">
                                    <input id="search_from" class="form-control" type="text" name="search_from" value="{{date('d/m/Y', strtotime("-1 month"))}}" required>
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
                                <label class="form-label" for="searchTo">Buscar hasta:</label>
                                <div class="input-group">
                                    <input id="search_to" class="form-control" type="text" name="search_to" value="{{date('d/m/Y', strtotime("now"))}}" required>
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
                <input id="search_report" class="btn btn-success text-white" type="submit" form="report_pdf" value="Buscar">
            </div>
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-4">
            <div class="card-body">
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
                                <th class="th-user bg-dark text-white" width="120px">Usuario</th>
                                <th class="th-travel-from bg-dark text-white" width="120px">Viaje Desde</th>
                                <th class="th-travel-to bg-dark text-white" width="120px">Viaje Hasta</th>
                                <th class="th-activity bg-dark text-white">Actividad</th>
                                <th class="th-deal bg-dark text-white">Acuerdo</th>
                                {{-- <th class="bg-dark text-white" width="100px">Desde</th> --}}
                                <th class="th-to bg-dark text-white" width="120px">Fecha Fin</th>
                                <th class="th-to bg-dark text-white" width="140px">Estado</th>
                                <th class="th-actions bg-dark text-white" width="100px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activities as $activity)
                            <tr class="act-row" data-actid="{{$activity->id}}">
                                <td class="t-branch align-middle">{{$activity->travelSchedule->branch->nombre}}</td>
                                <td class="t-user align-middle">{{$activity->travelSchedule->user->nombre}}</td>
                                <td class="t-travel-from align-middle">{{date("d-m-y",strtotime($activity->travelSchedule->viaje_comienzo))}}</td>
                                <td class="t-travel-to align-middle">{{date("d-m-y",strtotime($activity->travelSchedule->viaje_fin))}}</td>
                                <td class="t-activity align-middle">{{$activity->descripcion}}</td>
                                <td class="t-deal align-middle">{{$activity->acuerdo}}</td>
                                {{-- <td class="align-middle">{{date("d-m-Y", strtotime($activity->fecha_comienzo))}}</td> --}}
                                <td class="t-to align-middle">{{date("d-m-Y", strtotime($activity->fecha_fin))}}</td>
                                <td class="t-deal align-middle {{$activity->estado=='1'?'text-danger':'text-success'}}">{{$activity->estado=='1'?'NO TERMINADO':'TERMINADO'}}</td>
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
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
<script src="{{asset("js/intranet/tracking.js")}}"></script>
<script>
    var pop_activity_route = "{{route('agenda.tracking.popup')}}";
    var update_route = "{{route('agenda.tracking.update')}}";
    var close_route = "{{route('agenda.tracking.close')}}";
</script>
@endsection