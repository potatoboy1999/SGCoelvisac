@extends('layouts.admin')

@section('title', 'Viajes')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{asset('css/intranet/objectives.css')}}">
    <style>
        .area-travel{
            background:#93cdff;
            cursor: pointer;
        }
        .area-travel:hover {
            background: #246cab;
            color: white;
        }
        .d-week:hover{
            background: #f3f3f3;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
<div class="modal fade" id="newScheduleModal" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Agenda de Viaje</h5>
                <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_schedule" action="{{route('agenda.nuevo')}}" method="POST">
                    @php
                        $user = Auth()->user();
                    @endphp
                    @csrf
                    <input type="hidden" name="user" value="{{$user->id}}">
                    <input type="hidden" name="branch" value="">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="mb-2">
                                <label>Área</label>
                                <input id="sch_area" class="form-control" type="text" value="{{$user->position->area->nombre}}" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-2">
                                <label>Nombre</label>
                                <input id="sch_user" class="form-control" type="text" value="{{$user->nombre}}" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-2">
                                <label>Puesto / Cargo</label>
                                <input id="sch_position" class="form-control" type="text" value="{{$user->position->nombre}}" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-2">
                                <label>Sede visitada</label>
                                <input id="sch_branch_name" class="form-control" type="text" value="" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-2">
                                <label for="">Fecha Desde</label>
                                <input id="sch_date_start" class="form-control" type="text" name="date_start" value="" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-2">
                                <label for="">Fecha Hasta</label>
                                <input id="sch_date_end" class="form-control" type="text" name="date_end" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="my-2" id="sch_activities_list">
                        <div class="card">
                            <div class="card-header">Actividades del área</div>
                            <div class="card-body p-2">
                                <div id="area_act">
                                    <div class="mb-2 d-none">
                                        <label>#1</label>
                                        <textarea name="area_act[]" rows="2" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="text-end text-white">
                                    <a href="#" class="btn btn-secondary btn-sm" type="area">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-plus"></use>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="my-2" id="sch_activities_list">
                        <div class="card">
                            <div class="card-header">Actividades de otras áreas</div>
                            <div class="card-body p-2">
                                <div id="non_area_act p-2">
                                    <div class="mb-2 d-none">
                                        <label>#1</label>
                                        <textarea name="non_area_act[]" rows="2" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="text-end text-white">
                                    <a href="#" class="btn btn-secondary btn-sm add-act" type="non_area">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-plus"></use>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" id="vehicle_check" type="checkbox">
                                <label class="form-check-label" for="vehicle_check">¿Requiere Vehiculo?</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="hab_check" type="checkbox">
                                <label class="form-check-label" for="hab_check">¿Requiere Hospedaje?</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="extras_check" type="checkbox">
                                <label class="form-check-label" for="extras_check">¿Requiere Viáticos?</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary text-white" type="button" data-coreui-dismiss="modal" aria-label="Close">Cancelar</button>
                <input class="btn btn-info text-white" type="submit" form="form_schedule" value="Crear">
            </div>
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-row flex-wrap">
                    <form id="form-area-sel" action="{{route('agenda.index')}}" method="get" class="w-100">
                        <div class="form-group w-100">
                            <label>Año:</label>
                            <div class="input-group d-inline-flex" style="width: calc(100% - 41px);">
                                <input class="form-control" type="number" min="2020" value="{{$year}}" name="year" step="1" onkeydown="return false">
                                <button id="search-year" class="btn btn-secondary" type="submit">
                                    <svg class="icon">
                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-zoom"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="calendar-card" class="card mb-4">
            <div class="card-header"><span>Viajes</span></div>
            <div class="card-body">
                <div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="{{asset("js/intranet/travels.js")}}"></script>
<script>
    var calendar_route = "{{route('agenda.calendar')}}";
    $(function(){
        getCalendar();
    });

</script>
@endsection