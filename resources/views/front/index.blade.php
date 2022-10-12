<!DOCTYPE html>
<!-- saved from url=(0026)https://disac.info/roger/# -->
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Coelvisac</title>
    <link rel="stylesheet" href="{{asset("css/front/bootstrap.min.css")}}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset("css/front/all.css")}}">
    <link rel="stylesheet" href="{{asset("css/front/owl.carousel.css")}}">
    <link rel="stylesheet" href="{{asset("css/front/owl.theme.default.css")}}">
    <link rel="stylesheet" href="{{asset("css/front/owl.css")}}">
    <link rel="stylesheet" href="{{asset("css/front/estilos.css")}}">
</head>

<body class="home">
    <nav class="navbar navbar-default navbar-static-top header">
        <div class="fondo container-fluid">
            <img class="logo" height="80" src="{{asset("img/logo.png")}}" alt="">

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

    <div class="home-contenido">
        <div class="container">
            <h1>Aplicativo de Gestión</h1>
            <div class="opciones">
                <div class="row">
                    <div class="col-3">
                        <div class="box">
                            <a href="{{route('front.menu')}}">
                                <i class="fas fa-cog"></i>
                                Matriz de Agenda de Gestión Estratégica
                            </a>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="box">
                            <a href="{{route('front.schedules')}}">
                                <i class="fas fa-suitcase"></i>
                                Rol de viajes
                            </a>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="box">
                            <a href="javascript:;">
                                <i class="fas fa-chart-bar"></i>
                                Resultados
                            </a>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="box">
                            <a href="{{route('front.reunions')}}">
                                <i class="fas fa-bezier-curve"></i>
                                Reunión de Resultado mensual
                            </a>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="box">
                            <a href="javascript:;">
                                <i class="fas fa-file-alt"></i>
                                Documentos de gestión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="owl-carousel owl-theme">
        <div class="owl-slide d-flex align-items-center cover" style="background-image: url({{asset("img/banner1.jpg")}});">
          <div class="container">
            <div class="row justify-content-center justify-content-md-start">
              <div class="col-10 col-md-6 static">
                <div class="owl-slide-text">&nbsp;
                </div>
              </div>
            </div>
          </div>
        </div>
      
        <div class="owl-slide d-flex align-items-center cover" style="background-image: url({{asset("img/banner2.jpg")}});">
          <div class="container">
            <div class="row justify-content-center justify-content-md-start">
              <div class="col-10 col-md-6 static">
                <div class="owl-slide-text">&nbsp;
                </div>
              </div>
            </div>
          </div>
        </div>
      
        <div class="owl-slide d-flex align-items-center cover" style="background-image: url({{asset("img/banner3.jpg")}});">
          <div class="container">
            <div class="row justify-content-center justify-content-md-start">
              <div class="col-10 col-md-6 static">
                <div class="owl-slide-text">&nbsp;
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    <script src="{{asset("js/front/popper.min.js")}}" crossorigin="anonymous">
    </script>
    <script src="{{asset("js/front/bootstrap.min.js")}}" crossorigin="anonymous">
    </script>
    <script src="{{asset("js/front/jquery-3.2.1.min.js")}}"></script>
    <script src="{{asset("js/front/owl.carousel.js")}}"></script>
    <script src="{{asset("js/front/owl.js")}}"></script>
    <script>
        $(document).ready(function() {

        });
        $(".owl-carousel").on("initialized.owl.carousel", () => {
            setTimeout(() => {
                $(".owl-item.active .owl-slide-animated").addClass("is-transitioned");
                $("section").show();
            }, 200);
        });

        const $owlCarousel = $(".owl-carousel").owlCarousel({
            autoplay: true,
            autoplayTimeout: 5000,
            items: 1,
            loop: true,
            nav: false
        });

        $owlCarousel.on("changed.owl.carousel", e => {
            $(".owl-slide-animated").removeClass("is-transitioned");

            const $currentOwlItem = $(".owl-item").eq(e.item.index);
            $currentOwlItem.find(".owl-slide-animated").addClass("is-transitioned");

            const $target = $currentOwlItem.find(".owl-slide-text");
            doDotsCalculations($target);
        });

        $owlCarousel.on("resize.owl.carousel", () => {
            setTimeout(() => {
                setOwlDotsPosition();
            }, 50);
        });

        setOwlDotsPosition();

        function setOwlDotsPosition() {
            const $target = $(".owl-item.active .owl-slide-text");
            doDotsCalculations($target);
        }

        function doDotsCalculations(el) {
            const height = el.height();
            const {
                top,
                left
            } = el.position();
            const res = height + top + 20;

            $(".owl-carousel .owl-dots").css({
                top: `${res}px`,
                left: `${left}px`
            });
        }
    </script>

</body>

</html>
