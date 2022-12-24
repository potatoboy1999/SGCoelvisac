@extends('layouts.admin')

@section('title', 'Objetivo Especifico')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="{{asset('css/intranet/specific_actions.css')}}">
@endsection

@section('content')
<div class="body flex-grow-1 px-3">
    <div class="container">
        <h4>OBJETIVO ESPECÍFICO</h4>
        <div class="card mb-4">
            <div class="card-body">
                <p class="m-0">{{$strat->nombre}}</p>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-bordered m-0">
                            <tr>
                                <td class="side-title align-middle" align="center">Pilar</td>
                                <td class="align-middle" align="center">{{$strat->dimension->pilar->nombre}}</td>
                            </tr>
                            <tr>
                                <td class="side-title" align="center">Dimensión</td>
                                <td class="align-middle" align="center">{{$strat->dimension->nombre}}</td>
                            </tr>
                            <tr>
                                <td class="side-title" align="center">Código</td>
                                <td class="align-middle" align="center">{{$strat->codigo}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-row flex-wrap">
                    <div class="p-1">
                        <a href="#" class="btn btn-success text-white">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-plus"></use>
                            </svg> Nuevo Item
                        </a>
                    </div>
                    {{-- <div class="p-1">
                        <button type="button" class="btn btn-secondary text-white" data-coreui-toggle="modal" data-coreui-target="#filterModal">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-filter"></use>
                            </svg> Filtrar
                        </button>
                    </div> --}}
                </div>
            </div>
        </div>
        <div id="matrix_content">
            <div class="spinner-border" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Leyenda</div>
            <div class="card-body">
                <p>
                    <span class="d-inline-block text-block t_gray" style="width: 20px;">&nbsp;</span> 
                    <strong>Gris:</strong> La actividad aun no ha comenzado
                </p>
                <p>
                    <span class="d-inline-block text-block t_green" style="width: 20px;">&nbsp;</span> 
                    <strong>Verde:</strong> Desde la fecha de inicio hasta faltando 25% de los días para la fecha de término.
                </p>
                <p>
                    <span class="d-inline-block text-block t_yellow" style="width: 20px;">&nbsp;</span>
                    <strong>Amarillo:</strong> Entre el 25% de los días previo a la fecha de vencimiento hasta la fecha de vencimiento.
                </p>
                <p>
                    <span class="d-inline-block text-block t_blue" style="width: 20px;">&nbsp;</span> 
                    <strong>Azul:</strong> La actividad ha sido cumplida.
                </p>
                <p>
                    <span class="d-inline-block text-block t_red" style="width: 20px;">&nbsp;</span>
                    <strong>Rojo:</strong> Cuando no se haya cumplido la accion y se ha vencido el plazo.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let stratId = "{{$strat->id}}";
    let matrixUrl = "{{route('actions.matrix')}}";
</script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
<script src="{{asset("js/intranet/specific_actions.js")}}"></script>
<script>
    
</script>

@endsection