

@extends('layouts.front')

@section('title', 'Kpi')
    
@section('style')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('css/front/kpis.css')}}">
@endsection

@section('content')

<div class="modal fade" id="highlightModal" tabindex="-1" aria-labelledby="highlightModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="highlightModalLabel">Highlights: <span id="hl-label"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="table-highlights">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="marco col-md-12 col-xs-12">
        <div class="box">
            <h2 class="titulo"><i class="fas fa-cog"></i>Indicador Clave Desempeño</h2>
        </div>
    </div>
    <div class="marco col-12">
        <div class="box">
            <h3 class="titulo">Pilares</h3>
            <div class="cuerpo">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="kpi_name">KPI</label>
                            <p class="p-2 mb-2 rounded border">{{$kpi->nombre}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="kpi_description">Descripción del indicador</label>
                            <p class="p-2 mb-2 rounded border">{{$kpi->descripcion}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="kpi_formula">Fórmula</label>
                            <p class="p-2 mb-2 rounded border">{{$kpi->formula}}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="kpi_name">Frecuencia</label>
                            <p class="p-2 mb-2 rounded border">{{$cicles[$kpi->frecuencia]['name']}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="kpi_name">Tipo</label>
                            <p class="p-2 mb-2 rounded border">{{$types[$kpi->tipo]['name']}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="kpi_name">Resultado Clave Anual</label>
                            <p class="p-2 mb-2 rounded border">{{$kpi->meta}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php
            $months = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
        @endphp
        <div class="box">
            <h3 class="titulo">{{date('Y')}}</h3>
            <div class="cuerpo">
                <div id="matrix_now">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="box">
            <h3 class="titulo">{{date('Y',strtotime('-1 year'))}}</h3>
            <div class="cuerpo">
                <div id="matrix_future">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <h3 class="titulo">POR CICLOS</h3>
                    <div class="cuerpo">
                        <div class="my-2">
                            <div class="card">
                                <div class="card-body">
                                    <canvas id="graph-bar"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="my-2">
                            <div class="card">
                                <div class="card-body">
                                    <canvas id="graph-line"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <h3 class="titulo">ACUMULADO</h3>
                    <div class="cuerpo">
                        <div class="my-2">
                            <div class="card">
                                <div class="card-body">
                                    <canvas id="graph-bar-acum"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="my-2">
                            <div class="card">
                                <div class="card-body">
                                    <canvas id="graph-line-acum"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var kpi = "{{$kpi->id}}";
    var ogType = "{{$kpi->tipo}}";
    var ogFrequency = "{{$kpi->frecuencia}}";
    var nowMatrixUrl = "{{route('front.kpi.matrix_now')}}";
    var futureMatrixUrl = "{{route('front.kpi.matrix_past')}}";
    var highlightsUrl = "{{route('front.kpi.highlights')}}";
    var graphUrl = "{{route('front.kpi.bar_now')}}";
</script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{asset('js/front/kpis/script.js')}}"></script>
@endsection