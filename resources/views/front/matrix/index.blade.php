@extends('layouts.front')

@section('title', 'Matriz')
    
@section('style')
<style>
  .collapsing {
    -webkit-transition: height .10s ease;
         -o-transition: height .10s ease;
            transition: height .10s ease;
  }
  thead tr th{
    background-color: #51607c!important;
    color: white!important;
  }
  .rol-header{
    background-color: #4190af;
    color: white;
  }
  tr.border-w-0{
    border-width: 0;
  }
  tr.border-w-0 td{
    border-width: 0;
  }
  td.t_role_row{
    background-color: #8b9bb7!important;
    /* border-width: 6px 1px!important;
    border-left-color: #8b9bb7;
    border-right-color: #8b9bb7; */
  }
  td.t_theme_row{
    background-color: #cccccc!important;
    /* border-width: 3px 1px!important; 
    border-left-color: #cccccc;
    border-right-color: #cccccc; */
  }
  .t_red {
      background-color: #ec1d1d!important;
  }
  .t_green {
      background-color: #12c212!important;
  }
  .t_yellow {
      background-color: #f9e715!important;
  }
  .text-block{
    padding: 0.1rem;
    border-radius: 5px;
  }
  tr.td_activity>*, tr.td_theme>* {
    padding: 0!important;
  }
  tr.td_theme .td_content{
    padding: 0.5rem!important;
  }
  .td_content{
    padding: 0.2rem!important;
  }
  .file-downloadable {
      padding: 0.5rem;
      border: 1px solid #ccc;
      border-radius: 0.5rem;
      border-color: #2eb85c;
  }
  .file-downloadable p{
      margin: 0;
  }
</style>
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
                      $themes = $role->themes->where("estado", 1);
                  ?>
                  @foreach ($themes as $theme)
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
                    @foreach ($theme->objectives->where("estado", 1) as $objective)
                      <?php 
                          $y = 0; 
                          $activities = $objective->activities->where("estado", 1);
                      ?>
                      @foreach ($activities as $activity)
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
                                <a href="javascript:;" class="btn {{sizeof($adjacents)>0?'btn-success':'btn-outline-secondary'}} btn-sm {{sizeof($adjacents)>0?'text-white':''}} btn-show-adjacent" data-route="{{route('front.activity.popup.adjacents')}}" data-id="{{$activity->id}}">
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
<script src="{{asset('js/front/matrix/script.js')}}"></script>
@endsection