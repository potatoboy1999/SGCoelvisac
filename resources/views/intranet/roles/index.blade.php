@extends('layouts.admin')

@section('title', 'Roles')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="{{asset('css/intranet/roles.css')}}">
@endsection

@section('content')
<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleModalLabel">Nuevo Rol<span id="hl-label"></span></h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-section" id="form-role">
                    <form id="form-newRole" action="{{route('areaRoles.store')}}" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label class="form-label" for="">Area</label>
                                    <select name="area_id" id="area_select" class="form-select">
                                        @foreach ($areas as $area)
                                        <option value="{{$area->id}}">{{$area->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label class="form-label" for="">Nombre</label>
                                    <input type="text" name="name" class="form-control" placeholder="Nombre del Rol" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-success text-white float-end mx-1">Crear</button>
                                    <a class="btn btn-secondary text-white float-end" data-coreui-dismiss="modal">Cerrar</a>
                                </div>
                            </div>
                        </div>
                    </form>
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
<div class="modal fade" id="roleEditModal" tabindex="-1" aria-labelledby="roleEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleEditModalLabel">Editar Objetivo<span id="hl-label"></span></h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-section" id="form-edit-role">
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
<div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRoleModalLabel">Eliminar Rol<span id="hl-label"></span></h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-section" id="form-delete">
                    <form id="f-form-delete" action="{{route('areaRoles.delete')}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="">
                        <div class="row">
                            <div class="col-md-12">
                                <p>
                                    <strong class="text-danger">¡Estas por eliminar un Rol!</strong><br>
                                    Estas por eliminar el Rol: <br>
                                    <strong><span id="role_dlt_name"></span></strong>
                                </p>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-danger text-white float-end mx-1">Eliminar</button>
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
        <h4>Roles</h4>
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-row flex-wrap">
                    <div class="p-1">
                        <a href="#" class="btn btn-success text-white new-role" data-coreui-toggle="modal" data-coreui-target="#roleModal">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-plus"></use>
                            </svg> Nuevo Item
                        </a>
                    </div>
                    {{-- <div class="p-1">
                        <button type="button" class="btn btn-secondary text-white" data-coreui-toggle="modal" data-coreui-target="#filterModal">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-filter"></use>
                            </svg> Filtrar
                        </button>
                    </div>--}}
                </div>
            </div>
        </div>
        <div id="matrix_content">
            @foreach ($areas as $area)
            <div class="card mb-4">
                <div class="card-header">
                    {{$area->nombre}}
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Rol</th>
                                <th>Objetivos</th>
                                <th>Creado En</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($area->roles as $rol)
                                <tr class="role-{{$rol->id}}">
                                    <td class="role-name">{{$rol->nombres}}</td>
                                    <td width="100">{{sizeOf($rol->stratObjectives)}}</td>
                                    <td width="150">{{date('d-m-Y',strtotime($rol->created_at));}}</td>
                                    <td width="50" class="align-middle" align="center">
                                        <div class="dropdown" ddTrack="{{'ddrole'.$rol->id}}">
                                            <span class="badge bg-secondary btn-more text-black" href="#" role="button" data-coreui-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis"></i>
                                            </span>
                                            <ul class="dropdown-menu p-0" ddTrack="{{'ddrole'.$rol->id}}">
                                                <li>
                                                    <a class="dropdown-item edit-role" href="#" role="{{$rol->id}}" data-coreui-toggle="modal" data-coreui-target="#roleEditModal">
                                                        Editar
                                                    </a>
                                                </li>
                                                @if (sizeOf($rol->stratObjectives) == 0)
                                                <li>
                                                    <a class="dropdown-item bg-danger text-white dlt-role" href="#" role="{{$rol->id}}" data-coreui-toggle="modal" data-coreui-target="#deleteRoleModal">
                                                        <svg class="icon">
                                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-trash"></use>
                                                        </svg> Eliminar
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
                
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    const editFormUrl = "{{route('areaRoles.popup.edit')}}"
</script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
<script src="{{asset("js/intranet/roles.js")}}"></script>
<script>
    
</script>

@endsection