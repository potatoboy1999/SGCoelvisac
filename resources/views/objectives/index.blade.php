@extends('layouts.admin')

@section('title', 'Objetivos')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <style>
        thead tr th{
            background-color: #51607c!important;
            color: white!important;
        }
        .rol-header{
            background-color: #4190af;
            color: white;
        }
        td.t_role_row{
            background-color: #8b9bb7!important;
        }
        td.t_theme_row{
            background-color: #cccccc!important;
        }
        td.t_red {
            background-color: rgb(236, 29, 29);
        }
        td.t_green {
            background-color: green;
        }
        td.t_yellow {
            background-color: rgb(172, 172, 39);
        }
        #ui-datepicker-div{
            z-index: 10000!important;
        }
        .toast{
            background-color: var(--cui-toast-background-color, rgba(255, 255, 255, 1))
        }
        .file-downloadable{
            /*border: 1px solid #2eb85c;
            border-radius: 0.5rem;
            padding: 0.25rem;*/
        }
        .file-downloadable p{
            margin: 0;
        }
    </style>
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
                <form id="role_form" action="{{route('new_item')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
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
                                <select class="form-select" name="role_sel" id="role_sel"></select>
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
                                <select class="form-select" name="theme_sel" id="theme_sel"></select>
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
                                <select class="form-select" name="obj_sel" id="obj_sel"></select>
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
                                    <input id="act_date_start" class="form-control" type="text" name="act_date_start" value="" required>
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
                                    <input id="act_date_end" class="form-control" type="text" name="act_date_end" value="" required>
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-calendar"></use>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group py-1">
                                <label class="form-label" for="policy_file">Procedimiento / Politica:</label>
                                <input id="policy_file" class="form-control" type="file" name="policy_file">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group py-1">
                                <label class="form-label" for="adjacent_file">Documento Adjunto:</label>
                                <input id="adjacent_file" class="form-control" type="file" name="adjacent_file">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="item_save" class="btn btn-info text-white" type="button">Guardar</button>
            </div>
        </div>
    </div>
</div>
<!-- End Item Modal -->

<!-- Edit Activity Modal -->
<div class="modal fade" id="editActivityModal" tabindex="-1" aria-labelledby="editActivityModalLabel" aria-hidden="true">
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
            <div class="modal-header">
                <h5 class="modal-title" id="adjacentModalLabel">Documento: Adjunto</h5>
                <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div id="" class="file-downloadable mb-3" style="">
                            <p><strong>Documento Adjunto:</strong></p>
                            <p id="a_filename"></p>
                            <div class="mt-3">
                                <a id="a_file_download" href="{{route("doc.download")}}" file-id="" class="btn btn-success text-white btn-file-download">Descargar</a>
                                <a id="a_file_delete" href="{{route("doc.delete")}}" file-id="" file-type="adj" class="btn btn-danger text-white btn-file-delete">Eliminar</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <form id="adjacent-form" action="{{route('upd_activity_adjacent')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">Nuevo Documento Adjunto:</label>
                                <input type="file" name="a_file" id="adjacent_upd_file" class="form-control" required>
                                <input type="hidden" name="a_edit" value="false">
                                <input type="hidden" name="a_act_id" value="">
                            </div>
                        </form>
                    </div>
                    <div class="col-12">
                        <div id="a_error" class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="adj_save" class="btn btn-info text-white" type="button">Guardar</button>
            </div>
        </div>
    </div>
</div>
<!-- End Adjacent Modal -->

<div class="body flex-grow-1 px-3">
    <div class="position-fixed end-0 px-3" style="z-index: 11; margin-top: -20px">
        <div id="liveToast" class="toast fade hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <svg class="docs-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#2eb85c"></rect></svg>
                <strong class="me-auto">Nuevo Item</strong>
                <button type="button" class="btn-close" data-coreui-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">Hello, world! This is a toast message.</div>
        </div>
    </div>
    <div class="container-lg">
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
                        <a href="javascript:;" class="btn btn-secondary text-white">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-search"></use>
                            </svg> Buscar
                        </a>
                    </div>
                    <div class="p-1">
                        <a href="javascript:;" class="btn btn-secondary text-white">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-filter"></use>
                            </svg> Filtrar
                        </a>
                    </div>
                    <div class="p-1">
                        <a href="javascript:;" class="btn btn-secondary text-white">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-comment-bubble"></use>
                            </svg> Ver Comentarios
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start Activities Matrix -->
        <?php $i = 0; ?>
        @foreach ($roles as $role)
        <div class="card mb-4">
            <div class="card-header rol-header">
                Rol {{$i+1}}: {{$role->nombre}}
            </div>
            <div class="card-body">
                <?php 
                    $x = 0; 
                    $themes = $role->themes->where("estado", 1);
                ?>
                @foreach ($themes as $theme)
                <div class="card {{($x != sizeOf($themes)-1?"mb-3":"")}}">
                    <div class="card-header">Tema {{$x+1}}: {{$theme->nombre}}</div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="overflow-auto">
                                    <table class="table table-bordered m-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center align-middle" width="50">COD</th>
                                                <th class="text-center align-middle" width="150">Objetivo</th>
                                                <th class="text-center align-middle" width="180">Actividades Principales</th>
                                                <th class="text-center align-middle" width="100">Fecha Inicio</th>
                                                <th class="text-center align-middle" width="100">Fecha Fin</th>
                                                <th class="text-center align-middle" width="80"><!--Procedimiento/<br>-->Politica</th>
                                                <th class="text-center align-middle" width="120">Documento<br>Adjunto</th>
                                                <th class="text-center align-middle" width="50">Estado</th>
                                                <th class="text-center align-middle t-head-comments" width="100"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($theme->objectives->where("estado", 1) as $objective)
                                                <?php 
                                                    $y = 0; 
                                                    $activities = $objective->activities->where("estado", 1);
                                                ?>
                                                @foreach ($activities as $activity)
                                                <tr>
                                                    @if ($y == 0)
                                                    <td class="text-center align-middle" rowspan="{{sizeOf($activities)}}">Op_01-1</td>
                                                    <td class="align-middle" rowspan="{{sizeOf($activities)}}">{{$objective->nombre}}</td>
                                                    @endif
                                                    <td class="align-middle">{{$activity->nombre}}</td>
                                                    <td class="text-center align-middle">{{date("d-m-Y", strtotime($activity->fecha_comienzo))}}</td>
                                                    <td class="text-center align-middle">{{date("d-m-Y", strtotime($activity->fecha_fin))}}</td>
                                                    <td class="text-center align-middle">
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
                                                    <td class="text-center align-middle">
                                                        @php
                                                            $adjacent = $activity->docAdjacent;
                                                            $docName = null;
                                                            $docId = null;
                                                            if($adjacent && $adjacent->estado == 1){
                                                                $docName = $adjacent->nombre;
                                                                $docId = $adjacent->id;
                                                            }
                                                        @endphp
                                                        <a href="javascript:;" class="btn {{$docName?'btn-success':'btn-warning'}} btn-sm text-white btn-show-adjacent" data-id="{{$activity->id}}" data-filename="{{$docName}}" data-fileid="{{$docId}}">
                                                            <svg class="icon">
                                                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#{{$docName?'cil-file':'cil-arrow-thick-from-bottom'}}"></use>
                                                            </svg>
                                                        </a>
                                                    </td>
                                                    <td class="t_red"></td>
                                                    <td class="text-center align-middle t-cel-comments">
                                                        <a href="javascript:;" class="btn btn-secondary my-1 btn-sm text-white btn-edit" data-act="{{$activity->id}}" data-route="{{route("activity.popup.edit")}}">
                                                            <svg class="icon">
                                                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-pencil"></use>
                                                            </svg>
                                                        </a>
                                                        <a href="javascript:;" class="btn btn-danger my-1 btn-sm text-white btn-delete">
                                                            <svg class="icon">
                                                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-trash"></use>
                                                            </svg>
                                                        </a>
                                                        <a href="javascript:;" class="btn btn-success my-1 btn-sm text-white btn-comment">
                                                            <svg class="icon">
                                                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-comment-bubble"></use>
                                                            </svg>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php $y++; ?>
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
                @endforeach
            </div>
        </div>
        <?php $i++; ?>
        @endforeach
        <!-- End Activities Matrix -->
        <div class="card mb-4 d-none">
            <div class="card-header">Matriz de Objetivos</div>
            <div class="card-body overflow-auto">
                <div class="row">
                    <div class="col-12">
                        <div class="text-end mb-2">
                            <a href="javascript:;" class="btn btn-success text-white" data-coreui-toggle="modal" data-coreui-target="#roleModal">+ Nuevo Rol</a>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle" width="90">COD</th>
                            <th class="text-center align-middle">Objetivo</th>
                            <th class="text-center align-middle">Actividades Principales</th>
                            <th class="text-center align-middle" width="120">Fecha Inicio</th>
                            <th class="text-center align-middle" width="120">Fecha Fin</th>
                            <th class="text-center align-middle">Procedimiento/<br>Politica</th>
                            <th class="text-center align-middle">Documento<br>Adjunto</th>
                            <th class="text-center align-middle">Estado</th>
                            <th class="text-center align-middle">Comentarios</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="t_role_row" colspan="100%">Rol 01: Asegurar la calidad y confiabilidad de la red eléctrica de distribución de Coelvisac para el suministro de energía</td>
                        </tr>
                        <tr>
                            <td class="t_theme_row" colspan="100%">Tema 1: Distribución de la Red Eléctrica</td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" rowspan="3">Op_01-1</td>
                            <td class="align-middle" rowspan="3">Evitar Interrupciones Masivas</td>
                            <td class="align-middle">Elaborar e implementar plan</td>
                            <td class="text-center align-middle">05-Junio-22</td>
                            <td class="text-center align-middle">25-Junio-22</td>
                            <td class="text-center align-middle"><a href="#" class="btn btn-warning btn-sm">
                                <svg class="icon">
                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-arrow-thick-from-bottom"></use>
                                </svg>
                            </a></td>
                            <td class="text-center align-middle"><a href="#" class="btn btn-warning btn-sm">
                                <svg class="icon">
                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-arrow-thick-from-bottom"></use>
                                </svg>
                            </a></td>
                            <td class="t_red"></td>
                            <td class="text-center align-middle t_comments"><a href="#" class="btn btn-success btn-sm">
                                <svg class="icon">
                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-notes"></use>
                                </svg>
                            </a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">Implementar sistemas de protección suficientes</td>
                            <td class="text-center align-middle">05-Junio-22</td>
                            <td class="text-center align-middle">25-Junio-22</td>
                            <td class="text-center align-middle"><a href="#" class="btn btn-warning btn-sm">
                                <svg class="icon">
                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-arrow-thick-from-bottom"></use>
                                </svg>
                            </a></td>
                            <td class="text-center align-middle"><a href="#" class="btn btn-warning btn-sm">
                                <svg class="icon">
                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-arrow-thick-from-bottom"></use>
                                </svg>
                            </a></td>
                            <td class="t_red"></td>
                            <td class="text-center align-middle t_comments"><a href="#" class="btn btn-success btn-sm">
                                <svg class="icon">
                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-notes"></use>
                                </svg>
                            </a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">Clientes libres deben contar con sistemas de protección dentro de sus operaciones</td>
                            <td class="text-center align-middle">05-Junio-22</td>
                            <td class="text-center align-middle">25-Junio-22</td>
                            <td class="text-center align-middle"><a href="#" class="btn btn-warning btn-sm">
                                <svg class="icon">
                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-arrow-thick-from-bottom"></use>
                                </svg>
                            </a></td>
                            <td class="text-center align-middle"><a href="#" class="btn btn-warning btn-sm">
                                <svg class="icon">
                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-arrow-thick-from-bottom"></use>
                                </svg>
                            </a></td>
                            <td class="t_red"></td>
                            <td class="text-center align-middle t_comments"><a href="#" class="btn btn-success btn-sm">
                                <svg class="icon">
                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-notes"></use>
                                </svg>
                            </a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
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

    $(function() {
        $.ajax({
            url: "{{route('api_all_activities')}}",
            method: "GET",
            success: function(res){
                global_items = res;
                setupNewItemModal();
            }
        });
    });

</script>

@endsection