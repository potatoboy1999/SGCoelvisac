

@extends('layouts.front')

@section('title', 'Matriz')
    
@section('style')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('css/front/specifics.css')}}">
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

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filtros</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>
<!-- End Filter Modal -->

<div class="row">
    <div class="marco col-md-12 col-xs-12">
        <div class="box">
            <h2 class="titulo"><i class="fas fa-cog"></i>Planeamiento Estratégico</h2>
        </div>
    </div>
    <div class="marco col-12">
        <div class="box">
            <h3 class="titulo">{{$strat->nombre}}</h3>
            <div class="cuerpo">
                <div class="row mb-4">
                    <div class="col-md-6">
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
                    <div id="matrix_summary" class="col-md-6">
                        <div class="spinner-border" role="status">
                            <span class="sr-only"></span>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-row flex-wrap">
                            {{-- <div class="p-1">
                                <button type="button" class="btn btn-secondary text-white" data-bs-toggle="modal" data-bs-target="#filterModal">
                                    <svg class="icon">
                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-filter"></use>
                                    </svg> Filtrar
                                </button>
                            </div> --}}
                            <div class="p-1">
                                <a href="javascript:;" class="btn btn-danger text-white switch-view" view="general">
                                    <i class="fas fa-eye"></i> Vista
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="matrix_content">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="marco col-12">
        <div class="box">
            <h3 class="titulo">Leyenda</h3>
            <div class="cuerpo text-start">
                <p><span class="d-inline-block text-block t_green" style="width: 20px;">&nbsp;</span> <strong>Verde:</strong> Desde la fecha de inicio hasta faltando 25% de los días para la fecha de término.</p>
                <p><span class="d-inline-block text-block t_yellow" style="width: 20px;">&nbsp;</span> <strong>Amarillo:</strong> Entre el 25% de los días previo a la fecha de vencimiento hasta la fecha de vencimiento.</p>
                <p><span class="d-inline-block text-block t_blue" style="width: 20px;">&nbsp;</span> <strong>Azul:</strong> La actividad ha sido cumplida.</p>
                <p><span class="d-inline-block text-block t_red" style="width: 20px;">&nbsp;</span> <strong>Rojo:</strong> Cuando no se haya cumplido la accion y se ha vencido el plazo.</p>
            </div>
        </div>
    </div> --}}
</div>
@endsection

@section('script')
<script>
    var obj_form_data = null;
    let stratId = "{{$strat->id}}";
    let matrixUrl = "{{route('front.specifics.matrix')}}";
    let summMatrixUrl = "{{route('front.specifics.summarymatrix')}}";
</script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="{{asset('js/front/specifics/script.js')}}"></script>
@endsection