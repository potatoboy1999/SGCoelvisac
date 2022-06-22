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

@extends('layouts.front')

@section('title', 'Matriz')
    
@section('style')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('css/front/matrix.css')}}">
@endsection

@section('content')

<!-- Document Download Modal -->
<div class="modal fade" id="adjacentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div>
<!-- End Document Download Modal -->

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
              <form id="search_form" action="{{route('front.activity.matrix.show')}}" method="GET" autocomplete="off" onkeydown="return event.key != 'Enter';">
                  <input type="hidden" name="search" value="Y">
                  <input type="hidden" name="area" value="{{$area?$area->id:''}}">
                  <div class="row">
                      <div class="col-12">
                          <label class="form-check-label">Estado:</label>
                          <ul class="status-choice-list mb-1">
                              <li class="s-choice-item choice-green {{$isFiltered?($sGreen?'active':''):'active'}}">
                                  <a href="javascript:;" class="s-choice" data-target="#s-green" active="{{$isFiltered?($sGreen?'on':'off'):'on'}}">
                                      Verde
                                      <svg class="i-check icon">
                                          <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-check"></use>
                                      </svg>
                                  </a>
                                  <input id="s-green" class="d-none" type="checkbox" name="s_green" {{$isFiltered?($sGreen?'checked':''):'checked'}}>
                              </li>
                              <li class="s-choice-item choice-yellow {{$isFiltered?($sYellow?'active':''):'active'}}">
                                  <a href="javascript:;" class="s-choice" data-target="#s-yellow" active="{{$isFiltered?($sYellow?'on':'off'):'on'}}">
                                      Amarillo
                                      <svg class="i-check icon">
                                          <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-check"></use>
                                      </svg>
                                  </a>
                                  <input id="s-yellow" class="d-none" type="checkbox" name="s_yellow" {{$isFiltered?($sYellow?'checked':''):'checked'}}>
                              </li>
                              <li class="s-choice-item choice-red {{$isFiltered?($sRed?'active':''):'active'}}">
                                  <a href="javascript:;" class="s-choice" data-target="#s-red" active="{{$isFiltered?($sRed?'on':'off'):'on'}}">
                                      Rojo
                                      <svg class="i-check icon">
                                          <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-check"></use>
                                      </svg>
                                  </a>
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
              <a href="{{route('front.activity.matrix.show')}}?area={{$area->id}}" class="btn btn-secondary text-white">Sin Filtro</a>
              <input class="btn btn-info text-white" type="submit" form="search_form" value="Filtrar">
          </div>
      </div>
  </div>
</div>
<!-- End Filter Modal -->

<div class="row">
  <div class="marco col-md-12 col-xs-12">
    <div class="box">
      <h2 class="titulo"><i class="fas fa-cog"></i>Matríz de Agenda de Gestión Estratégica</h2>
    </div>
  </div>
  <div class="marco col-12">
    <div class="box">
      <div class="cuerpo text-start">
        <button class="btn {{$isFiltered?'btn-warning':'btn-secondary'}} text-white" data-bs-toggle="modal" data-bs-target="#filterModal">
          <svg class="icon">
            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-filter"></use>
          </svg> {{$isFiltered?'Filtrado':'Filtrar'}}
        </button>
      </div>
    </div>
  </div>
  <div class="marco col-12">
    <div class="box">
      <h3 class="titulo">{{$area?$area->nombre:'???'}}</h3>
      <div class="cuerpo text-center">
        <div class="card mb-4">
          <div class="card-header">Matriz de Objetivos</div>
          <div class="card-body overflow-auto">
            <table class="table table-bordered">
              <thead>
                  <tr>
                      <th class="text-center align-middle th-obj-cod" width="50">COD</th>
                      <th class="text-center align-middle th-obj-name" width="150">Objetivo</th>
                      <th class="text-center align-middle th-act-name" width="180">Actividades Principales</th>
                      <th class="text-center align-middle th-date-start" width="50">Fecha Inicio</th>
                      <th class="text-center align-middle th-date-end" width="50">Fecha Fin</th>
                      <th class="text-center align-middle th-policies" width="65" style="max-width: 60px!important;">Procedimiento/<br>Politica</th>
                      <th class="text-center align-middle th-adjacents" width="85" style="max-width: 55px!important;">Documento<br>Adjunto</th>
                      <th class="text-center align-middle th-status" width="60">Estado</th>
                  </tr>
              </thead>
              <tbody>
                <?php $i = 0; ?>
                @foreach ($roles as $role)
                  @if (roleHasActivities($role, $filter))
                    <tr class="td_role">
                      <td class="text-start t_role_row t_collapsible" colspan="100%" type="role">
                        <div class="float-end">
                          <button class="btn btn-light btn-sm" data-target=".collapseRole{{$role->id}}" data-bs-toggle="collapse">
                              <svg class="icon">
                                  <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-chevron-double-down"></use>
                              </svg>
                          </button>
                        </div>
                        <p class="m-0 pt-1">Rol {{$role->id}}: {{$role->nombre}}</p>
                      </td>
                    </tr>
                    <?php 
                        $x = 0; 
                        $themes = $role->themes;
                    ?>
                    @foreach ($themes as $theme)
                      @if (themeHasActivities($theme, $filter))
                        <tr class="td_theme border-w-0">
                            <td class="text-start t_theme_row t_collapsible" colspan="100%" type="theme">
                              <div class="collapse collapseRole{{$role->id}}">
                                <div class="td_content">
                                  <div class="float-end">
                                    <button class="btn btn-light btn-sm" data-target=".collapseTheme{{$theme->id}}" data-bs-toggle="collapse" data-parent=".collapseRole{{$role->id}}">
                                      <svg class="icon">
                                          <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-chevron-double-down"></use>
                                      </svg>
                                    </button>
                                  </div>
                                  <p class="m-0 pt-1">Tema {{$x+1}}: {{$theme->nombre}}</p>
                                  <div class="clearfix"></div>
                                </div>
                              </div>
                            </td>
                        </tr>
                        @foreach ($theme->objectives as $objective)
                          <?php 
                              $y = 0; 
                              $activities = filterActivities($objective->activities, $filter);
                          ?>
                          @foreach ($activities as $activity)
                          @if (valActivity($activity, $filter))
                            <tr class="td_activity border-w-0">
                              @if ($y == 0)
                              <td class="text-center align-middle t-obj-code" rowspan="{{sizeOf($activities)}}">
                                <div class="collapse collapseTheme{{$theme->id}}">
                                  <div class="td_content">
                                    Ob_{{$theme->id}}-{{$objective->id}}
                                  </div>
                                </div>
                              </td>
                              <td class="align-middle t-obj-name" rowspan="{{sizeOf($activities)}}">
                                <div class="collapse collapseTheme{{$theme->id}}">
                                  <div class="td_content">
                                    {{$objective->nombre}}
                                  </div>
                                </div>
                              </td>
                              @endif
                              <td class="align-middle t-act-name">
                                <div class="collapse collapseTheme{{$theme->id}}">
                                  <div class="td_content">
                                    {{$activity->nombre}}
                                  </div>
                                </div>
                              </td>
                              <td class="text-center align-middle t-date-start">
                                <div class="collapse collapseTheme{{$theme->id}}">
                                  <div class="td_content">
                                    {{date("d-m-Y", strtotime($activity->fecha_comienzo))}}
                                  </div>
                                </div>
                              </td>
                              <td class="text-center align-middle t-date-end">
                                <div class="collapse collapseTheme{{$theme->id}}">
                                  <div class="td_content">
                                    {{date("d-m-Y", strtotime($activity->fecha_fin))}}
                                  </div>
                                </div>
                              </td>
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
                                  <div class="collapse collapseTheme{{$theme->id}}">
                                    <div class="td_content">
                                      <a href="{{$docName?route('doc.download').'?id='.$docId:'javascript:;'}}" class="btn {{$docName?'btn-success':'btn-outline-secondary'}} btn-sm {{$docName?'text-white':''}} btn-show-policy">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-file"></use>
                                        </svg>
                                      </a>
                                    </div>
                                  </div>
                              </td>
                              <td class="text-center align-middle t-adjacents">
                                  @php
                                      $adjacents = $activity->docAdjacents;
                                  @endphp
                                  <div class="collapse collapseTheme{{$theme->id}}">
                                    <div class="td_content">
                                      <a href="javascript:;" class="btn {{sizeof($adjacents)>0?'btn-success':'btn-outline-secondary'}} btn-sm {{sizeof($adjacents)>0?'text-white':''}} {{sizeof($adjacents)>0?'btn-show-adjacent':''}}" data-route="{{route('front.activity.popup.adjacents')}}" data-id="{{$activity->id}}">
                                          <svg class="icon">
                                              <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-file"></use>
                                          </svg>
                                      </a>
                                    </div>
                                  </div>
                              </td>

                              @php
                                $s = ['t_red','t_yellow','t_green'];
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
                                            $status = 1; // if today is past 25%, status warning
                                        }

                                    }else if($d_end < $today){
                                        $status = 0; // time expired, not done = RED
                                    }
                                }
                              @endphp
                              <td class="{{$s[$status]}} t-status">
                                <div class="collapse collapseTheme{{$theme->id}}">
                                </div>
                              </td>
                            </tr>
                            <?php $y++; ?>
                          @endif
                          @endforeach
                        @endforeach
                      <?php $x++; ?>   
                      @endif   
                    @endforeach
                  @endif
                <?php $i++; ?>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="marco col-12">
    <div class="box">
      <h3 class="titulo">Leyenda</h3>
      <div class="cuerpo text-start">
        <p><span class="d-inline-block text-block t_green" style="width: 20px;">&nbsp;</span> <strong>Verde:</strong> Desde la fecha de inicio hasta faltando 25% de los días para la fecha de término.</p>
        <p><span class="d-inline-block text-block t_yellow" style="width: 20px;">&nbsp;</span> <strong>Amarillo:</strong> Entre el 25% de los días previo a la fecha de vencimiento hasta la fecha de vencimiento.</p>
        <p><span class="d-inline-block text-block t_red" style="width: 20px;">&nbsp;</span> <strong>Rojo:</strong> Cuando no se haya cumplido la accion y se ha vencido el plazo.</p>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="{{asset('js/front/matrix/script.js')}}"></script>
@endsection