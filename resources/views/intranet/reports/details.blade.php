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