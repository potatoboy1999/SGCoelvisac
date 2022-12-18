@extends('layouts.admin')

@section('title', 'Roles')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="{{asset('css/intranet/roles.css')}}">
@endsection

@section('content')
<div class="body flex-grow-1 px-3">
    <div class="container">
        <h4>Roles</h4>
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
                                <tr>
                                    <td>{{$rol->nombres}}</td>
                                    <td width="100">{{sizeOf($rol->stratObjectives)}}</td>
                                    <td width="150">{{date('d-m-Y',strtotime($rol->created_at));}}</td>
                                    <td width="50" class="align-middle" align="center">
                                        <div class="dropdown">
                                            <span class="badge bg-secondary btn-more text-black" href="#" role="button" data-coreui-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis"></i>
                                            </span>
                                            <ul class="dropdown-menu p-0">
                                                <li>
                                                    <a class="dropdown-item" href="">
                                                        Editar
                                                    </a>
                                                </li>
                                                @if (sizeOf($rol->stratObjectives) == 0)
                                                <li>
                                                    <a class="dropdown-item bg-danger text-white" href="">
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
    
</script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
<script src="{{asset("js/intranet/roles.js")}}"></script>
<script>
    
</script>

@endsection