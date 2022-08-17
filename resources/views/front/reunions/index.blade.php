@extends('layouts.front')

@section('title', 'Calendario de Viaje')
    
@section('style')
    <link rel="stylesheet" href="{{asset('css/front/reunion.css')}}">
    <style>
        .bg-dark {
            --bs-bg-opacity: 1;
            background-color: #414565 !important;
        }
        .bg-modal-header{
            background-color: #bfbfbf !important;
        }
    </style>
@endsection

@section('content')
<!-- Travel Schedule Modal -->
<div class="modal fade" id="reunionModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
    </div>
</div>
<!-- END Travel Schedule Modal -->

<div class="row">
    <div class="marco col-md-12 col-xs-12">
        <div class="box">
            <h2 class="titulo"><i class="fas fa-cog"></i>Calendario de Reuniones</h2>
        </div>
    </div>
    <div class="marco">
        <div class="box">
            <div class="cuerpo">
                <div class="d-flex flex-row flex-wrap">
                    <form id="form-area-sel" action="{{route('agenda.index')}}" method="get" class="w-100">
                        <div class="form-group w-100 d-inline-block my-1">
                            <label>Año:</label>
                            <div class="input-group d-inline-flex" style="width: calc(100% - 41px);">
                                <input class="form-control" type="number" min="2020" value="{{$year}}" name="year" step="1" onkeydown="return false">
                                <button id="search-year" class="btn btn-secondary search-calendar" type="submit">
                                    <svg class="icon">
                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-zoom"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="form-group w-100 d-inline-block my-1">
                            <label>Mes:</label>
                            <div class="input-group d-inline-flex" style="width: calc(100% - 41px);">
                                <select class="form-select" name="month" id="sel_month">
                                    <option value="01" {{$month == 1?'selected':''}}>Enero</option>
                                    <option value="02" {{$month == 2?'selected':''}}>Febrero</option>
                                    <option value="03" {{$month == 3?'selected':''}}>Marzo</option>
                                    <option value="04" {{$month == 4?'selected':''}}>Abril</option>
                                    <option value="05" {{$month == 5?'selected':''}}>Mayo</option>
                                    <option value="06" {{$month == 6?'selected':''}}>Junio</option>
                                    <option value="07" {{$month == 7?'selected':''}}>Julio</option>
                                    <option value="08" {{$month == 8?'selected':''}}>Agosto</option>
                                    <option value="09" {{$month == 9?'selected':''}}>Septiembre</option>
                                    <option value="10" {{$month == 10?'selected':''}}>Octubre</option>
                                    <option value="11" {{$month == 11?'selected':''}}>Noviembre</option>
                                    <option value="12" {{$month == 12?'selected':''}}>Diciembre</option>
                                </select>
                                <button id="search-month" class="btn btn-secondary search-calendar" type="submit">
                                    <svg class="icon">
                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-zoom"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="marco" id="reunion-wrapper">
        <div class="box">
            <div class="cuerpo">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        </div>
    </div>
</div>
  <!-- End Travel Schedule Modal -->
@endsection

@section('script')

<script src="{{asset("js/front/reunions/script.js")}}"></script>
<script>
    const reunion_route = "{{route('front.reunion.details')}}";
    
    $(function(){
        getReunion();
    });

</script>

@endsection