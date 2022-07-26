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
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-4">
            <div class="card-header">
                <span>Nueva Reunion</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-4">
                            <label class="form-label" for="title">Título:</label>
                            <input class="form-control" type="text" name="title" id="title" value="" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="title">Descripción:</label>
                            <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
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
        <div class="card">
            <div class="card-header">
                <span>Nuevo Tema:</span>
            </div>
            <div class="card-body">
                <div id="themes_div">
                    <div class="theme_elem">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="mb-4">
                                    <label for="theme1" class="form-label">Nombre de Tema</label>
                                    <input type="text" class="form-control" name="theme[]" id="theme1" required>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="area_docs border rounded p-3">
                                    <label class="form-label" for="area_name">Área:</label>
                                    <select class="form-select area_select" name="area[]" id="area_name1">
                                        <option value="4">Administración</option>
                                        <option value="5">Finanzas</option>
                                    </select>
                                    <hr>
                                    <label class="form-label" for="area_name">Archivos:</label>
                                    <input type="file" class="form-control" name="file1" id="file1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection