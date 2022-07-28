@extends('layouts.admin')

@section('title', 'Reunion')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{asset('css/intranet/reunion.css')}}">
    <style>
        .border-right{
            border-right: 1px solid #ccc;
        }
        .area_select{
            width: calc(100% - 42px);
            display: inline-block;
        }
    </style>
@endsection

@section('content')
<div class="modal fade" id="alertModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Alert
                <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <form id="new_results_form" action="{{route('results.store')}}" method="POST" enctype="multipart/form-data" autocomplete="off" onkeydown="return event.key != 'Enter';">
            @csrf
            <div class="card mb-4">
                <div class="card-header">
                    <span>Nueva Reunion</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-4">
                                        <label class="form-label" for="title">Título:</label>
                                        <input class="form-control" type="text" name="title" id="title" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="date">Fecha:</label>
                                        <input class="form-control" type="text" name="date" id="date" value="{{date('d/m/Y',strtotime($date))}}" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="title">Descripción:</label>
                                <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label" for="presenter">Presentadores:</label>
                                <input class="form-control" type="text" id="presenters">
                            </div>
                            <div id="presenters_names" class="mb-4"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-100 text-end mb-3">
                <a href="#" class="btn btn-info text-white" id="addTheme">+ Nuevo Tema</a>
            </div>
            <div id="themes_div" counter="1">
                <div class="theme_elem card mb-4" id="theme1" theme_code="1">
                    <div class="card-header">
                        <span>Tema:</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="mb-2">
                                    <label for="theme1" class="form-label">Nombre de Tema</label>
                                    <input type="text" class="form-control" name="theme[theme1]" required>
                                </div>
                                <div>
                                    <a class="btn btn-secondary text-white newAreaBtn" href="#" target="theme1">+ Nueva Área</a>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="area_docs_list" counter="2">
                                    <div class="area_docs border rounded p-3 mb-2" area-count="1">
                                        <label class="form-label" for="area_name">Área:</label>
                                        <select class="form-select area_select" name="area[theme1][]" id="area_name1">
                                            @foreach ($areas as $area)
                                                <option value="{{$area->id}}">{{$area->nombre}}</option>
                                            @endforeach
                                        </select>
                                        <hr>
                                        <label class="form-label">Archivos:</label>
                                        <input type="file" class="form-control not-filled mb-2" name="files[theme1][area1][]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <button form="new_results_form" class="btn btn-success text-white">Crear Reunión</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script>
    let usernames_route = "{{route('user.name_list')}}";
</script>
<script src="{{asset("js/intranet/reunion_create.js")}}"></script>
<script>
    areas = JSON.parse('{!! json_encode($areas_arr) !!}');
</script>
@endsection