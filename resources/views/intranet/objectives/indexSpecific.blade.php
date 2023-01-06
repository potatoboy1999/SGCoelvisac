@extends('layouts.admin')

@section('title', 'Planemiento Estratégico')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="{{asset('css/intranet/specific_objectives.css')}}">
@endsection

@section('content')
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newKpiModalLabel">Comentarios<span id="hl-label"></span></h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="comments_list">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
                <form id="comments_form" action="{{route('obj_comments.store')}}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="objective_id" value="">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group py-1 comm-group" commid="new">
                                <label class="form-label">Nuevo comentario:</label>
                                <textarea id="comm_desc" name="description" class="form-control" rows="2" maxlength="1500" required></textarea>
                            </div>
                        </div>
                    </div>
                    <button id="comm_update" class="btn btn-info text-white" type="submit" src="">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
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
<div class="modal fade" id="newKpiModal" tabindex="-1" aria-labelledby="newKpiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newKpiModalLabel">Nuevo KPI<span id="hl-label"></span></h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="form-kpi">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="objectiveModal" tabindex="-1" aria-labelledby="ObjectiveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="objectiveModalLabel">Nuevo Objetivo<span id="hl-label"></span></h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-section" id="form-objectives">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
                <div class="modal-section" id="form-new-loading" style="display: none">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="objectiveEditModal" tabindex="-1" aria-labelledby="ObjectiveEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="objectiveEditModalLabel">Editar Objetivo<span id="hl-label"></span></h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-section" id="form-edit-objectives">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
                <div class="modal-section" id="form-edit-loading" style="display: none">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="redirectKpiModal" tabindex="-1" aria-labelledby="redirectKpiModalLabel" aria-hidden="true" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="redirectKpiModalLabel">Create Kpi<span id="hl-label"></span></h5>
            </div>
            <div class="modal-body">
                <p>
                    ¡Nuevo Item Creado!<br>
                    Continuar a creación de KPI
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success text-white" id="redirect-kpi" href="{{route('kpi')}}" obj="">Continuar</button>
            </div>
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3">
    <div class="container">
        <h4>PLANEAMIENTO ESTRATÉGICO</h4>
        <div class="card mb-4">
            <div class="card-body">
                <p class="m-0">{{$strat->nombre}}</p>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-bordered m-0">
                            <tr>
                                <td class="side-title align-middle" align="center">Pilar</td>
                                <td class="align-middle" align="center">{{$strat->dimension->pilar->nombre}}</td>
                            </tr>
                            <tr>
                                <td class="side-title" align="center">Dimensión</td>
                                <td class="align-middle" align="center">{{$strat->dimension->nombre}}</td>
                            </tr>
                            <tr>
                                <td class="side-title" align="center">Código</td>
                                <td class="align-middle" align="center">{{$strat->codigo}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div id="matrix_summary" class="col-md-6">
                <div class="spinner-border" role="status">
                    <span class="sr-only"></span>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-row flex-wrap">
                    <div class="p-1">
                        <a href="#" class="btn btn-success text-white btn-new-obj" data-coreui-toggle="modal" data-coreui-target="#objectiveModal">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-plus"></use>
                            </svg> Nuevo Objetivo
                        </a>
                    </div>
                    <div class="p-1">
                        <a href="#" class="btn btn-success text-white btn-new-kpi" data-coreui-toggle="modal" data-coreui-target="#newKpiModal">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-plus"></use>
                            </svg> Nuevo KPI
                        </a>
                    </div>
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
    var obj_form_data = null;
    let stratId = "{{$strat->id}}";
    let matrixUrl = "{{route('specifics.matrix')}}";
    let summMatrixUrl = "{{route('specifics.summarymatrix')}}";
    let newFormUrl = "{{route('specifics.matrix.create')}}";
    let editFormUrl = "{{route('specifics.matrix.edit')}}";
    let redirectKpiUrl = "{{route('kpi.redirect.form')}}";
    let listComment = "{{route('obj_comments.list')}}";
</script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
<script src="{{asset("js/intranet/specific_objectives.js")}}"></script>
<script>
    
</script>

@endsection