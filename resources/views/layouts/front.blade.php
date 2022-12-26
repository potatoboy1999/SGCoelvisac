<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Coelvisac</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/front/fontawesome/all.css')}}">
    <link rel="stylesheet" href="{{asset("css/front/estilos.css")}}">
    <link rel="stylesheet" href="{{asset("css/front_global.css")}}">
    <style>
      .icon {
        width: 1rem;
        height: 1rem;
        font-size: 1rem;
      }
    </style>
    @yield('style')
  </head>
  <body class="content">
    <nav class="navbar header">
        <div class="fondo container-fluid">
            <div class="logo-int">
              <span onclick="openNav('menu','contenido')">&#9776;</span>
              <a href="{{url('')}}"><img src="{{asset("img/logo.png")}}" height="60" alt=""></a>
            </div>
            {{-- <form class="d-flex" role="search">
              <input class="form-control" type="search" placeholder="Buscar" aria-label="Search">
              <button class="btn btn-outline-success" type="submit"><i class="fas fa-search"></i></button>
            </form> --}}
            <div class="usuario d-flex">
                <a class="pt-1" href="{{route('login')}}" target="_blank">Login</a>
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
              <div id="menu1Body" class="accordion-collapse collapse {{$page == 'matrix'? 'show':''}}" aria-labelledby="menu1Head" data-bs-parent="#menuInterna">
                <div class="accordion-body">
                  <a href="{{route('front.objectives')}}">Objetivos Estratégicos</a>
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
              <div id="menu2Body" class="accordion-collapse collapse {{$page == 'schedules'? 'show':''}}" aria-labelledby="menu2Head" data-bs-parent="#menuInterna">
                <div class="accordion-body">
                  <a href="{{route('front.schedules')}}">Calendario</a>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <div class="accordion-header" id="menu3Head">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menu3Body" aria-expanded="false" aria-controls="menu3Body">
                  <i class="fas fa-chart-bar"></i>
                  <span>Resultados</span>
                </button>
              </div>
              <div id="menu3Body" class="accordion-collapse collapse {{$page == 'results'? 'show':''}}" aria-labelledby="menu3Head" data-bs-parent="#menuInterna">
                <div class="accordion-body">
                  {{-- <a href="">here comes a link</a> --}}
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <div class="accordion-header" id="menu4Head">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menu4Body" aria-expanded="false" aria-controls="menu4Body">
                  <i class="fas fa-bezier-curve"></i>
                  <span>Reunión de Resultado mensual</span>                  
                </button>
              </div>
              <div id="menu4Body" class="accordion-collapse collapse {{$page == 'result_reunion'? 'show':''}}" aria-labelledby="menu4Head" data-bs-parent="#menuInterna">
                <div class="accordion-body">
                  <a href="{{route('front.reunions')}}">Calendario</a>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <div class="accordion-header" id="menu5Head">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menu5Body" aria-expanded="false" aria-controls="menu5Body">
                  <i class="fas fa-file-alt"></i>
                  <span>Documentos de gestión</span>                  
                </button>
              </div>
              <div id="menu5Body" class="accordion-collapse collapse {{$page == 'documents'? 'show':''}}" aria-labelledby="menu5Head" data-bs-parent="#menuInterna">
                <div class="accordion-body">
                  {{-- <a href="">here comes a link</a> --}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="contenido">
        <div class="container-fluid">

            @yield('content')

        </div>
      </section>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="{{asset('js/front/jquery-3.2.1.min.js')}}"></script>
    <script src="{{asset('js/front/jsFunctions.js')}}"></script>
    <script>
      $(document).ready(function() {
        let navegador = navigator.userAgent;
        if (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i)) {
          closeNav("menu", "contenido");
        } else {
          openNav("menu", "contenido");
        }
      });
      // $('#datepicker').datepicker('show');
    </script>
    {{-- <script>
        $(document).ready(function() {
          openNav("menu", "contenido");
        });
    </script> --}}

    @yield('script')

  </body>
</html>