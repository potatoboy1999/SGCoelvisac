@extends('layouts.front')

@section('title', 'Matriz')
    
@section('style')
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
  tr.td_activity>* {
      padding: 0.2rem!important;
  }
</style>
@endsection

@section('content')
<div class="row">
  <div class="marco col-md-12 col-xs-12">
    <div class="box">
      <h2 class="titulo"><i class="fas fa-cog"></i>Matríz de Agenda de Gestión Estratégica</h2>
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
                        <th class="text-center align-middle" width="50">COD</th>
                        <th class="text-center align-middle" width="150">Objetivo</th>
                        <th class="text-center align-middle" width="180">Actividades Principales</th>
                        <th class="text-center align-middle" width="50">Fecha Inicio</th>
                        <th class="text-center align-middle" width="50">Fecha Fin</th>
                        <th class="text-center align-middle" width="65" style="max-width: 60px!important;">Procedimiento/<br>Politica</th>
                        <th class="text-center align-middle" width="85" style="max-width: 55px!important;">Documento<br>Adjunto</th>
                        <th class="text-center align-middle" width="60">Estado</th>
                    </tr>
                </thead>
                <tbody>
                  <?php $i = 0; ?>
                  @foreach ($roles as $role)
                    <tr class="td_role">
                      <td class="text-start t_role_row" colspan="100%">Rol {{$role->id}}: {{$role->nombre}}</td>
                    </tr>
                    <?php 
                        $x = 0; 
                        $themes = $role->themes->where("estado", 1);
                    ?>
                    @foreach ($themes as $theme)
                      <tr class="td_theme">
                          <td class="text-start t_theme_row" colspan="100%">Tema {{$x+1}}: {{$theme->nombre}}</td>
                      </tr>
                      @foreach ($theme->objectives->where("estado", 1) as $objective)
                        <?php 
                            $y = 0; 
                            $activities = $objective->activities->where("estado", 1);
                        ?>
                        @foreach ($activities as $activity)
                        <tr class="td_activity">
                          @if ($y == 0)
                          <td class="text-center align-middle" rowspan="{{sizeOf($activities)}}">
                            Ob_{{$theme->id}}-{{$objective->id}}
                          </td>
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
                              <a href="{{$docName?route('front.doc.download').'?id='.$docId:'javascript:;'}}" class="btn {{$docName?'btn-success':'btn-outline-secondary'}} btn-sm {{$docName?'text-white':''}} btn-show-policy" style="{{$docName?'':'width: 34px;'}}">
                                @if ($docName)
                                  <svg class="icon">
                                      <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-file"></use>
                                  </svg>
                                @else
                                  <span>?</span>
                                @endif
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
                              <a href="{{$docName?route('front.doc.download').'?id='.$docId:'javascript:;'}}" class="btn {{$docName?'btn-success':'btn-outline-secondary'}} btn-sm {{$docName?'text-white':''}} btn-show-policy" style="{{$docName?'':'width: 34px;'}}">
                                @if ($docName)
                                  <svg class="icon">
                                      <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-file"></use>
                                  </svg>
                                @else
                                  <span>?</span>
                                @endif
                              </a>
                          </td>
                          <td class="t_red"></td>
                        </tr>
                        <?php $y++; ?>
                        @endforeach
                      @endforeach
                    <?php $x++; ?>      
                    @endforeach
                  <?php $i++; ?>
                  @endforeach
                </tbody>
              </table>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')

@endsection