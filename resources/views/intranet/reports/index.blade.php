@extends('layouts.admin')

@section('title', 'Reportes')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
    <link rel="stylesheet" href="{{asset("css/intranet/reports.css")}}" />
@endsection

@section('content')

<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card">
            <div class="card-header">
                Reportes
            </div>
            <div class="card-body">
                <div class="overflow-auto">
                    <table class="table table-bordered m-0">
                        <thead>
                            <tr>
                                <th class="bg-dark text-white h-created" width="100">Creado</th>
                                <th class="bg-dark text-white h-branch" width="200">Sede</th>
                                <th class="bg-dark text-white h-from" width="100">Desde</th>
                                <th class="bg-dark text-white h-to" width="100">Hasta</th>
                                <th class="bg-dark text-white h-status" width="100">Estado</th>
                                <th class="bg-dark text-white h-action" width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $schedule)
                            <tr>
                                <td class="d-created align-middle">{{date("d-m-Y", strtotime($schedule->created_at))}}</td>
                                <td class="d-branch">{{$schedule->branch->nombre}}</td>
                                <td class="d-from">{{date("d-m-Y", strtotime($schedule->viaje_comienzo))}}</td>
                                <td class="d-to">{{date("d-m-Y", strtotime($schedule->viaje_fin))}}</td>
                                <td class="d-status">
                                    @if ($schedule->estado == 3 || $schedule->estado == 6)
                                        <span class="text-danger">RECHAZADO</span>
                                    @else
                                        @php
                                            $progress = 0;
                                            if($schedule->validacion_uno == 2){
                                                $progress += 50;
                                            }
                                            if($schedule->validacion_dos == 2){
                                                $progress += 50;
                                            }
                                        @endphp
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{$progress}}%" aria-valuenow="{{$progress}}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    @endif
                                </td>
                                <td class="d-action text-center">
                                    <a href="{{route('agenda.reports.show')}}?id={{$schedule->id}}" class="btn btn-success btn-sm text-white btn-view">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-calendar-check"></use>
                                        </svg>
                                    </a>
                                    <form class="d-inline-block" action="{{route('agenda.reports.deactivate')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$schedule->id}}">
                                        <button class="btn {{$schedule->estado == 0?'btn-warning':'btn-danger'}} btn-sm text-white">
                                            <svg class="icon">
                                                <use xlink:href="{{asset("icons/sprites/free.svg")}}{{$schedule->estado == 0?'#cil-reload':'#cil-trash'}}"></use>
                                            </svg>
                                        </button>
                                    </form>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script src="{{asset("js/intranet/reports.js")}}"></script>
@endsection