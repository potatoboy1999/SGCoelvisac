<!DOCTYPE html>

<!-- Breadcrumb-->
<html>
    <head>
        <base href="./">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
        <meta name="author" content="Łukasz Holeczek">
        <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
        <title>COELVISAC - @yield('title')</title>
        <link rel="apple-touch-icon" sizes="57x57" href="{{asset("favicon/apple-icon-57x57.png")}}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{asset("favicon/apple-icon-60x60.png")}}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{asset("favicon/apple-icon-72x72.png")}}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{asset("favicon/apple-icon-76x76.png")}}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{asset("favicon/apple-icon-114x114.png")}}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{asset("favicon/apple-icon-120x120.png")}}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{asset("favicon/apple-icon-144x144.png")}}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{asset("favicon/apple-icon-152x152.png")}}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{asset("favicon/apple-icon-180x180.png")}}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{asset("favicon/android-icon-192x192.png")}}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{asset("favicon/favicon-32x32.png")}}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{asset("favicon/favicon-96x96.png")}}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{asset("favicon/favicon-16x16.png")}}">
        <link rel="manifest" href="{{asset("favicon/manifest.json")}}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{asset("favicon/ms-icon-144x144.png")}}">
        <meta name="theme-color" content="#ffffff">
        <!-- Vendors styles-->
        <link rel="stylesheet" href="{{asset("css/node_vendors/simplebar.css")}}">
        <link rel="stylesheet" href="{{asset("css/vendors/simplebar.css")}}">
        <!-- Main styles for this application-->
        <link href="{{asset('css/style.css')}}" rel="stylesheet">
        <!-- Global site tag (gtag.js) - Google Analytics-->
        <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-118965717-3"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            // Shared ID
            gtag('config', 'UA-118965717-3');
            // Bootstrap ID
            gtag('config', 'UA-118965717-5');
        </script>
        <link href="{{asset("css/node_vendors/coreui-chartjs.css")}}" rel="stylesheet">
        @yield('style')
    </head>
    <body>
        <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
            <div class="sidebar-brand d-none d-md-flex">
                <svg class="sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
                    <use xlink:href="{{asset("icons/coreui.svg#full")}}"></use>
                </svg>
                <svg class="sidebar-brand-narrow" width="46" height="46" alt="CoreUI Logo">
                    <use xlink:href="{{asset("icons/coreui.svg#signet")}}"></use>
                </svg>
            </div>
            <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
                <li class="nav-item">
                    <a class="nav-link {{((isset($page) && $page == "dashboard") ? "active":"")}}" href="#">
                        <svg class="nav-icon">
                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-speedometer"></use>
                        </svg> Dashboard
                    </a>
                </li>
                @foreach (Auth::user()->profiles as $profile)
                    @foreach ($profile->options
                                ->whereNull("opcion_padre_id")
                                ->where("tipo_opcion",1)
                                ->where("estado",1)
                                ->sortBy("num_nivel") as $parentOption)
                        <li class="nav-title">{{$parentOption->opcion}}</li>

                        @foreach ($profile->options
                                    ->where("opcion_padre_id",$parentOption->id)
                                    ->where("estado",1)
                                    ->sortBy("num_orden") as $option)
                            <li class="nav-item">
                                <a class="nav-link" href="{{($option->url != null)?route($option->url):""}}">
                                    <svg class="nav-icon">
                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#{{$option->url_img}}"></use>
                                    </svg> {{$option->opcion}}
                                </a>
                            </li>
                        @endforeach
                    @endforeach
                @endforeach
                <li class="nav-title">Otros</li>
                <li class="nav-group">
                    <a class="nav-link nav-group-toggle" href="#">
                        <svg class="nav-icon">
                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-bell"></use>
                        </svg> Notifications
                    </a>
                    <ul class="nav-group-items">
                        <li class="nav-item"><a class="nav-link" href="#"><span class="nav-icon"></span> Alerts</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"><span class="nav-icon"></span> Badge</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"><span class="nav-icon"></span> Modals</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"><span class="nav-icon"></span> Toasts</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="wrapper d-flex flex-column min-vh-100 bg-light">
            <header class="header header-sticky mb-4">
                <div class="container-fluid">
                    <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
                        <svg class="icon icon-lg">
                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-menu"></use>
                        </svg>
                    </button>
                    <a class="header-brand d-md-none" href="#">
                        <svg width="118" height="46" alt="CoreUI Logo">
                            <use xlink:href="{{asset("icons/coreui.svg#full")}}"></use>
                        </svg>
                    </a>
                    <ul class="header-nav ms-3">
                    <li class="nav-item dropdown"><a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar avatar-md"><img class="avatar-img" src="{{asset("img/avatars/8.jpg")}}" alt="user@email.com"></div></a>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <div class="dropdown-header bg-light py-2">
                                <div class="fw-semibold">Account: {{Auth::user()->nombre}}</div>
                            </div>
                            <!--<a class="dropdown-item" href="#">
                                <svg class="icon me-2">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-bell"></use>
                                </svg> Updates<span class="badge badge-sm bg-info ms-2">42</span>
                            </a>
                            <a class="dropdown-item" href="#">
                                <svg class="icon me-2">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-envelope-open"></use>
                                </svg> Messages<span class="badge badge-sm bg-success ms-2">42</span>
                            </a>
                            <a class="dropdown-item" href="#">
                                <svg class="icon me-2">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-task"></use>
                                </svg> Tasks<span class="badge badge-sm bg-danger ms-2">42</span>
                            </a>
                            <a class="dropdown-item" href="#">
                                <svg class="icon me-2">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-comment-square"></use>
                                </svg> Comments<span class="badge badge-sm bg-warning ms-2">42</span>
                            </a>
                            <div class="dropdown-header bg-light py-2">
                                <div class="fw-semibold">Settings</div>
                            </div>-->
                            <a class="dropdown-item" href="#">
                                <svg class="icon me-2">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-user"></use>
                                </svg> Profile
                            </a>
                            <a class="dropdown-item" href="#">
                                <svg class="icon me-2">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-settings"></use>
                                </svg> Settings
                            </a>
                            <!--
                            <a class="dropdown-item" href="#">
                                <svg class="icon me-2">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-credit-card"></use>
                                </svg> Payments<span class="badge badge-sm bg-secondary ms-2">42</span>
                            </a>-->
                            <a class="dropdown-item" href="#">
                                <svg class="icon me-2">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-file"></use>
                                </svg> Projects
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout').submit();">
                                <svg class="icon me-2">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-account-logout"></use>
                                </svg> {{ __('Log Out') }}
                            </a>
                            <form id="logout" method="POST" action="{{ route('logout') }}">
                                @csrf
                            </form>
                        </div>
                    </li>
                    </ul>
                </div>
                <div class="header-divider"></div>
                <div class="container-fluid">
                    <nav aria-label="breadcrumb">
                    <ol class="breadcrumb my-0 ms-2">
                        @for ($i = 0; $i < sizeOf($bcrums); $i++)
                            <li class="breadcrumb-item {{(($i+1) == sizeOf($bcrums))?"active":""}}">
                                @if (($i+1) < sizeOf($bcrums))
                                    <a href="#">{{$bcrums[$i]}}</a>
                                @else
                                    <span>{{$bcrums[$i]}}</span>
                                @endif
                            </li>
                        @endfor
                    </ol>
                    </nav>
                </div>
            </header>
            @yield('content')
        </div>
        <!-- CoreUI and necessary plugins-->
        <script src="{{asset("js/node_vendors/coreui.bundle.min.js")}}"></script>
        <script src="{{asset("js/node_vendors/simplebar.min.js")}}"></script>
        <!-- Plugins and scripts required by this view-->
        <script src="{{asset("js/node_vendors/chart.min.js")}}"></script>
        <script src="{{asset("js/node_vendors/coreui-chartjs.js")}}"></script>
        <script src="{{asset("js/node_vendors/coreui-utils.js")}}"></script>
        <script src="{{asset("js/main.js")}}"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="crossorigin="anonymous"></script>
        @yield('script')
    </body>
</html>