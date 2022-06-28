@extends('layouts.admin')

@section('title', 'Usuarios')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{asset('css/intranet/users.css')}}">
@endsection

@section('content')

<div id="newUserModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>

<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-3">
            <div class="card-body">
                <a id="new_user_btn" href="#" class="btn btn-success text-white">+ Nuevo usuario</a>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                Usuarios
            </div>
            <div class="card-body">
                <div class="overflow-auto">
                    <table class="table table-bordered m-0">
                        <thead>
                            <tr>
                                <th class="bg-dark text-white h-name">Nombre</th>
                                <th class="bg-dark text-white h-email" width="250">Email</th>
                                <th class="bg-dark text-white h-area" width="200">Área</th>
                                <th class="bg-dark text-white h-position" width="250">Posición</th>
                                <th class="bg-dark text-white h-status" width="100">Estado</th>
                                <th class="bg-dark text-white h-action" width="150"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td class="d-name align-middle">{{$user->nombre}}</td>
                                <td class="d-email align-middle">{{$user->email}}</td>
                                <td class="d-area align-middle">{{$user->position->area->nombre}}</td>
                                <td class="d-position align-middle">{{$user->position->nombre}}</td>
                                <td class="d-position align-middle text-{{$user->estado == 0?'danger':'success'}}">{{$user->estado == 0?'DESACTIVADO':'ACTIVO'}}</td>
                                <td class="d-action text-center">
                                    <a href="#" class="btn btn-info btn-sm">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-pencil"></use>
                                        </svg>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm">
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
<script src="{{asset("js/intranet/users.js")}}"></script>
@endsection