@extends('layouts.admin')

@section('title', 'Viajes')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{asset('css/intranet/travel.css')}}">
    <style>
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
<div class="modal fade" id="newScheduleModal" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-4">
            <div class="card-body">
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
        <div id="calendar-card" class="card mb-4">
            <div class="card-header"><span>Viajes</span></div>
            <div class="card-body">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="{{asset("js/intranet/travels.js")}}"></script>
<script>
    var calendar_route = "{{route('agenda.calendar')}}";
    var pop_schedule_route = "{{route('agenda.popup.schedule')}}";
    $(function(){
        getCalendar();
    });

    function newActivity(name, type){
        var html = '<div class="mb-2 act-ta">'+
                        '<div style="display: flex;">'+
                            '<div style="flex: 0 40px;">'+
                                '<a class="btn btn-danger btn-sm btn-del-act" type="'+type+'">'+
                                    '<svg class="icon">'+
                                        '<use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-minus"></use>'+
                                    '</svg>'+
                                '</a>'+
                            '</div>'+
                            '<div style="flex: 1;">'+
                                '<textarea name="'+name+'[]" rows="2" class="form-control" required></textarea>'+
                            '</div>'+
                        '</div>'+
                    '</div>';
        return html;
    }

</script>
@endsection