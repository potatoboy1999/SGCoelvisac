@php
    function progressStatus($activity){
        $status = 0; // not done = RED
        if($activity->cumplido == 1){
            $status = 2; // done = GREEN
        }else{
            $today = time();
            $d_start = strtotime($activity->fecha_comienzo);
            $d_end = strtotime($activity->fecha_fin);
            if($d_start <= $today && $today <= $d_end){
                // calculate 25% of time remaining
                $diff = ($d_end - $d_start)*0.25;
                $d_limit = $d_start + $diff;

                if($today < $d_limit){
                    $status = 2; // if today is within 25% of start, status OK = GREEN
                }
                
                if($d_limit <= $today){
                    $status = 1; // if today is past 25%, status warning = YELLOW
                }

            }else if($d_end < $today){
                $status = 0; // time expired, not done = RED
            }
        }
        return $status;
    }
    function valActivity($activity, $filter){
        if($activity->estado == 0){
            return false;
        }
        if($filter['active']){
            $labels = ['red','yellow','green'];
            $progStatus = progressStatus($activity);
            if($filter['status'][$labels[$progStatus]]){
                return true;
            }
        }else{
            return true;
        }
        return false;
    }
    function filterActivities($activities, $filter){
        $list = [];
        foreach ($activities as $activity) {
            if(valActivity($activity, $filter)){
                $list[] = $activity;
            }
        }
        return $list;
    }
    function themeHasActivities($theme, $filter){
        $count = 0;
        foreach ($theme->objectives as $objective) {
            foreach ($objective->activities as $activity) {
                if(valActivity($activity, $filter)){
                    $count++;
                }
            }
        }
        return ($count>0);
    }
    function roleHasActivities($role, $filter){
        $count = 0;
        foreach ($role->themes as $theme) {
            foreach ($theme->objectives as $objective) {
                foreach ($objective->activities as $activity) {
                    if(valActivity($activity, $filter)){
                        $count++;
                    }
                }
            }
        }
        return ($count>0);
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Matriz Objetivos - PDF</title>
    <style>
        .page-break {
            page-break-after: always;
        }
        body{
            font-family: arial, sans-serif;
        }
        p {
            font-size: 0.75rem;
        }
        .m-0{
            margin:0 !important;
        }
        table{
            border-collapse: collapse;
            width: 100%;
        }
        .table p{
            margin: 0;
        }
        .table td, 
        .table th {
            /* border: 1px solid #ccc; */
            border: 1px solid rgb(119, 119, 119)!important;
            text-align: left;
            padding: 8px;
        }
        .bg-dark-1{
            background-color: #51607c!important;
            color: white!important;
        }
        .bg-dark-2{
            background-color: #8b9bb7!important;
        }
        .bg-dark-3{
            background-color: #cccccc!important;
        }
        .t_red {
            background-color: #ec1d1d!important;
        }
        .t_green {
            background-color: #12c212!important;
        }
        .t_yellow {
            background-color: #f9e715!important;
        }
        .area-name{
            background: #ececec;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
            padding: 0.5rem;
        }
        .p-0{
            padding: 0!important;
        }
        .mb-3{
            margin-bottom: 1rem !important;
        }
        .mb-4{
            margin-bottom: 1.5rem !important;
        }
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 21, 0.125);
            border-radius: 0.25rem;
        }
        .card > .card-header + .list-group,
        .card > .list-group + .card-footer {
            border-top: 0;
        }
        .card-body {
            flex: 1 1 auto;
            padding: 1rem 1rem;
            color: unset;
        }
        .card-header {
            padding: 0.5rem 1rem;
            margin-bottom: 0;
            color: unset;
            background-color: rgba(0, 0, 21, 0.03);
            border-bottom: 1px solid rgba(0, 0, 21, 0.125);
        }
        .card-header:first-child {
            border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
        }

        .card-footer {
            padding: 0.5rem 1rem;
            color: unset;
            background-color: rgba(0, 0, 21, 0.03);
            border-top: 1px solid rgba(0, 0, 21, 0.125);
        }
        .card-footer:last-child {
            border-radius: 0 0 calc(0.25rem - 1px) calc(0.25rem - 1px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 style="font-family: sans-serif; color:#008cff">COELVISAC</h3>
        <h2 style="text-align: center;">Matriz de Objetivos</h2>
        <table style="width:100%">
            <tr>
                <td width="25"><p><strong>Área:</strong></p></td>
                <td><p class="area-name">{{$area->nombre}}</p></td>
            </tr>
        </table>
        <div>
            <?php $i = 0; ?>
            @foreach ($roles as $role)
                @if (roleHasActivities($role, $filter))
                <div class="card role-card mb-4" role-id="{{$role->id}}">
                    <div class="card-header rol-header bg-dark-1">
                        <p class="m-0">Rol {{$role->id}}: {{$role->nombre}}</p>
                    </div>
                    <div class="card-body">
                        <div id="collapseRole{{$role->id}}" class="collapse">
                            <div class="collapse-content p-3">
                                @php
                                    $x = 0; 
                                    $themes = $role->themes;
                                @endphp
                                @foreach ($themes as $theme)
                                @if (themeHasActivities($theme, $filter))
                                    <div class="card theme-card {{($x != sizeOf($themes)-1?"mb-3":"")}}" theme-id="{{$theme->id}}">
                                        <div class="card-header bg-dark-2">
                                            <p class="m-0">Tema {{$x+1}}: {{$theme->nombre}}</p>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="collapseTheme{{$theme->id}}" class="collapse row">
                                                <div class="col-12">
                                                    <div class="overflow-auto">
                                                        <table class="table table-bordered m-0">
                                                            <thead>
                                                                <tr>
                                                                    <th class="bg-dark-3 text-center t-head-obj-code"    width="10%"><p>COD</p></th>
                                                                    <th class="bg-dark-3 text-center t-head-obj-name"    width="20%"><p>Objetivo</p></th>
                                                                    <th class="bg-dark-3 text-center t-head-act-name"    width="30%"><p>Actividades Principales</p></th>
                                                                    <th class="bg-dark-3 text-center t-head-date-start"  width="15%"><p>Fecha Inicio</p></th>
                                                                    <th class="bg-dark-3 text-center t-head-date-end"    width="15%"><p>Fecha Fin</p></th>
                                                                    <th class="bg-dark-3 text-center t-head-status"      width="10%"><p>Estado</p></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($theme->objectives as $objective)
                                                                    <?php 
                                                                        $y = 0; 
                                                                        $activities = filterActivities($objective->activities, $filter);
                                                                    ?>
                                                                    @foreach ($activities as $activity)

                                                                        @if (valActivity($activity, $filter))
                                                                        <tr act-id="{{$activity->id}}">
                    
                                                                            <td class="text-center align-middle t-obj-code" obj-id="{{$objective->id}}" rowspan="{{sizeOf($activities)}}" style="{{$y == 0?'':'display: none;'}}">
                                                                                <p>Ob_{{$theme->id}}-{{$objective->id}}</p>
                                                                            </td>
                                                                            <td class="align-middle t-obj-name" obj-id="{{$objective->id}}" rowspan="{{sizeOf($activities)}}" style="{{$y == 0?'':'display: none;'}}"><p>{{$objective->nombre}}</p></td>
                    
                                                                            <td class="align-middle t-act-name"><p>{{$activity->nombre}}</p></td>
                                                                            <td class="text-center align-middle t-date-start"><p>{{date("d-m-Y", strtotime($activity->fecha_comienzo))}}</p></td>
                                                                            <td class="text-center align-middle t-date-end"><p>{{date("d-m-Y", strtotime($activity->fecha_fin))}}</p></td>
                                                                            @php
                                                                                $s = ['t_red','t_yellow','t_green'];
                                                                            @endphp
                                                                            <td class="t-status {{ $s[progressStatus($activity)] }}"></td>
                                                                        </tr>
                                                                        <?php $y++; ?>
                                                                        @endif
                                                                    @endforeach
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php $x++; ?>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <?php $i++; ?>
                @endif
            @endforeach
        </div>
    </div>
</body>