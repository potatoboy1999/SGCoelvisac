@extends('layouts.admin')

@section('title', 'Areas')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{asset('css/intranet/areas.css')}}">
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
<div class="modal fade" id="deleteAreaModal" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">Eliminar Area</div>
            <div class="modal-body">
                <div class="modal-area modal-loading" style="display: none">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
                <div class="modal-area modal-form">
                    <form id="form_delete" action="{{route('branches.delete')}}" method="POST">
                        @csrf
                        <p>¿Estás seguro que quieres eliminar esta area?</p>
                        <input type="hidden" name="reunion_id" value="">
                    </form>
                </div>
                <div class="modal-area modal-success" style="display: none">
                    <p>¡El area ha sido eliminada correctamente!</p>
                </div>
                <div class="modal-area modal-error" style="display: none">
                    <p class="text-danger" id="error_msg">Ha ocurrido un error al eliminar el area</p>
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
                <button id="newBranch" class="btn btn-success text-white">+ Nueva Área</button>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                Área
            </div>
            <div class="card-body">
                <div class="mb-3 overflow-auto">
                    <table id="areas_tbl" class="table table-bordered m-0 cell-border">
                        <thead>
                            <tr>
                                <th class="h-name bg-dark text-white">Nombre</th>
                                {{-- <th class="h-visible bg-dark text-white" width="100">Visible</th> --}}
                                <th class="h-positions bg-dark text-white text-center" width="100">Posiciones</th>
                                <th class="h-actions bg-dark text-white text-center" width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($areas as $area)
                            <tr class="row-reunion" reunionid="{{$area->id}}">
                                <td class="d-name">
                                    <a href="{{route('position.index')}}?area_id={{$area->id}}">{{$area->nombre}}</a>
                                </td>
                                {{-- <td class="d-visible align-middle">
                                    <span class="{{$area->vis_matriz == 1?'text-success':'text-danger'}}">
                                        {{$area->vis_matriz == 1?'Visible':'No Visible'}}
                                    </span>
                                </td> --}}
                                <td class="d-positions align-middle text-center">{{sizeof($area->positions)}}</td>
                                <td class="d-actions align-middle text-center">
                                    <a href="#" class="text-white btn btn-info btn-sm btn-edit" data-id="{{$area->id}}">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-pencil"></use>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-white btn btn-danger btn-sm btn-remove" data-id="{{$area->id}}">
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
<script src="{{asset("js/intranet/branches.js")}}"></script>
<script>
    var new_popup = "{{route('areas.new')}}";
    var edit_popup = "{{route('areas.edit')}}";
    var save_new = "{{route('areas.save.new')}}";
    var save_edit = "{{route('areas.save.update')}}";
</script>
@endsection