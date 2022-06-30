@extends('layouts.admin')

@section('title', 'Perfiles')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
@endsection

@section('content')

<div id="profileModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>

<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-3">
            <div class="card-body">
                <a id="profile_btn" href="{{route('user.profiles.popup')}}" class="btn btn-success text-white">+ Nuevo Perfil</a>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                Perfiles
            </div>
            <div class="card-body">
                <div class="overflow-auto">
                    <table class="table table-bordered m-0">
                        <thead>
                            <tr>
                                <th class="bg-dark text-white h-name" width="200">Nombre</th>
                                <th class="bg-dark text-white h-options">Opciones</th>
                                <th class="bg-dark text-white h-status" width="100">Estado</th>
                                <th class="bg-dark text-white h-action" width="150"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($profiles as $profile)
                            <tr>
                                <td class="d-name align-middle">{{$profile->descripcion}}</td>
                                <td>
                                    @foreach ($profile->options as $option)
                                        <span class="badge bg-info">{{$option->opcion}}</span>
                                    @endforeach
                                </td>
                                <td class="d-position align-middle text-{{$profile->estado == 0?'danger':'success'}}">{{$profile->estado == 0?'DESACTIVADO':'ACTIVO'}}</td>
                                <td class="d-action text-center">
                                    <a href="#" class="btn btn-info btn-sm btn-profile-edit" data-id="{{$profile->id}}" data-route="{{route('user.profiles.popup')}}">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-pencil"></use>
                                        </svg>
                                    </a>
                                    <form class="d-inline-block" action="{{route($profile->estado == 0?'user.profiles.activate':'user.profiles.deactivate')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$profile->id}}">
                                        <button class="btn {{$profile->estado == 0?'btn-warning':'btn-danger'}} btn-sm">
                                            <svg class="icon">
                                                <use xlink:href="{{asset("icons/sprites/free.svg")}}{{$profile->estado == 0?'#cil-reload':'#cil-trash'}}"></use>
                                            </svg>
                                        </button>
                                    </form>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script src="{{asset("js/intranet/profiles.js")}}"></script>
@endsection