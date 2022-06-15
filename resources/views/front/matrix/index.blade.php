<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Coelvisac</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/front/fontawesome/all.css')}}">
    <link rel="stylesheet" href="{{asset("css/front/estilos.css")}}">
    <style>
      .icon {
        width: 1rem;
        height: 1rem;
        font-size: 1rem;
      }
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
    </style>
  </head>
  <body class="content">
    <nav class="navbar header">
        <div class="fondo container-fluid">
            <div class="logo-int">
              <span onclick="openNav('menu','contenido')">&#9776;</span>
              <a href="index.html"><img src="{{asset("img/logo.png")}}" height="60" alt=""></a>
            </div>
            {{-- <form class="d-flex" role="search">
              <input class="form-control" type="search" placeholder="Buscar" aria-label="Search">
              <button class="btn btn-outline-success" type="submit"><i class="fas fa-search"></i></button>
            </form> --}}
            <div class="usuario d-flex">
                <a class="pt-1" href="{{route('login')}}">Login</a>
                <i class="fas fa-user"></i>
            </div>
        </div>
    </nav>

    <section class="interna">
      <section id="menu" class="sidenav">
        <div class="espaciado">
          <div class="accordion accordion-flush" id="menuInterna">
            <div class="accordion-item">
              <div class="accordion-header" id="menu1Head">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menu1Body" aria-expanded="false" aria-controls="menu1Body">
                  <i class="fas fa-cog"></i> 
                  <span>Matríz de Agenda de Gestión Estratégica</span>
                </button>
              </div>
              <div id="menu1Body" class="accordion-collapse collapse show" aria-labelledby="menu1Head" data-bs-parent="#menuInterna">
                <div class="accordion-body">
                  @foreach ($m_areas as $area)
                      <a href="{{route('front.activity.matrix.show').'?area='.$area->id}}">{{$area->nombre}}</a>
                  @endforeach
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <div class="accordion-header" id="menu2Head">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menu2Body" aria-expanded="false" aria-controls="menu2Body">
                  <i class="fas fa-suitcase"></i>
                  <span>Rol de Viajes</span>
                </button>
              </div>
              <div id="menu2Body" class="accordion-collapse collapse" aria-labelledby="menu2Head" data-bs-parent="#menuInterna">
                <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the second item's accordion body. Let's imagine this being filled with some actual content.</div>
              </div>
            </div>
            <div class="accordion-item">
              <div class="accordion-header" id="menu3Head">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menu3Body" aria-expanded="false" aria-controls="menu3Body">
                  <i class="fas fa-chart-bar"></i>
                  <span>Resultados</span>
                </button>
              </div>
              <div id="menu3Body" class="accordion-collapse collapse" aria-labelledby="menu3Head" data-bs-parent="#menuInterna">
                <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
              </div>
            </div>
            <div class="accordion-item">
              <div class="accordion-header" id="menu4Head">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menu4Body" aria-expanded="false" aria-controls="menu4Body">
                  <i class="fas fa-bezier-curve"></i>
                  <span>Reunión de Resultado mensual</span>                  
                </button>
              </div>
              <div id="menu4Body" class="accordion-collapse collapse" aria-labelledby="menu4Head" data-bs-parent="#menuInterna">
                <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
              </div>
            </div>
            <div class="accordion-item">
              <div class="accordion-header" id="menu5Head">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menu5Body" aria-expanded="false" aria-controls="menu5Body">
                  <i class="fas fa-file-alt"></i>
                  <span>Documentos de gestión</span>                  
                </button>
              </div>
              <div id="menu5Body" class="accordion-collapse collapse" aria-labelledby="menu5Head" data-bs-parent="#menuInterna">
                <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="contenido">
        <div class="container-fluid">
          <div class="row">
            <div class="marco col-md-12 col-xs-12">
              <div class="box">
                <h2 class="titulo"><i class="fas fa-cog"></i>Matríz de Agenda de Gestión Estratégica &raquo; <span>{{$area->nombre}}</span></h2>
              </div>
            </div>
            <div class="marco col-12">
              <div class="box">
                <h3 class="titulo">ESTADO DE OPERACIONES</h3>
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
                                  <th class="text-center align-middle" width="100">Fecha Inicio</th>
                                  <th class="text-center align-middle" width="100">Fecha Fin</th>
                                  <th class="text-center align-middle" width="65">Politica</th>
                                  <th class="text-center align-middle" width="85">Documento<br>Adjunto</th>
                                  <th class="text-center align-middle" width="80">Estado</th>
                              </tr>
                          </thead>
                          <tbody>
                            <?php $i = 0; ?>
                            @foreach ($roles as $role)
                              <tr>
                                <td class="text-start t_role_row" colspan="100%">Rol {{$role->id}}: {{$role->nombre}}</td>
                              </tr>
                              <?php 
                                  $x = 0; 
                                  $themes = $role->themes->where("estado", 1);
                              ?>
                              @foreach ($themes as $theme)
                                <tr>
                                    <td class="text-start t_theme_row" colspan="100%">Tema {{$x+1}}: {{$theme->nombre}}</td>
                                </tr>
                                @foreach ($theme->objectives->where("estado", 1) as $objective)
                                  <?php 
                                      $y = 0; 
                                      $activities = $objective->activities->where("estado", 1);
                                  ?>
                                  @foreach ($activities as $activity)
                                  <tr>
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
                                  @endforeach

                                @endforeach

                              @endforeach

                            @endforeach
                          </tbody>
                        </table>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <footer>
        </footer>
      </section>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
    <script src="{{asset('js/front/jquery-3.2.1.min.js')}}"></script>
    <script src="{{asset('js/front/jsFunctions.js')}}"></script>
    <script>
        $(document).ready(function() {
          openNav("menu", "contenido");
        });
    </script>
  </body>
</html>