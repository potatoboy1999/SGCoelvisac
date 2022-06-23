@extends('layouts.admin')

@section('title', 'Viajes')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{asset('css/intranet/objectives.css')}}">
    <style>
        .area-travel{
            background:#93cdff;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-row flex-wrap">
                    <form id="form-area-sel" action="{{route('agenda.index')}}" method="get" class="w-100">
                        <div class="form-group w-100">
                            <label>Año:</label>
                            <div class="input-group d-inline-flex" style="width: calc(100% - 41px);">
                                <input class="form-control" type="number" min="2020" value="{{$year}}" name="year" step="1" onkeydown="return false">
                                <button id="search-year" class="btn btn-secondary" type="submit">
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
                <div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>
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
    $(function(){
        getCalendar();
    });

</script>
@endsection