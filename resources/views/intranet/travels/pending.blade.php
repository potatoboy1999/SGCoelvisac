@extends('layouts.admin')

@section('title', 'Pendientes')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{asset('css/intranet/objectives.css')}}">
    <style>
    </style>
@endsection

@section('content')
<div class="modal fade" id="newScheduleModal" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5>Agendas Pendientes</h5></div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-4">
            <div class="card-header"><span>Pendientes</span></div>
            <div class="card-body">
                <div class="overflow-auto">
                    <table class="table table-bordered m-0">
                        <thead>
                            <tr>
                                <th>Creado</th>
                                <th>Usuario</th>
                                <th>Sede</th>
                                <th>Desde</th>
                                <th>Hasta</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $schedule)
                            <tr>
                                <td>{{date("d-m-Y", strtotime($schedule->created_at))}}</td>
                                <td>{{$schedule->user->nombre}}</td>
                                <td>{{$schedule->branch->nombre}}</td>
                                <td>{{date("d-m-Y", strtotime($schedule->viaje_comienzo))}}</td>
                                <td>{{date("d-m-Y", strtotime($schedule->viaje_fin))}}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-info"></use>
                                        </svg>
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
<script src="{{asset("js/intranet/pendings.js")}}"></script>
<script>
</script>
@endsection