@extends('layouts.admin')

@section('title', 'Pendientes')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{asset('css/intranet/pending.css')}}">
    <style>
        .nav-item-custom {
            border: 1px solid rgba(86, 61, 124, .2) !important;
        }
    </style>
@endsection

@section('content')
<div class="modal fade" id="scheduleModal" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5>Agendas Pendientes</h5></div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        {{-- if area is ADMIN, show both options --}}
        @if (Auth::user()->position->area->id == 1)
            <div class="card mb-4">
                <div class="card-body">
                    <div class="nav nav-pills nav-fill">
                        <a href="{{route('agenda.pending')}}?type=1" class="nav-item nav-link nav-item-custom mx-2 {{$type == 1?'active':''}}">1era Validacion</a>
                        <a href="{{route('agenda.pending')}}?type=2" class="nav-item nav-link nav-item-custom mx-2 {{$type == 2?'active':''}}">2da Validacion</a>
                    </div>
                </div>
            </div>
        @endif
        <div class="card mb-4">
            <div class="card-header"><span>Pendientes</span></div>
            <div class="card-body">
                <div class="overflow-auto">
                    <table class="table table-bordered m-0">
                        <thead>
                            <tr>
                                <th class="bg-dark text-white" width="50px">Creado</th>
                                <th class="bg-dark text-white" width="80px">Usuario</th>
                                <th class="bg-dark text-white" width="150px">Sede</th>
                                <th class="bg-dark text-white" width="50px">Desde</th>
                                <th class="bg-dark text-white" width="50px">Hasta</th>
                                <th class="bg-dark text-white" width="50px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $schedule)
                            <tr data-travelid="{{$schedule->id}}">
                                <td>{{date("d-m-Y", strtotime($schedule->created_at))}}</td>
                                <td>{{$schedule->user->nombre}}</td>
                                <td>{{$schedule->branch->nombre}}</td>
                                <td>{{date("d-m-Y", strtotime($schedule->viaje_comienzo))}}</td>
                                <td>{{date("d-m-Y", strtotime($schedule->viaje_fin))}}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info show-schedule" data-travelid="{{$schedule->id}}" data-action="{{$type == 1?'3':'4'}}">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-check"></use>
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
    var pop_schedule_route = "{{route('agenda.popup.schedule')}}";
    var confirm_route = "{{route('agenda.confirm')}}";
    var deny_route = "{{route('agenda.deny')}}";
</script>
@endsection