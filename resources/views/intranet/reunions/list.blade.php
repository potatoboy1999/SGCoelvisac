@extends('layouts.admin')

@section('title', 'Reuniones')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{asset('css/intranet/reunion.css')}}">
    <style>
        
    </style>
@endsection

@section('content')
<div class="modal fade" id="reunionModal" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-4">
            <div class="card-body">
                <div>
                    <p>Próximas Reuniones</p>
                </div>
                @php
                    $upcoming = $reunions->filter(function($value, $key){
                        $reu_time = strtotime($value->fecha);
                        $now = strtotime("now");
                        return ($now <= $reu_time);
                    });
                @endphp
                @if (sizeof($upcoming) > 0)
                    <div class="mb-3 overflow-auto">
                        <table id="reunions_tbl" class="table table-bordered m-0 cell-border">
                            <thead>
                                <tr>
                                    <th class="h-date bg-dark text-white" width="150">Fecha</th>
                                    <th class="h-title bg-dark text-white">Título</th>
                                    <th class="h-actions bg-dark text-white text-center" width="150">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($upcoming as $reunion)
                                <tr class="row-reunion" reunionid="{{$reunion->id}}">
                                    <td class="d-date align-middle">{{date('d/m/Y', strtotime($reunion->fecha))}}</td>
                                    <td class="d-title align-middle">{{$reunion->titulo}}</td>
                                    <td class="d-actions align-middle text-center">
                                        <a href="{{route('results.modify')}}?id={{$reunion->id}}" class="text-white btn btn-info btn-sm btn-edit">
                                            <svg class="icon">
                                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-pencil"></use>
                                            </svg>
                                        </a>
                                        <a href="#" class="text-white btn btn-danger btn-sm btn-remove" reunionid="{{$reunion->id}}">
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
                @else
                    <div class="rounded bordered p-3 bg-light center mb-3">
                        <p class="m-0 text-center">No hay reuniones próximas</p>
                    </div>
                @endif
                <div>
                    <p>Reuniones Pasadas</p>
                </div>
                @php
                    $past = $reunions->filter(function($value, $key){
                        $reu_time = strtotime($value->fecha);
                        $now = strtotime("now");
                        return ($now > $reu_time);
                    });
                @endphp
                @if (sizeof($past) > 0)
                    <div class="mb-3 overflow-auto">
                        <table id="reunions_tbl" class="table table-bordered m-0 cell-border">
                            <thead>
                                <tr>
                                    <th class="h-date bg-dark text-white" width="150">Fecha</th>
                                    <th class="h-title bg-dark text-white">Título</th>
                                    <th class="h-actions bg-dark text-white text-center" width="150">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($past as $reunion)
                                <tr class="row-reunion" reunionid="{{$reunion->id}}">
                                    <td class="d-date align-middle">{{date('d/m/Y', strtotime($reunion->fecha))}}</td>
                                    <td class="d-title align-middle">{{$reunion->titulo}}</td>
                                    <td class="d-actions align-middle text-center">
                                        <a href="{{route('results.modify')}}?id={{$reunion->id}}" class="text-white btn btn-info btn-sm btn-edit">
                                            <svg class="icon">
                                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-pencil"></use>
                                            </svg>
                                        </a>
                                        <a href="#" class="text-white btn btn-danger btn-sm btn-remove" reunionid="{{$reunion->id}}">
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
                @else
                    <div class="rounded bordered p-3 bg-light center mb-3">
                        <p class="m-0 text-center">No hay reuniones pasadas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="{{asset("js/intranet/reunion.js")}}"></script>
@endsection