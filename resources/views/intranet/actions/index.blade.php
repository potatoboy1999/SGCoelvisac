@extends('layouts.admin')

@section('title', 'Objetivo Especifico')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="{{asset('css/intranet/specific_actions.css')}}">
@endsection

@section('content')
<div class="modal fade" id="deleteActionModal" tabindex="-1" aria-labelledby="deleteActionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteActionModalLabel">Eliminar Acción<span id="hl-label"></span></h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-section" id="form-delete">
                    <form id="f-form-delete" action="{{route('action.delete')}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="">
                        <div class="row">
                            <div class="col-md-12">
                                <p>
                                    <strong class="text-danger">¡Estas por eliminar una acción!</strong><br>
                                    Estas por eliminar la acción: <br>
                                    <strong><span id="action_dlt_name"></span></strong>
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
<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="actionModalLabel">Nueva Acción<span id="hl-label"></span></h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-section" id="form-action">
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
<div class="modal fade" id="actionEditModal" tabindex="-1" aria-labelledby="actionEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="actionEditModalLabel">Editar Objetivo<span id="hl-label"></span></h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-section" id="form-edit-action">
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
<div class="modal fade" id="docsModal" tabindex="-1" aria-labelledby="docsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="actionModalLabel">Documentos<span id="hl-label"></span></h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-section" id="form-docs-action">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
                <div class="modal-section" id="form-docs-loading" style="display: none">
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
        <h4>OBJETIVO ESPECÍFICO</h4>
        <div class="card mb-4">
            <div class="card-body">
                <p class="m-0">{{$obj->nombre}}</p>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-bordered m-0">
                            <tr>
                                <td class="side-title align-middle" align="center">Pilar</td>
                                <td class="align-middle" align="center">{{$obj->stratObjective->dimension->pilar->nombre}}</td>
                            </tr>
                            <tr>
                                <td class="side-title" align="center">Dimensión</td>
                                <td class="align-middle" align="center">{{$obj->stratObjective->dimension->nombre}}</td>
                            </tr>
                            <tr>
                                <td class="side-title" align="center">Código</td>
                                <td class="align-middle" align="center">{{$obj->codigo}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-row flex-wrap">
                    <div class="p-1">
                        <a href="#" class="btn btn-success text-white btn-new-action" data-coreui-toggle="modal" data-coreui-target="#actionModal">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-plus"></use>
                            </svg> Nueva Acción
                        </a>
                    </div>
                    {{-- <div class="p-1">
                        <button type="button" class="btn btn-secondary text-white" data-coreui-toggle="modal" data-coreui-target="#filterModal">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-filter"></use>
                            </svg> Filtrar
                        </button>
                    </div> --}}
                </div>
            </div>
        </div>
        <div id="matrix_content">
            <div class="spinner-border" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Leyenda</div>
            <div class="card-body">
                <p>
                    <span class="d-inline-block text-block t_gray" style="width: 20px;">&nbsp;</span> 
                    <strong>Gris:</strong> La actividad aun no ha comenzado
                </p>
                <p>
                    <span class="d-inline-block text-block t_green" style="width: 20px;">&nbsp;</span> 
                    <strong>Verde:</strong> Desde la fecha de inicio hasta faltando 25% de los días para la fecha de término.
                </p>
                <p>
                    <span class="d-inline-block text-block t_yellow" style="width: 20px;">&nbsp;</span>
                    <strong>Amarillo:</strong> Entre el 25% de los días previo a la fecha de vencimiento hasta la fecha de vencimiento.
                </p>
                <p>
                    <span class="d-inline-block text-block t_blue" style="width: 20px;">&nbsp;</span> 
                    <strong>Azul:</strong> La actividad ha sido cumplida.
                </p>
                <p>
                    <span class="d-inline-block text-block t_red" style="width: 20px;">&nbsp;</span>
                    <strong>Rojo:</strong> Cuando no se haya cumplido la accion y se ha vencido el plazo.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let objId = "{{$obj->id}}";
    let matrixUrl = "{{route('actions.matrix')}}";
    let newFormUrl = "{{route('action.create')}}";
    let editFormUrl = "{{route('action.edit')}}";
    let docsFormUrl = "{{route('action.popup.docs')}}";
</script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
<script src="{{asset("js/intranet/specific_actions.js")}}"></script>
<script>
    
</script>

@endsection