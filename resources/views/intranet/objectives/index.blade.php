@php
    function progressStatus($activity){
        $status = 0; // not done = RED
        if($activity->cumplido == 1){
            $status = 2; // done = GREEN
        }else{
            $today = time();
            $d_start = strtotime($activity->fecha_comienzo);
            $d_end = strtotime($activity->fecha_fin);
            if($d_start <= $today && $today <= $d_end){
                // calculate 25% of time remaining
                $diff = ($d_end - $d_start)*0.25;
                $d_limit = $d_start + $diff;

                if($today < $d_limit){
                    $status = 2; // if today is within 25% of start, status OK = GREEN
                }
                
                if($d_limit <= $today){
                    $status = 1; // if today is past 25%, status warning = YELLOW
                }

            }else if($d_end < $today){
                $status = 0; // time expired, not done = RED
            }
        }
        return $status;
    }
    function valActivity($activity, $filter){
        if($filter['active']){
            $labels = ['red','yellow','green'];
            $progStatus = progressStatus($activity);
            if($filter['status'][$labels[$progStatus]]){
                return true;
            }
        }else{
            return true;
        }
        return false;
    }
    function filterActivities($activities, $filter){
        $list = [];
        foreach ($activities as $activity) {
            if(valActivity($activity, $filter)){
                $list[] = $activity;
            }
        }
        return $list;
    }
    function themeHasActivities($theme, $filter){
        $count = 0;
        foreach ($theme->objectives as $objective) {
            foreach ($objective->activities as $activity) {
                if(valActivity($activity, $filter)){
                    $count++;
                }
            }
        }
        return ($count>0);
    }
    function roleHasActivities($role, $filter){
        $count = 0;
        foreach ($role->themes as $theme) {
            foreach ($theme->objectives as $objective) {
                foreach ($objective->activities as $activity) {
                    if(valActivity($activity, $filter)){
                        $count++;
                    }
                }
            }
        }
        return ($count>0);
    }
@endphp

@extends('layouts.admin')

@section('title', 'Objetivos')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{asset('css/intranet/objectives.css')}}">
@endsection

@section('content')

<!-- Item Modal -->
<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleModalLabel">Nuevo Item</h5>
                <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="role_form" action="{{route('new_item')}}" method="POST" enctype="multipart/form-data" autocomplete="off" onkeydown="return event.key != 'Enter';">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group py-1">
                                <label>Area:</label>
                                <input type="text" class="form-control" value="{{$area?$area->nombre:''}}" readonly>
                                <input type="hidden" name="area_id" value="{{$area?$area->id:''}}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group py-1">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label" for="role_sel">Rol:</label>
                                    </div>
                                    <div class="col-6 text-right">
                                        <div class="form-check form-switch float-end">
                                            <input class="form-check-input new_item_switch" id="newRoleSwitch" name="new_role_switch" type="checkbox" data-object="role">
                                            <label class="form-check-label" for="newRoleSwitch">Nuevo</label>
                                        </div>
                                    </div>
                                </div>
                                <select class="form-select" name="role_sel" id="role_sel">
                                    <option value='0'>-- No hay Roles disponibles --</option>
                                </select>
                                <input class="form-control" type="text" name="role_name" id="role_name" placeholder="Descripcion del rol" style="display: none;">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group py-1">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label" for="theme_sel">Tema:</label>
                                    </div>
                                    <div class="col-6 text-right">
                                        <div class="form-check form-switch float-end">
                                            <input class="form-check-input new_item_switch" id="newThemeSwitch" name="new_theme_switch" type="checkbox" data-object="theme">
                                            <label class="form-check-label" for="newThemeSwitch">Nuevo</label>
                                        </div>
                                    </div>
                                </div>
                                <select class="form-select" name="theme_sel" id="theme_sel">
                                    <option value='0'>-- No hay Temas disponibles --</option>
                                </select>
                                <input class="form-control" type="text" name="theme_name" id="theme_name" placeholder="Descripcion del tema" style="display: none;">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group py-1">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label" for="obj_sel">Objetivo:</label>
                                    </div>
                                    <div class="col-6 text-right">
                                        <div class="form-check form-switch float-end">
                                            <input class="form-check-input new_item_switch" id="newObjSwitch" name="new_obj_switch" type="checkbox" data-object="obj">
                                            <label class="form-check-label" for="newObjSwitch">Nuevo</label>
                                        </div>
                                    </div>
                                </div>
                                <select class="form-select" name="obj_sel" id="obj_sel">
                                    <option value='0'>-- No hay Objetivos disponibles --</option>
                                </select>
                                <input class="form-control" type="text" name="obj_name" id="obj_name" placeholder="Descripcion del objetivo" style="display: none;">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group py-1">
                                <label class="form-label" for="activity_desc">Actividad:</label>
                                <input id="activity_desc" class="form-control" type="text" name="activity_desc" placeholder="Descripcion de la actividad" value="" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group py-1">
                                <label class="form-label" for="act_date_start">Fecha Inicio:</label>
                                <div class="input-group">
                                    <input id="act_date_start" class="form-control" type="text" name="act_date_start" value="{{date('d/m/Y')}}" required>
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-calendar"></use>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group py-1">
                                <label class="form-label" for="act_date_end">Fecha Fin:</label>
                                <div class="input-group">
                                    <input id="act_date_end" class="form-control" type="text" name="act_date_end" value="{{date('d/m/Y')}}" required>
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-calendar"></use>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-12">
                            <div class="form-group py-1">
                                <label class="form-label" for="policy_file">Procedimiento / Politica:</label>
                                <input id="policy_file" class="form-control" type="file" name="policy_file">
                            </div>
                        </div> --}}
                        <div class="col-12">
                            <div class="form-group py-1">
                                <label class="form-label" for="adjacent_file">Documento Adjunto:</label>
                                <input id="adjacent_file" class="form-control" type="file" name="adjacent_file">
                            </div>
                        </div>
                    </div>
                </form>
                <div id="item_error">
                    <ul class="text-danger"></ul>
                </div>
            </div>
            <div class="modal-footer">
                <button id="item_save" class="btn btn-info text-white" type="button">Guardar</button>
            </div>
        </div>
    </div>
</div>
<!-- End Item Modal -->

<!-- Edit Comment Modal -->
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
</div>
<!-- End Edit Comment Modal -->

<!-- Edit Activity Modal -->
<div class="modal fade" id="editActivityModal" tabindex="-1" aria-labelledby="editActivityModalLabel" aria-hidden="true">
</div>
<!-- End Edit Activity Modal -->

<!-- Edit Theme Modal -->
<div class="modal fade" id="editThemeModal" tabindex="-1" aria-labelledby="editThemeModalLabel" aria-hidden="true">
</div>
<!-- End Edit Activity Modal -->

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
</div>
<!-- End Edit Activity Modal -->

<!-- Delete Item Modal -->
<div class="modal fade" id="delItemModal" tabindex="-1" aria-labelledby="delItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Quieres eliminar este elemento?</h5>
            </div>
            <div class="modal-body">
                <p id="d-item-name"></p>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" id="delete-item-confirm" class="btn btn-danger text-white" d-route="" d-id="" d-type="" d-obj="">Si, Eliminar</a>
                <a href="javascript:;" id="delete-item-deny" class="btn btn-secondary text-white" data-coreui-dismiss="modal">No, Cancelar</a>
            </div>
        </div>
    </div>
</div>
<!-- End Edit Activity Modal -->

<!-- Policy Modal -->
<div class="modal fade" id="policyModal" tabindex="-1" aria-labelledby="policyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="policyModalLabel">Documento: Politica</h5>
                <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div id="" class="file-downloadable mb-3" style="">
                            <p><strong>Documento Adjunto:</strong></p>
                            <p id="p_filename"></p>
                            <div class="mt-3">
                                <a id="p_file_download" href="{{route("doc.download")}}" file-id="" class="btn btn-success text-white btn-file-download">Descargar</a>
                                <a id="p_file_delete" href="{{route("doc.delete")}}" file-id="" file-type="pol" class="btn btn-danger text-white btn-file-delete">Eliminar</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <form id="policy-form" action="{{route('upd_activity_policy')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">Nuevo Procedimiento / Politica:</label>
                                <input type="file" name="p_file" id="policy_upd_file" class="form-control" required>
                                <input type="hidden" name="p_edit" value="false">
                                <input type="hidden" name="p_act_id" value="">
                            </div>
                        </form>
                    </div>
                    <div class="col-12">
                        <div id="p_error" class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="pol_save" class="btn btn-info text-white" type="button">Guardar</button>
            </div>
        </div>
    </div>
</div>
<!-- End Policy Modal -->

<!-- Adjacent Modal -->
<div class="modal fade" id="adjacentModal" tabindex="-1" aria-labelledby="adjacentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<!-- End Adjacent Modal -->

@if ($area)
<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filtros</h5>
                <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                    $isFiltered = $filter['active'];
                    $statusFilter = $filter['status'];
                    $sGreen = $filter['status']['green'];
                    $sYellow = $filter['status']['yellow'];
                    $sRed = $filter['status']['red'];
                @endphp
                <form id="search_form" action="{{route('objectives')}}" method="GET" autocomplete="off" onkeydown="return event.key != 'Enter';">
                    <input type="hidden" name="search" value="Y">
                    <input type="hidden" name="area" value="{{$area?$area->id:''}}">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-check-label">Estado:</label>
                            <ul class="status-choice-list mb-1">
                                <li class="s-choice-item choice-green {{$isFiltered?($sGreen?'active':''):'active'}}">
                                    <a href="#" class="s-choice" data-target="#s-green" active="{{$isFiltered?($sGreen?'on':'off'):'on'}}">
                                        Verde
                                        <svg class="i-check icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-check"></use>
                                        </svg>
                                    </a href="#">
                                    <input id="s-green" class="d-none" type="checkbox" name="s_green" {{$isFiltered?($sGreen?'checked':''):'checked'}}>
                                </li>
                                <li class="s-choice-item choice-yellow {{$isFiltered?($sYellow?'active':''):'active'}}">
                                    <a href="#" class="s-choice" data-target="#s-yellow" active="{{$isFiltered?($sYellow?'on':'off'):'on'}}">
                                        Amarillo
                                        <svg class="i-check icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-check"></use>
                                        </svg>
                                    </a href="#">
                                    <input id="s-yellow" class="d-none" type="checkbox" name="s_yellow" {{$isFiltered?($sYellow?'checked':''):'checked'}}>
                                </li>
                                <li class="s-choice-item choice-red {{$isFiltered?($sRed?'active':''):'active'}}">
                                    <a href="#" class="s-choice" data-target="#s-red" active="{{$isFiltered?($sRed?'on':'off'):'on'}}">
                                        Rojo
                                        <svg class="i-check icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-check"></use>
                                        </svg>
                                    </a href="#">
                                    <input id="s-red" class="d-none" type="checkbox" name="s_red" {{$isFiltered?($sRed?'checked':''):'checked'}}>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12">
                            <div class="form-group py-1">
                                <div class="form-check form-switch">
                                    <input class="form-check-input switch-f-choice" id="searchByRole" name="search_role" type="checkbox" data-object="role" data-target="#search_role" {{$filter['role_word']!=''?'checked':''}}>
                                    <label class="form-check-label" for="searchByRole">Buscar por Rol:</label>
                                </div>
                                <input id="search_role" class="form-control" type="text" name="search_role" placeholder="Busqueda" value="{{$filter['role_word']!=''?$filter['role_word']:''}}" required {{$filter['role_word']!=''?'':'disabled'}}>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group py-1">
                                <div class="form-check form-switch">
                                    <input class="form-check-input switch-f-choice" id="searchByTheme" name="search_theme" type="checkbox" data-object="role" data-target="#search_theme" {{$filter['theme_word']!=''?'checked':''}}>
                                    <label class="form-check-label" for="searchByTheme">Buscar por Tema:</label>
                                </div>
                                <input id="search_theme" class="form-control" type="text" name="search_theme" placeholder="Busqueda" value="{{$filter['theme_word']!=''?$filter['theme_word']:''}}" required {{$filter['theme_word']!=''?'':'disabled'}}>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group py-1">
                                <div class="form-check form-switch">
                                    <input class="form-check-input switch-f-choice" id="searchByObj" name="search_objective" type="checkbox" data-object="role" data-target="#search_objective" {{$filter['obj_word']!=''?'checked':''}}>
                                    <label class="form-check-label" for="searchByObj">Buscar por Objetivo:</label>
                                </div>
                                <input id="search_objective" class="form-control" type="text" name="search_objective" placeholder="Busqueda" value="{{$filter['obj_word']!=''?$filter['obj_word']:''}}" required {{$filter['obj_word']!=''?'':'disabled'}}>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group py-1">
                                <div class="form-check form-switch">
                                    <input class="form-check-input switch-f-choice" id="searchByActivity" name="search_activity" type="checkbox" data-object="role" data-target="#search_activity" {{$filter['act_word']!=''?'checked':''}}>
                                    <label class="form-check-label" for="searchByActivity">Buscar por nombre:</label>
                                </div>
                                <input id="search_activity" class="form-control" type="text" name="search_activity" placeholder="Busqueda" value="{{$filter['act_word']!=''?$filter['act_word']:''}}" required {{$filter['act_word']!=''?'':'disabled'}}>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group py-1">
                                <div class="form-check form-switch">
                                    <input class="form-check-input switch-f-choice" id="searchFrom" name="search_from" type="checkbox" data-object="role" data-target="#search_from" {{$filter['date_from']!=''?'checked':''}}>
                                    <label class="form-check-label" for="searchFrom">Buscar desde:</label>
                                </div>
                                <div class="input-group">
                                    <input id="search_from" class="form-control" type="text" name="search_from" value="{{$filter['date_from']!=''?$filter['date_from']:date('d/m/Y', time())}}" required {{$filter['date_from']!=''?'':'disabled'}}>
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-calendar"></use>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group py-1">
                                <div class="form-check form-switch">
                                    <input class="form-check-input switch-f-choice" id="searchTo" name="search_to" type="checkbox" data-object="role" data-target="#search_to" {{$filter['date_to']!=''?'checked':''}}>
                                    <label class="form-check-label" for="searchTo">Buscar hasta:</label>
                                </div>
                                <div class="input-group">
                                    <input id="search_to" class="form-control" type="text" name="search_to" value="{{$filter['date_to']!=''?$filter['date_to']:date('d/m/Y', time())}}" required {{$filter['date_to']!=''?'':'disabled'}}>
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-calendar"></use>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="{{route('objectives')}}?area={{$area->id}}" class="btn btn-secondary text-white">Sin Filtro</a>
                <input class="btn btn-info text-white" type="submit" form="search_form" value="Filtrar">
            </div>
        </div>
    </div>
</div>
<!-- End Filter Modal -->
@endif

<div class="body flex-grow-1 px-3">
    <div class="position-fixed end-0 px-3" style="z-index: 11; margin-top: -20px">
        <div id="liveToast" class="toast fade hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <svg class="docs-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#2eb85c"></rect></svg>
                <strong class="me-auto">Nuevo Item</strong>
                <button type="button" class="btn-close" data-coreui-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>
    <div class="container-lg">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-row flex-wrap">
                    <form id="form-area-sel" action="{{route('objectives')}}" method="get" class="w-100">
                        <div class="form-group w-100">
                            <label>Area:</label>
                            <select name="area" id="area-sel" class="form-select d-inline" style="width: calc(100% - 41px);">
                                <option value="0">-- Selecciona un area --</option>
                                @foreach ($all_areas as $a)
                                    <option value="{{$a->id}}" {{($area && $area->id == $a->id)? 'selected':''}}>{{$a->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if ($area)
        
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-row flex-wrap">
                    <div class="p-1">
                        <a href="javascript:;" class="btn btn-success text-white" data-coreui-toggle="modal" data-coreui-target="#roleModal">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-plus"></use>
                            </svg> Nuevo Item
                        </a>
                    </div>
                    <div class="p-1">
                        <button type="button" class="btn {{$isFiltered?'btn-warning':'btn-secondary'}} text-white" data-coreui-toggle="modal" data-coreui-target="#filterModal">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-filter"></use>
                            </svg> {{$isFiltered?'Filtrado':'Filtrar'}}
                        </button>
                    </div>
                    <div class="p-1">
                        <a href="javascript:;" class="btn btn-secondary text-white toggle-dates" toggle-visible="false">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-calendar"></use>
                            </svg> <span>Ver Fechas</span>
                        </a>
                    </div>
                    <?php $permissions = ["Gestión","Admin"]; ?>
                    @if (in_array(Auth::user()->position->area->nombre, $permissions ))
                    <div class="p-1">
                        <a href="javascript:;" class="btn btn-secondary text-white toggle-comments" toggle-visible="false">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-comment-bubble"></use>
                            </svg> <span>Ver Comentarios</span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Start Activities Matrix -->
        <?php $i = 0; ?>
        @foreach ($roles as $role)
            @if (roleHasActivities($role, $filter))
            <div class="card role-card mb-4" role-id="{{$role->id}}">
                <div class="card-header rol-header">
                    <div class="float-end">
                        <a href="{{route('role.popup.edit')}}" class="btn btn-light btn-sm btn-role-settings" roleid="{{$role->id}}">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-settings"></use>
                            </svg>
                        </a>
                        <button class="btn btn-light btn-sm" data-coreui-target="#collapseRole{{$role->id}}" data-coreui-toggle="collapse" type="button" aria-expanded="true">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-chevron-double-down"></use>
                            </svg>
                        </button>
                    </div>
                    {{-- <p class="m-0">Rol {{$i+1}}: {{$role->nombre}}</p> --}}
                    <p class="m-0">Rol {{$role->id}}: {{$role->nombre}}</p>
                </div>
                <div class="card-body p-0">
                    <div id="collapseRole{{$role->id}}" class="collapse">
                        <div class="collapse-content p-3">
                            @php
                                $x = 0; 
                                $themes = $role->themes;
                            @endphp
                            @foreach ($themes as $theme)
                            @if (themeHasActivities($theme, $filter))
                                <div class="card theme-card {{($x != sizeOf($themes)-1?"mb-3":"")}}" theme-id="{{$theme->id}}">
                                    <div class="card-header">
                                        <div class="float-end">
                                            <a href="{{route('theme.popup.edit')}}" class="btn btn-outline-secondary btn-sm btn-theme-settings" themeid="{{$theme->id}}">
                                                <svg class="icon">
                                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-settings"></use>
                                                </svg>
                                            </a>
                                            <button class="btn btn-outline-secondary btn-sm" data-coreui-target="#collapseTheme{{$theme->id}}" data-coreui-toggle="collapse" role="button" aria-expanded="false" roleid="3">
                                                <svg class="icon">
                                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-chevron-double-down"></use>
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="m-0">Tema {{$x+1}}: {{$theme->nombre}}</p>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="collapseTheme{{$theme->id}}" class="collapse row">
                                            <div class="col-12">
                                                <div class="overflow-auto">
                                                    <table class="table table-bordered m-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center align-middle t-head-obj-code" width="50">COD</th>
                                                                <th class="text-center align-middle t-head-obj-name" width="150">Objetivo</th>
                                                                <th class="text-center align-middle t-head-act-name" width="180">Actividades Principales</th>
                                                                <th class="text-center align-middle t-head-date-start" width="100" style="display: none;">Fecha Inicio</th>
                                                                <th class="text-center align-middle t-head-date-end" width="100">Fecha Fin</th>
                                                                <th class="text-center align-middle t-head-policies" width="80"><!--Procedimiento/<br>-->Politica</th>
                                                                <th class="text-center align-middle t-head-adjacents" width="120">Documento<br>Adjunto</th>
                                                                <th class="text-center align-middle t-head-status" width="50">Estado</th>
                                                                <th class="text-center align-middle t-head-comments" width="100"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($theme->objectives as $objective)
                                                                <?php 
                                                                    $y = 0; 
                                                                    $activities = filterActivities($objective->activities, $filter);
                                                                ?>
                                                                @foreach ($activities as $activity)

                                                                    @if (valActivity($activity, $filter))
                                                                    <tr act-id="{{$activity->id}}">
                
                                                                        <td class="text-center align-middle t-obj-code" obj-id="{{$objective->id}}" rowspan="{{sizeOf($activities)}}" style="{{$y == 0?'':'display: none;'}}">
                                                                            Ob_{{$theme->id}}-{{$objective->id}}
                                                                        </td>
                                                                        <td class="align-middle t-obj-name" obj-id="{{$objective->id}}" rowspan="{{sizeOf($activities)}}" style="{{$y == 0?'':'display: none;'}}">{{$objective->nombre}}</td>
                
                                                                        <td class="align-middle t-act-name">{{$activity->nombre}}</td>
                                                                        <td class="text-center align-middle t-date-start" style="display: none;">{{date("d-m-Y", strtotime($activity->fecha_comienzo))}}</td>
                                                                        <td class="text-center align-middle t-date-end">{{date("d-m-Y", strtotime($activity->fecha_fin))}}</td>
                                                                        <td class="text-center align-middle t-policies">
                                                                            @php
                                                                                $policy = $activity->docPolicy;
                                                                                $docName = null;
                                                                                $docId = null;
                                                                                if($policy && $policy->estado == 1){
                                                                                    $docName = $policy->nombre;
                                                                                    $docId = $policy->id;
                                                                                }
                                                                            @endphp
                                                                            <a href="javascript:;" class="btn {{$docName?'btn-success':'btn-warning'}} btn-sm text-white btn-show-policy" data-id="{{$activity->id}}" data-filename="{{$docName}}" data-fileid="{{$docId}}">
                                                                                <svg class="icon">
                                                                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#{{$docName?'cil-file':'cil-arrow-thick-from-bottom'}}"></use>
                                                                                </svg>
                                                                            </a>
                                                                        </td>
                                                                        <td class="text-center align-middle t-adjacents">
                                                                            @php
                                                                                $adjacents = $activity->docAdjacents;
                                                                            @endphp
                                                                            <a href="javascript:;" class="btn {{sizeof($adjacents)>0?'btn-success':'btn-warning'}} btn-sm text-white btn-show-adjacent" data-route="{{route('activity.popup.adjacents')}}" data-id="{{$activity->id}}">
                                                                                <svg class="icon">
                                                                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#{{sizeof($adjacents)>0?'cil-file':'cil-arrow-thick-from-bottom'}}"></use>
                                                                                </svg>
                                                                            </a>
                                                                        </td>
                                                                        @php
                                                                            $s = ['t_red','t_yellow','t_green'];
                                                                        @endphp
                                                                        <td class="t-status {{ $s[progressStatus($activity)] }}"></td>
                                                                        
                                                                        <td class="text-center align-middle t-cel-comments">
                                                                            <a href="javascript:;" class="btn btn-secondary my-1 btn-sm text-white btn-edit" data-act="{{$activity->id}}" data-route="{{route("activity.popup.edit")}}">
                                                                                <svg class="icon">
                                                                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-pencil"></use>
                                                                                </svg>
                                                                            </a>
                                                                            <a href="javascript:;" class="btn btn-danger my-1 btn-sm text-white btn-delete" data-act="{{$activity->id}}" data-route="{{route("activity.popup.delete")}}" data-obj="{{$objective->id}}">
                                                                                <svg class="icon">
                                                                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-trash"></use>
                                                                                </svg>
                                                                            </a>
                                                                            @if (in_array(Auth::user()->position->area->nombre, $permissions ))
                                                                            <a href="{{route('comment.popup.show')}}" class="btn btn-success my-1 btn-sm text-white btn-comment" style="display: none;" data-act="{{$activity->id}}">
                                                                                <svg class="icon">
                                                                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-comment-bubble"></use>
                                                                                </svg>
                                                                            </a>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    <?php $y++; ?>
                                                                    @endif
                                                                @endforeach
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php $x++; ?>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <?php $i++; ?>
            @endif
        @endforeach
        @endif
        <!-- End Activities Matrix -->
    </div>
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="{{asset("js/intranet/objectives.js")}}"></script>
<script>
    @if (session()->get('item_status'))
        $(".toast-body").html("{{session()->get('item_msg')}}");
        var toast = new coreui.Toast($('#liveToast'));
        toast.show();
    @endif

    function getAllActivities(){
        $.ajax({
            url: "{{route('api_all_activities').($area?'?area='.$area->id:'')}}",
            method: "GET",
            success: function(res){
                global_items = res;
                setupNewItemModal();
            }
        });
    }

    $(function() {
        getAllActivities();
    });

</script>

@endsection