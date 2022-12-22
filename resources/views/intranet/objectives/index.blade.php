@extends('layouts.admin')

@section('title', 'Objetivos Estratégicos')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="{{asset('css/intranet/objectives.css')}}">
@endsection

@section('content')
<div class="body flex-grow-1 px-3">
    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-row flex-wrap">
                    <div class="p-1">
                        <a href="#" class="btn btn-success text-white">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-plus"></use>
                            </svg> Nuevo Item
                        </a>
                    </div>
                    <div class="p-1">
                        <button type="button" class="btn btn-secondary text-white" data-coreui-toggle="modal" data-coreui-target="#filterModal">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-filter"></use>
                            </svg> Filtrar
                        </button>
                    </div>
                    <div class="p-1">
                        {{-- <button type="button" class="btn btn-secondary text-white" data-coreui-toggle="modal" data-coreui-target="#filterModal">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-filter"></use>
                            </svg> Filtrar
                        </button> --}}
                        <a href="#" class="btn btn-danger text-white">
                            <i class="fa-regular fa-file-pdf"></i> Exportar a PDF
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
        <div id="matrix_content">
            @foreach ($pilars as $pilar)
            <div class="pilar">
                <div class="pilar-header">
                    <span class="icon-btn" style="padding-right: 0;" data-coreui-target="#collapsePilar-{{$pilar->id}}" data-coreui-toggle="collapse" aria-coreui-expanded="true">
                        <i class="fa-solid fa-chevron-down"></i>
                    </span>&nbsp;
                    <span class="pilar-name">{{mb_strtoupper($pilar->nombre)}}</span>&nbsp;
                    <span class="icon-hover icon-info"><i class="fa-solid fa-circle-info"></i></span>&nbsp;
                    {{-- <span><i class="fa-regular fa-lightbulb"></i></span>&nbsp; --}}
                    <span class="icon-btn switch-view" view="general"><i class="fa-regular fa-eye"></i></span>
                </div>
                <div class="pilar-body pilar-{{$pilar->id}} collapse show" id="collapsePilar-{{$pilar->id}}" pilar="{{$pilar->id}}">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let matrixUrl = "{{route('objectives.matrix')}}";
</script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
<script src="{{asset("js/intranet/objectives.js")}}"></script>
<script>
    
</script>

@endsection