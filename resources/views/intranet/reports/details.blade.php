@extends('layouts.admin')

@section('title', 'Reportes')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
    <link rel="stylesheet" href="{{asset("css/intranet/reports.css")}}" />
@endsection

@section('content')
<div class="modal fade" id="modalActivity" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="modal fade" id="deleteActivity" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="text-center">Eliminar actividad</h5>
        </div>
        <div class="modal-content">
            <p>¿Estás seguro de eliminar esta actividad?</p>
        </div>
        <div class="modal-footer">
            <a href="#" id="dltActivity" class="btn btn-danger text-white" activity-id="">Si, Eliminar</a>
            <a href="#" class="btn btn-secondary text-white" data-coreui-dismiss="modal">No, Cancelar</a>
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-3">
            <div class="card-header">
                Agenda de Viaje #{{$schedule->id}}
            </div>
            @php
                $user = Auth::user();
            @endphp
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-2">
                            <p class="m-0 border-bottom "><strong>Área</strong></p>
                            <p class="">{{$user->position->area->nombre}}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-2">
                            <p class="m-0 border-bottom "><strong>Nombre</strong></p>
                            <p class="">{{$user->nombre}}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-2">
                            <p class="m-0 border-bottom "><strong>Puesto / Cargo</strong></p>
                            <p class="">{{$user->position->nombre}}</p>
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
        <div class="card">
            <div class="card-header">
                Informe del viaje
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="float-end">
                            <a href="#" class="btn btn-info new_activity" data-target="rep_activities" data-type="1" travelid="{{$schedule->id}}">+ Nueva Actividad</a>
                        </div>
                        <p class="m-0 mt-2">Actividades Realizadas</p>
                    </div>
                    <div class="col-12">
                        <div class="overflow-auto mt-2 mb-4">
                            <table id="rep_activities" class="table table-bordered activity-table m-0">
                                <thead>
                                    <tr>
                                        <th class="bg-dark text-white h-description" width="250">Descripción</th>
                                        <th class="bg-dark text-white h-deal" width="250">Acciones propuestas / Acuerdos</th>
                                        <th class="bg-dark text-white h-start" width="100">Desde</th>
                                        <th class="bg-dark text-white h-end" width="100">Hasta</th>
                                        <th class="bg-dark text-white h-status" width="50">Estado</th>
                                        <th class="bg-dark text-white h-action" width="75"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($schedule->reportActivities->where('tipo', 1) as $activity)
                                    <tr>
                                        <td class="d-description align-middle">{{$activity->descripcion}}</td>
                                        <td class="d-deal align-middle">{{$activity->acuerdo}}</td>
                                        <td class="d-start align-middle">{{$activity->fecha_comienzo}}</td>
                                        <td class="d-end align-middle">{{$activity->fecha_fin}}</td>
                                        <td class="d-status align-middle">{{$activity->estado}}</td>
                                        <td class="d-action align-middle">
                                            <a href="#" class="btn btn-info btn-sm text-white btn-edit">
                                                <svg class="icon">
                                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-pencil"></use>
                                                </svg>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-sm text-white btn-delete">
                                                <svg class="icon">
                                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-trash"></use>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="float-end">
                            <a href="#" class="btn btn-info new_activity" data-target="rep_activities_others" data-type="2" travelid="{{$schedule->id}}">+ Nueva Actividad</a>
                        </div>
                        <p class="m-0 mt-2">Otras Actividades</p>
                    </div>
                    <div class="col-12">
                        <div class="overflow-auto mt-2">
                            <table id="rep_activities_others" class="table table-bordered activity-table m-0">
                                <thead>
                                    <tr>
                                        <th class="bg-dark text-white h-description" width="250">Descripción</th>
                                        <th class="bg-dark text-white h-deal" width="250">Acciones propuestas / Acuerdos</th>
                                        <th class="bg-dark text-white h-start" width="100">Desde</th>
                                        <th class="bg-dark text-white h-end" width="100">Hasta</th>
                                        <th class="bg-dark text-white h-status" width="50">Estado</th>
                                        <th class="bg-dark text-white h-action" width="75"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($schedule->reportActivities->where('tipo', 2) as $activity)
                                    <tr>
                                        <td class="d-description align-middle">{{$activity->descripcion}}</td>
                                        <td class="d-deal align-middle">{{$activity->acuerdo}}</td>
                                        <td class="d-start align-middle">{{$activity->fecha_comienzo}}</td>
                                        <td class="d-end align-middle">{{$activity->fecha_fin}}</td>
                                        <td class="d-status align-middle">{{$activity->estado}}</td>
                                        <td class="d-action align-middle">
                                            <a href="#" class="btn btn-info btn-sm text-white btn-edit">
                                                <svg class="icon">
                                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-pencil"></use>
                                                </svg>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-sm text-white btn-delete">
                                                <svg class="icon">
                                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-trash"></use>
                                                </svg>
                                            </a>
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
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script src="{{asset("js/intranet/reports.js")}}"></script>
<script>
    var activity_modal = "{{route('agenda.reports.activity.popup')}}";
    var delete_route = "{{route('agenda.reports.activity.delete')}}";
</script>
@endsection