@extends('layouts.admin')

@section('title', 'KPI')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="{{asset('css/intranet/kpi.css')}}">
@endsection

@section('content')
<div class="modal fade" id="highlightModal" tabindex="-1" aria-labelledby="highlightModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="highlightModalLabel">Highlights: <span id="hl-label"></span></h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="table-highlights">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
                @csrf
                <div class="form-group mt-3">
                    <label>Agregar</label>
                    <div class="input-group my-1">
                        <input type="text" class="form-control" name="highlight_desc">
                        <a href="{{route('kpi.highlights.store')}}" id="btn-add-high" class="btn btn-sm btn-success text-white" kpidate="" style="padding-top: 7px;">
                            <i class="fa-solid fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3">
    <div class="container">
        <h4>INDICADOR CLAVE DESEMPEÑO (KPI)</h4>
        <form id="kpi_form" action="{{$kpi?route('kpi.update'):route('kpi.store')}}" method="POST">
            @csrf
            @if ($kpi)
                <input type="hidden" name="objective" value="{{$kpi->objective->id}}">
            @elseif($obj)
                <input type="hidden" name="objective" value="{{$obj->id}}">
            @endif
            <input type="hidden" name="id" value="{{$kpi?$kpi->id:''}}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="kpi_name">KPI</label>
                        <input class="form-control" type="text" name="kpi" id="kpi_name" value="{{$kpi?$kpi->nombre:''}}" placeholder="Nombre del Indicador" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="kpi_description">Descripción del indicador</label>
                        <input class="form-control" type="text" name="description" id="kpi_description" value="{{$kpi?$kpi->descripcion:''}}" placeholder="¿Para qué se usa?" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="kpi_formula">Fórmula</label>
                        <input class="form-control" type="text" name="formula" id="kpi_formula" value="{{$kpi?$kpi->formula:''}}" placeholder="¿Cómo se calcula?" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="kpi_frequency">Frecuencia</label>
                        <select class="form-select" name="frequency" id="kpi_frequency">
                            <option value="men" {{$kpi?($kpi->frecuencia == "men"?'selected':''):''}}>Mensual</option>
                            <option value="bim" {{$kpi?($kpi->frecuencia == "bim"?'selected':''):''}}>Bimensual</option>
                            <option value="tri" {{$kpi?($kpi->frecuencia == "tri"?'selected':''):''}}>Trimestral</option>
                            <option value="sem" {{$kpi?($kpi->frecuencia == "sem"?'selected':''):''}}>Semestral</option>
                            <option value="anu" {{$kpi?($kpi->frecuencia == "anu"?'selected':''):''}}>Anual</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="kpi_type">Tipo</label>
                        <select class="form-select" name="type" id="kpi_type">
                            <option value="per" {{$kpi?($kpi->tipo == "per"?'selected':''):''}}>Porcentaje</option>
                            <option value="mon" {{$kpi?($kpi->tipo == "mon"?'selected':''):''}}>Moneda</option>
                            <option value="doc" {{$kpi?($kpi->tipo == "doc"?'selected':''):''}}>Entregable</option>
                            <option value="uni" {{$kpi?($kpi->tipo == "uni"?'selected':''):''}}>Unidad</option>
                            <option value="rat" {{$kpi?($kpi->tipo == "rat"?'selected':''):''}}>Ratio</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="kpi_meta">Resultado Clave Anual</label>
                        <input class="form-control" type="text" name="meta" id="kpi_meta" value="{{$kpi?$kpi->meta:''}}" placeholder="¿Cuál es la meta esperada?" required>
                    </div>
                </div>
            </div>
            @php
                $months = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
            @endphp
            <div class="kpi_dates">
                <h3 id="year_now">{{date('Y')}}</h3>
                <div id="matrix_now">
                    @if ($kpi)
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                    @else
                    <div class="card mb-4">
                        <div class="card-body p-0">
                            <table class="table table-bordered m-0">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" width="110">Metas</th>
                                        @for ($i = 0; $i < 12; $i++)
                                            <th class="text-center align-middle f-14">
                                                {{$months[$i]}}
                                            </th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center align-middle">Real</td>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <td class="text-center align-middle p-0">
                                                <input class="form-control input-number border-0 text-center" type="number" name="real_cicle[]" value="0">
                                            </td>
                                        @endfor
                                    </tr>
                                    <tr>
                                        <td class="text-center align-middle">Planificado</td>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <td class="text-center align-middle p-0">
                                                <input class="form-control input-number border-0 text-center" type="number" name="plan_cicle[]" value="0">
                                            </td>
                                        @endfor
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="kpi_dates">
                <h3 id="year_future">{{date('Y',strtotime('+1 year'))}}</h3>
                <div id="matrix_future">
                    @if ($kpi)
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                    @else
                    <div class="card mb-4">
                        <div class="card-body p-0">
                            <table class="table table-bordered m-0">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" width="110">Metas</th>
                                        @for ($i = 0; $i < 12; $i++)
                                            <th class="text-center align-middle f-14">
                                                {{$months[$i]}}
                                            </th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center align-middle">Planificado</td>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <td class="text-center align-middle p-0">
                                                <input class="form-control input-number border-0 text-center" type="number" name="plan_futurecicle[]" value="0">
                                            </td>
                                        @endfor
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <input class="btn btn-success text-white" type="submit" value="Guardar">
        </form>
        <div class="row my-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center p-1">
                        MENSUAL
                    </div>
                </div>
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
                            <canvas id="graph-bar-acum"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center p-1">
                        ACUMULADO
                    </div>
                </div>
                <div class="my-2">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="graph-line"></canvas>
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
@endsection

@section('script')
<script>
    var kpi = "{{$kpi?$kpi->id:''}}";
    var ogType = "{{$kpi?$kpi->tipo:'per'}}";
    var ogFrequency = "{{$kpi?$kpi->frecuencia:'men'}}";
    var nowMatrixUrl = "{{route('kpi.matrix_now')}}";
    var futureMatrixUrl = "{{route('kpi.matrix_future')}}";
    var highlightsUrl = "{{route('kpi.highlights')}}";
    var addHighUrl = "{{route('kpi.highlights.store')}}";
    var rmvHighUrl = "{{route('kpi.highlights.delete')}}";
    var graphUrl = "{{route('kpi.bar_now')}}";
    var cicles = JSON.parse('{!! $cicles !!}');
</script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{asset("js/intranet/kpi.js")}}"></script>
<script>
    
</script>

@endsection