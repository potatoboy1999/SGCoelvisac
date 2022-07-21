@extends('layouts.admin')

@section('title', 'Reportes')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="{{asset("css/intranet/reports.css")}}" />
@endsection

@section('content')

<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card">
            <div class="card-header">
                Reporte de Agendas
            </div>
            <div class="card-body">
                <div class="overflow-auto">
                    <table id="schedules_tbl" class="table table-bordered m-0 cell-border ">
                        <thead>
                            <tr>
                                <th class="bg-dark text-white align-middle h-created" width="120">Fecha de creación</th>
                                @if (Auth::user()->position->area->id == 1)
                                <th class="bg-dark text-white align-middle h-user" width="50">Usuario</th>
                                @endif
                                <th class="bg-dark text-white align-middle h-branch" width="200">Sede</th>
                                <th class="bg-dark text-white align-middle h-from" width="100">Desde</th>
                                <th class="bg-dark text-white align-middle h-to" width="100">Hasta</th>
                                <th class="bg-dark text-white align-middle h-status no-sort" width="100">Estado</th>
                                {{-- <th class="bg-dark text-white align-middle h-report" width="100">Reporte</th> --}}
                                <th class="bg-dark text-white align-middle h-action no-sort" width="{{Auth::user()->position->area->id == 1?'85':'70'}}"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $schedule)
                            <tr>
                                <td class="d-created align-middle">{{date("d-m-Y", strtotime($schedule->created_at))}}</td>
                                @if (Auth::user()->position->area->id == 1)
                                <td class="d-user align-middle" width="100">{{$schedule->user->nombre}}</td>
                                @endif
                                <td class="d-branch align-middle">{{$schedule->branch->nombre}}</td>
                                <td class="d-from align-middle">{{date("d-m-Y", strtotime($schedule->viaje_comienzo))}}</td>
                                <td class="d-to align-middle">{{date("d-m-Y", strtotime($schedule->viaje_fin))}}</td>
                                <td class="d-status align-middle">
                                    @if ($schedule->estado == 3 || $schedule->estado == 6)
                                        <span class="text-danger">RECHAZADO</span>
                                    @else
                                        @php
                                            $progress = 0;
                                            if($schedule->estado == 1){
                                                $progress += 25;
                                            }
                                            if($schedule->validacion_uno == 2){
                                                $progress += 50;
                                            }
                                            if($schedule->validacion_dos == 2){
                                                $progress += 50;
                                            }
                                        @endphp
                                        <div class="progress">
                                            <div class="progress-bar {{$progress>=100?'bg-success':'bg-warning'}}" role="progressbar" style="width: {{$progress}}%" aria-valuenow="{{$progress}}" aria-valuemin="0" aria-valuemax="100">%{{$progress}}</div>
                                        </div>
                                    @endif
                                </td>
                                {{-- <td class="d-report align-middle {{sizeof($schedule->reportActivities)>0?'text-success':'text-danger'}}">{{sizeof($schedule->reportActivities)>0?'CREADO':'SIN CREAR'}}</td> --}}
                                <td class="d-action align-middle text-center">
                                    @if ($progress >= 100)
                                    <a href="{{route('agenda.reports.show')}}?id={{$schedule->id}}" class="btn btn-success btn-sm text-white btn-view">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-calendar-check"></use>
                                        </svg>
                                    </a>
                                    @endif
                                    <form class="d-inline-block" action="{{route('agenda.reports.deactivate')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$schedule->id}}">
                                        <button class="btn {{$schedule->estado == 0?'btn-warning':'btn-danger'}} btn-sm text-white">
                                            <svg class="icon">
                                                <use xlink:href="{{asset("icons/sprites/free.svg")}}{{$schedule->estado == 0?'#cil-reload':'#cil-trash'}}"></use>
                                            </svg>
                                        </button>
                                    </form>
                                    @if ($progress >= 100)
                                    <a href="{{route('agenda.reports.pdf')}}?id={{$schedule->id}}" class="btn btn-info btn-sm text-white" style="padding: 4px 10px;">
                                        <i class="fa-regular fa-file-pdf"></i>
                                        {{-- <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/brand.svg")}}#cib-adobe-acrobat-reader"></use>
                                        </svg> --}}
                                    </a>
                                    @endif
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    var colDefaultSort = {{Auth::user()->position->area->id == 1?'2':'1'}};
</script>
<script src="{{asset("js/intranet/reports.js")}}"></script>
@endsection