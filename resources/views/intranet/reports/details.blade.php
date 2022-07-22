@php
    function progressStatus($activity){
        $status = 0; // not done = RED
        if($activity->estado == 2){
            $status = 2; // done = GREEN
        }else{
            $today = time();
            $d_start = strtotime($activity->fecha_comienzo);
            $d_end = strtotime($activity->fecha_fin);
            if($d_start <= $today && $today <= $d_end){
                // calculate 25% of time remaining
                $diff = ($d_end - $d_start)*0.25;
                $d_limit = $d_start + $diff;

                if($today < $d_limit){
                    $status = 2; // if today is within 25% of start, status OK = GREEN
                }
                
                if($d_limit <= $today){
                    $status = 1; // if today is past 25%, status warning = YELLOW
                }

            }else if($d_end < $today){
                $status = 0; // time expired, not done = RED
            }
        }
        return $status;
    }
@endphp

@extends('layouts.admin')

@section('title', 'Reportes')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="{{asset("css/intranet/reports.css")}}" />
    <style>
        td.t_red {
            background-color: #ec1d1d;
        }
        td.t_green {
            background-color: #12c212;
        }
        td.t_yellow {
            background-color: #f9e715;
        }
    </style>
@endsection

@section('content')
<div class="modal fade" id="modalActivity" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="modal fade" id="confirmFinalVersion" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="m-0 text-center">Guardar como version final</h5>
            </div>
            <div class="modal-body">
                <div class="modal-area modal-loading" style="display: none">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
                <div class="modal-area modal-text">
                    <p>¿Estás seguro de guardar esta actividad?</p>
                    <p>No podrá realizar más modificaciones a la lista de actividades realizadas</p>
                </div>
            </div>
            <div class="modal-footer">
                @csrf
                <a href="#" id="confirmFinalBtn" class="btn btn-danger text-white btn-actions" data-id="{{$schedule->id}}">Si, Guardar</a>
                <a href="#" class="btn btn-secondary text-white btn-actions" data-coreui-dismiss="modal">No, Cancelar</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteActivity" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="m-0 text-center">Eliminar actividad</h5>
            </div>
            <div class="modal-body">
                <div class="modal-area modal-loading" style="display: none">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
                <div class="modal-area modal-text">
                    <p>¿Estás seguro de eliminar esta actividad?</p>
                </div>
            </div>
            <div class="modal-footer">
                @csrf
                <a href="#" id="dltActivity" class="btn btn-danger text-white btn-actions" activity-id="">Si, Eliminar</a>
                <a href="#" class="btn btn-secondary text-white btn-actions" data-coreui-dismiss="modal">No, Cancelar</a>
            </div>
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-3">
            <div class="card-header">
                Agenda de Viaje #{{$schedule->id}}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-2">
                            <p class="m-0 border-bottom "><strong>Área</strong></p>
                            <p class="">{{$schedule->user->position->area->nombre}}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-2">
                            <p class="m-0 border-bottom "><strong>Nombre</strong></p>
                            <p class="">{{$schedule->user->nombre}}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-2">
                            <p class="m-0 border-bottom "><strong>Puesto / Cargo</strong></p>
                            <p class="">{{$schedule->user->position->nombre}}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-2">
                            <p class="m-0 border-bottom "><strong>Sede visitada</strong></p>
                            <p class="">{{$schedule->branch->nombre}}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <p class="m-0 border-bottom "><strong>Fecha Desde</strong></p>
                            <p class="">{{date('d/m/Y', strtotime($schedule->viaje_comienzo))}}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <p class="m-0 border-bottom "><strong>Fecha Hasta</strong></p>
                            <p class="">{{date('d/m/Y', strtotime($schedule->viaje_fin))}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                Informe del viaje
            </div>
            <div class="card-body">
                <div class="row">
                    @if ($schedule->finalizado == 0)
                    <div class="col-12">
                        <div class="float-end">
                            <a href="#" class="btn btn-info text-white new_activity" data-target="rep_activities" data-type="1" travelid="{{$schedule->id}}">+ Nueva Actividad</a>
                        </div>
                        <p class="m-0 mt-2">Actividades Realizadas</p>
                    </div>
                    @endif

                    @if (($schedule->finalizado == 1 && sizeof($schedule->reportActivities->where('tipo', 1)) > 0) || $schedule->finalizado == 0 )
                    <div class="col-12">
                        <div class="overflow-auto mt-2 mb-4">
                            <table id="rep_activities" class="table table-bordered activity-table m-0" data-type="1">
                                <thead>
                                    <tr>
                                        <th class="bg-dark text-white h-description" width="250">Descripción</th>
                                        <th class="bg-dark text-white h-deal" width="250">Acciones propuestas / Acuerdos</th>
                                        <th class="bg-dark text-white h-start" width="100">Desde</th>
                                        <th class="bg-dark text-white h-end" width="100">Hasta</th>
                                        <th class="bg-dark text-white h-status" width="50">Estado</th>
                                        @if ($schedule->finalizado == 0)
                                        <th class="bg-dark text-white h-action" width="75"></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($schedule->reportActivities->where('tipo', 1) as $activity)
                                    <tr class="rep-act" act-id="{{$activity->id}}">
                                        <td class="d-description align-middle">{{$activity->descripcion}}</td>
                                        <td class="d-deal align-middle">{{$activity->acuerdo}}</td>
                                        <td class="d-start align-middle">{{date('d/m/Y', strtotime($activity->fecha_comienzo))}}</td>
                                        <td class="d-end align-middle">{{date('d/m/Y', strtotime($activity->fecha_fin))}}</td>
                                        @php
                                            $s = ['t_red','t_yellow','t_green'];
                                        @endphp
                                        <td class="d-status align-middle {{ $s[progressStatus($activity)] }}"></td>
                                        @if ($schedule->finalizado == 0)
                                        <td class="d-action align-middle text-center">
                                            <a href="#" class="btn btn-info btn-sm text-white btn-edit" data-id="{{$activity->id}}" data-type="{{$activity->tipo}}" travelid="{{$schedule->id}}">
                                                <svg class="icon">
                                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-pencil"></use>
                                                </svg>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-sm text-white btn-delete" data-id="{{$activity->id}}">
                                                <svg class="icon">
                                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-trash"></use>
                                                </svg>
                                            </a>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if ($schedule->finalizado == 0)
                    <div class="col-12">
                        <div class="float-end">
                            <a href="#" class="btn btn-info text-white new_activity" data-target="rep_activities_others" data-type="2" travelid="{{$schedule->id}}">+ Nueva Actividad</a>
                        </div>
                        <p class="m-0 mt-2">Otras Actividades</p>
                    </div>
                    @endif

                    @if (($schedule->finalizado == 1 && sizeof($schedule->reportActivities->where('tipo', 2)) > 0) || $schedule->finalizado == 0 )
                    <div class="col-12">
                        <div class="overflow-auto mt-2">
                            <table id="rep_activities_others" class="table table-bordered activity-table m-0" data-type="2">
                                <thead>
                                    <tr>
                                        <th class="bg-dark text-white h-description" width="250">Descripción</th>
                                        <th class="bg-dark text-white h-deal" width="250">Acciones propuestas / Acuerdos</th>
                                        <th class="bg-dark text-white h-start" width="100">Desde</th>
                                        <th class="bg-dark text-white h-end" width="100">Hasta</th>
                                        <th class="bg-dark text-white h-status" width="50">Estado</th>
                                        @if ($schedule->finalizado == 0)
                                        <th class="bg-dark text-white h-action" width="75"></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($schedule->reportActivities->where('tipo', 2) as $activity)
                                    <tr class="rep-act" act-id="{{$activity->id}}">
                                        <td class="d-description align-middle">{{$activity->descripcion}}</td>
                                        <td class="d-deal align-middle">{{$activity->acuerdo}}</td>
                                        <td class="d-start align-middle">{{date('d/m/Y', strtotime($activity->fecha_comienzo))}}</td>
                                        <td class="d-end align-middle">{{date('d/m/Y', strtotime($activity->fecha_fin))}}</td>
                                        <td class="d-status align-middle">{{$activity->estado}}</td>
                                        @if ($schedule->finalizado == 0)
                                        <td class="d-action align-middle text-center">
                                            <a href="#" class="btn btn-info btn-sm text-white btn-edit" data-id="{{$activity->id}}" data-type="{{$activity->tipo}}" travelid="{{$schedule->id}}">
                                                <svg class="icon">
                                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-pencil"></use>
                                                </svg>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-sm text-white btn-delete" data-id="{{$activity->id}}">
                                                <svg class="icon">
                                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-trash"></use>
                                                </svg>
                                            </a>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        @if ($schedule->finalizado == 0)
                        <a href="#" id="saveFinalBtn" class="btn btn-success text-white">
                            <i class="fa-solid fa-lock"></i> Guardar como versión final
                        </a>
                        @else    
                        <a href="{{route('agenda.reports.pdf')}}?id={{$schedule->id}}" class="btn btn-info text-white">
                            <i class="fa-regular fa-file-pdf"></i> Exportar a PDF
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
<script src="{{asset("js/intranet/reports.js")}}"></script>
<script>
    var activity_modal = "{{route('agenda.reports.activity.popup')}}";
    var delete_route = "{{route('agenda.reports.activity.delete')}}";
    var asset_url = "{{asset('icons/sprites/free.svg')}}";
    var finalize_url = "{{route('agenda.reports.finalize')}}";
</script>
@endsection