@extends('layouts.admin')

@section('title', 'Posiciones')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{asset('css/intranet/positions.css')}}">
    <style>
    </style>
@endsection

@section('content')
<div class="modal fade" id="newEditModal" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="modal fade" id="deletePositionModal" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">Eliminar Posición</div>
            <div class="modal-body">
                <div class="modal-area modal-loading" style="display: none">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
                <div class="modal-area modal-form">
                    <form id="form_delete" action="{{route('position.delete')}}" method="POST">
                        @csrf
                        <p>¿Estás seguro que quieres eliminar esta posición?</p>
                        <input type="hidden" name="reunion_id" value="">
                    </form>
                </div>
                <div class="modal-area modal-success" style="display: none">
                    <p>¡La posición ha sido eliminada correctamente!</p>
                </div>
                <div class="modal-area modal-error" style="display: none">
                    <p class="text-danger" id="error_msg">Ha ocurrido un error al eliminar la posición</p>
                </div>
            </div>
            <div class="modal-footer">
                <div class="align-end form-btns">
                    <button class="btn btn-secondary text-white" type="button" data-coreui-dismiss="modal" aria-label="Close">Cerrar</button>
                    <button class="btn btn-danger text-white btn-actions" form="form_delete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-4">
            <div class="card-body">
                <button id="newPosition" class="btn btn-success text-white">+ Nueva Posición</button>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                Posiciones
            </div>
            <div class="card-body">
                <div class="mb-3 overflow-auto">
                    <table id="areas_tbl" class="table table-bordered m-0 cell-border">
                        <thead>
                            <tr>
                                <th class="h-name bg-dark text-white">Nombre</th>
                                <th class="h-actions bg-dark text-white text-center" width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($positions as $position)
                            <tr class="row-reunion" reunionid="{{$position->id}}">
                                <td class="d-name align-middle">
                                    <a href="#">{{$position->nombre}}</a>
                                </td>
                                <td class="d-actions align-middle text-center">
                                    <a href="#" class="text-white btn btn-info btn-sm btn-edit" data-id="{{$position->id}}">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-pencil"></use>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-white btn btn-danger btn-sm btn-remove" data-id="{{$position->id}}">
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
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="{{asset("js/intranet/positions.js")}}"></script>
<script>
    var new_popup = "{{route('position.new')}}";
    var edit_popup = "{{route('position.edit')}}";
    var save_new = "{{route('position.save.new')}}";
    var save_edit = "{{route('position.save.update')}}";
</script>
@endsection