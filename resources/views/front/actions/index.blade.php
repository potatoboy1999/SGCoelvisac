

@extends('layouts.front')

@section('title', 'Matriz')
    
@section('style')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('css/front/actions.css')}}">
@endsection

@section('content')

<div class="modal fade" id="docsModal" tabindex="-1" aria-labelledby="docsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="actionModalLabel">Documentos<span id="hl-label"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-section" id="form-docs-action">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
                <div class="modal-section" id="form-docs-loading" style="display: none">
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
            <h2 class="titulo"><i class="fas fa-cog"></i>Objetivo Específico</h2>
        </div>
    </div>
    <div class="marco col-12">
        <div class="box">
            <h3 class="titulo">{{$obj->nombre}}</h3>
            <div class="cuerpo">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-bordered m-0">
                            <tr>
                                <td class="side-title align-middle" align="center">Pilar</td>
                                <td class="align-middle" align="center">{{$obj->stratObjective->dimension->pilar->nombre}}</td>
                            </tr>
                            <tr>
                                <td class="side-title" align="center">Dimensión</td>
                                <td class="align-middle" align="center">{{$obj->stratObjective->dimension->nombre}}</td>
                            </tr>
                            <tr>
                                <td class="side-title" align="center">Código</td>
                                <td class="align-middle" align="center">{{$obj->codigo}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                {{--<div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-row flex-wrap">
                             <div class="p-1">
                                <button type="button" class="btn btn-secondary text-white" data-bs-toggle="modal" data-bs-target="#filterModal">
                                    <svg class="icon">
                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-filter"></use>
                                    </svg> Filtrar
                                </button>
                            </div> 
                        </div>
                    </div>
                </div>--}}
                <div id="matrix_content">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="marco col-12">
        <div class="box">
            <h3 class="titulo">Leyenda</h3>
            <div class="cuerpo text-start">
                <p><span class="d-inline-block text-block t_gray" style="width: 20px;">&nbsp;</span> <strong>Gris:</strong> La actividad aun no ha comenzado.</p>
                <p><span class="d-inline-block text-block t_green" style="width: 20px;">&nbsp;</span> <strong>Verde:</strong> Desde la fecha de inicio hasta faltando 25% de los días para la fecha de término.</p>
                <p><span class="d-inline-block text-block t_yellow" style="width: 20px;">&nbsp;</span> <strong>Amarillo:</strong> Entre el 25% de los días previo a la fecha de vencimiento hasta la fecha de vencimiento.</p>
                <p><span class="d-inline-block text-block t_blue" style="width: 20px;">&nbsp;</span> <strong>Azul:</strong> La actividad ha sido cumplida.</p>
                <p><span class="d-inline-block text-block t_red" style="width: 20px;">&nbsp;</span> <strong>Rojo:</strong> Cuando no se haya cumplido la accion y se ha vencido el plazo.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let objId = "{{$obj->id}}";
    let matrixUrl = "{{route('front.actions.matrix')}}";
    let docsFormUrl = "{{route('front.actions.popup.docs')}}";
</script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="{{asset('js/front/actions/script.js')}}"></script>
@endsection