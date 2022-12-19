@extends('layouts.admin')

@section('title', 'KPI')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="{{asset('css/intranet/kpi.css')}}">
@endsection

@section('content')
<div class="body flex-grow-1 px-3">
    <div class="container">
        <h4>INDICADOR CLAVE DESEMPEÑO (KPI)</h4>
        <form action="">
            @if ($kpi)
                <input type="hidden" name="objective" value="{{$kpi->objective->id}}">
            @elseif($obj)
                <input type="hidden" name="objective" value="{{$obj->id}}">
            @endif
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="kpi_name">KPI</label>
                        <input class="form-control" type="text" name="kpi" id="kpi_name" value="{{$kpi?$kpi->nombre:''}}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="kpi_description">Descripción del indicador</label>
                        <input class="form-control" type="text" name="description" id="kpi_description" value="{{$kpi?$kpi->descripcion:''}}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="kpi_formula">Fórmula</label>
                        <input class="form-control" type="text" name="formula" id="kpi_formula" value="{{$kpi?$kpi->formula:''}}" required>
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
                        <input class="form-control" type="text" name="meta" id="kpi_meta" value="{{$kpi?$kpi->meta:''}}" required>
                    </div>
                </div>
            </div>
            <div class="kpi_dates">
                <h3 id="year_now">{{date('Y')}}</h3>
                <div id="matrix_now">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </div>
            <div class="kpi_dates">
                <h3 id="year_future">{{date('Y',strtotime('+1 year'))}}</h3>
                <div id="matrix_future">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </div>
        </form>
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
</script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
<script src="{{asset("js/intranet/kpi.js")}}"></script>
<script>
    
</script>

@endsection