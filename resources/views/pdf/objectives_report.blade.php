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
    <title>Reporte PDF</title>
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
            border: 1px solid rgb(0, 0, 0)!important;
            text-align: left;
            padding: 8px;
        }
        .bg-dark-1{
            background: #51607c;
            color: white;
        }
        .bg-dark-2{
            background: #8b9bb7;
        }
        .bg-dark-3{
            background: #cccccc;
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
    </style>
</head>
<body>
    <div class="container">
        <h3 style="font-family: sans-serif; color:#008cff">CVC ENERGIA</h3>
        <h2 style="text-align: center;">Matriz de Objetivos</h2>
        <table style="width:100%">
            <tr>
                <td width="25"><p><strong>Área:</strong></p></td>
                <td><p class="area-name">{{$area->nombre}}</p></td>
            </tr>
        </table>
        <div>
            <table class="table table-bordered" style="margin-top:10px; margin-bottom:10px;">
                <thead>
                    <tr>
                        <th class="bg-dark-1 th-obj-cod"    width="10%"><p>COD</p></th>
                        <th class="bg-dark-1 th-obj-name"   width="20%"><p>Objetivo</p></th>
                        <th class="bg-dark-1 th-act-name"   width="30%"><p>Actividades Principales</p></th>
                        <th class="bg-dark-1 th-date-start" width="15%"><p>Fecha Inicio</p></th>
                        <th class="bg-dark-1 th-date-end"   width="15%"><p>Fecha Fin</p></th>
                        <th class="bg-dark-1 th-status"     width="10%"><p>Estado</p></th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = 0; ?>
                @foreach ($roles as $role)
                    @if (roleHasActivities($role, $filter))
                        <tr class="td_role">
                            <td class="bg-dark-2 t_role_row" colspan="100%" type="role">
                                <p>Rol {{$role->id}}: {{$role->nombre}}</p>
                            </td>
                        </tr>
                        <?php 
                            $x = 0; 
                            $themes = $role->themes;
                        ?>
                        @foreach ($themes as $theme)
                            @if (themeHasActivities($theme, $filter))
                                <tr class="td_theme">
                                    <td class="bg-dark-3 t_theme_row" colspan="100%" type="theme">
                                        <p>Tema {{$x+1}}: {{$theme->nombre}}</p>
                                    </td>
                                </tr>
                                @foreach ($theme->objectives as $objective)
                                    <?php 
                                        $y = 0; 
                                        $activities = filterActivities($objective->activities, $filter);
                                    ?>
                                    @foreach ($activities as $activity)
                                        @if (valActivity($activity, $filter))
                                        <tr class="td_activity">
                                            @if ($y == 0)
                                            <td class="text-center t-obj-code" rowspan="{{sizeOf($activities)}}">
                                                <div class="collapse collapseTheme{{$theme->id}}">
                                                    <div class="td_content">
                                                        <p>Ob_{{$theme->id}}-{{$objective->id}}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="t-obj-name" rowspan="{{sizeOf($activities)}}">
                                                <div class="collapse collapseTheme{{$theme->id}}">
                                                    <div class="td_content">
                                                        <p>{{$objective->nombre}}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            @endif
                                            <td class="t-act-name">
                                                <div class="collapse collapseTheme{{$theme->id}}">
                                                    <div class="td_content">
                                                        <p>{{$activity->nombre}}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center t-date-start">
                                                <div class="collapse collapseTheme{{$theme->id}}">
                                                    <div class="td_content">
                                                        <p>{{date("d-m-Y", strtotime($activity->fecha_comienzo))}}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center t-date-end">
                                                <div class="collapse collapseTheme{{$theme->id}}">
                                                    <div class="td_content">
                                                        <p>{{date("d-m-Y", strtotime($activity->fecha_fin))}}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            @php
                                                $s = ['t_red','t_yellow','t_green'];
                                            @endphp
                                            <td class="t-status {{ $s[progressStatus($activity)] }}"></td>
                                        </tr>
                                        <?php $y++; ?>
                                        @endif
                                    @endforeach
                                @endforeach
                                <?php $x++; ?>
                            @endif
                        @endforeach
                    @endif
                <?php $i++; ?>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>