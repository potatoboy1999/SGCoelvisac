@extends('layouts.admin')

@section('title', 'Objetivos Especificos')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="{{asset('css/intranet/specific.css')}}">
@endsection

@section('content')
<div class="modal fade" id="deleteKpiModal" tabindex="-1" aria-labelledby="deleteKpiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteKpiModalLabel">Eliminar KPI<span id="hl-label"></span></h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-section" id="form-delete">
                    <form id="f-form-delete" action="{{route('kpi.delete')}}" method="post">
                        @csrf
                        <input type="hidden" name="kpi_id" value="">
                        <div class="row">
                            <div class="col-md-12">
                                <p>
                                    <strong class="text-danger">¡Estas por eliminar un KPI!</strong><br>
                                    Estas por eliminar el KPI: <br>
                                    <strong><span id="kpi_dlt_name"></span></strong>
                                </p>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-danger text-white float-end mx-1">Eliminar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-section" id="form-delete-loading" style="display: none">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3">
    <div class="container">
        <h4>OBJETIVOS ESPECÍFICOS</h4>
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-row flex-wrap">
                    {{-- <div class="p-1">
                        <button type="button" class="btn btn-secondary text-white" data-coreui-toggle="modal" data-coreui-target="#filterModal">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-filter"></use>
                            </svg> Filtrar
                        </button>
                    </div> --}}
                    <div class="p-1">
                        <a href="#" class="btn btn-danger text-white switch-view" view="general">
                            <i class="fa-regular fa-eye"></i> Vista
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div id="matrix_content">
            <div class="spinner-border" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let matrixUrl = "{{route('spec_matrix.matrix')}}";
</script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
<script src="{{asset("js/intranet/specific.js")}}"></script>
<script>
    
</script>

@endsection