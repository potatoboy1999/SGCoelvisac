@extends('layouts.front')

@section('title', 'Calendario de Viaje')
    
@section('style')
    <link rel="stylesheet" href="{{asset('css/front/travels.css')}}">
    <style>
        .text-block {
            padding: 0.1rem;
            border-radius: 5px;
        }
        .branch1{
            background: rgb(0, 139, 0);
        }
        .branch2{
            background: rgb(0, 134, 139);
        }
        .branch3{
            background: rgb(116, 0, 139);
        }
    </style>
@endsection

@section('content')
<!-- Travel Schedule Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
    </div>
</div>
<!-- END Travel Schedule Modal -->

<div class="row">
    <div class="marco col-md-12 col-xs-12">
        <div class="box">
            <h2 class="titulo"><i class="fas fa-cog"></i>Calendario de Viajes</h2>
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
    <div class="marco" id="calendar-wrapper">
        <div class="box">
            <div class="cuerpo">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
    <div class="marco col-12">
        <div class="box">
            <h3 class="titulo">Leyenda</h3>
            <div class="cuerpo text-start">
                <p><span class="d-inline-block text-block branch1" style="width: 20px;">&nbsp;</span> Sede Villacurí</p>
                <p><span class="d-inline-block text-block branch2" style="width: 20px;">&nbsp;</span> Sede Andahuasi</p>
                <p><span class="d-inline-block text-block branch3" style="width: 20px;">&nbsp;</span> Sede Olmos</p>
            </div>
        </div>
    </div>
</div>
  <!-- End Travel Schedule Modal -->
@endsection

@section('script')

<script src="{{asset("js/front/travels/script.js")}}"></script>
<script>
    var calendar_route = "{{route('front.schedules.calendar')}}";
    var pop_schedule_route = "{{route('front.schedules.popup')}}";
    $(function(){
        getCalendar();
    });

</script>

@endsection