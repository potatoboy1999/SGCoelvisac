<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF Report Activity</title>
    <style>
        .page-break {
            page-break-after: always;
        }
        body{
            font-family: arial, sans-serif;
        }
        h2 {
            font-size: 1.25rem;
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

        #schedule_info .table td, 
        #schedule_info .table th,
        #extras_info .table td,
        #extras_info .table th,
        #val_signatures .table td,
        #val_signatures .table th {
            text-align: left;
            padding: 0px;
        }

        /* --- Schedule Info --- */
        #schedule_info .table th {
            border-radius: 5px 5px 0 0;
        }

        #schedule_info .table td {
            border-radius: 0 0 5px 5px;
        }

        #schedule_info .table th p,
        #schedule_info .table td p{
            padding: 8px;
            border: 1px solid #ccc;
        }

        #schedule_info .table th p {
            background: #dddddd;
            border-radius: 5px 5px 0 0;
        }
        #schedule_info .table td p {
            border-top: 0;
            border-radius: 0 0 5px 5px;
        }

        /* --- Extras Info --- */
        #extras_info .table th:first-child {
            border-radius: 5px 0 0 0;
        }
        #extras_info .table th:first-child p {
            border-radius: 5px 0 0 0;
        }
        #extras_info .table th:last-child {
            border-radius: 0 5px 0 0;
        }
        #extras_info .table th:last-child p{
            border-radius: 0 5px 0 0;
        }

        #extras_info .table td:first-child {
            border-radius: 0 0 0 5px;
        }
        #extras_info .table td:first-child p {
            border-radius: 0 0 0 5px;
        }
        #extras_info .table td:last-child {
            border-radius: 0 0 5px 0;
        }
        #extras_info .table td:last-child p{
            border-radius: 0 0 5px 0;
        }

        #extras_info .table th p,
        #extras_info .table td p{
            text-align: center;
            padding: 8px;
            border: 1px solid #ccc;
        }

        #extras_info .table th p {
            background: #dddddd;
        }
        
        #extras_info .table td p {
            border-top: 0;
        }

        /* --- Activities Info --- */
        .travel_activity{
            margin: 0;
            margin-top: 2px;
            margin-bottom: 2px;
            padding: 8px;
            border:1px solid #ccc;
            border-radius: 5px;
        }

        /* --- Signatures Info --- */
        #val_signatures .table th p,
        #val_signatures .table td p{
            padding: 8px;
            border: 1px solid #ccc;
        }

        #val_signatures .table th p {
            background: #dddddd;
        }

        #val_signatures .table th {
            border-radius: 0 0 5px 5px;
        }
        #val_signatures .table th p{
            border-radius: 0 0 5px 5px;
            padding: 2px 8px;
        }
        #val_signatures .table td {
            border-radius: 5px 5px 0 0;
        }
        #val_signatures .table td p{
            border-radius: 5px 5px 0 0;
        }
        #val_signatures .table td p,
        #val_signatures .table th p{
            text-align: center;
        }

        /* --- Activities Report --- */
        td.t_red {
            background-color: #ec1d1d;
        }
        td.t_green {
            background-color: #12c212;
        }
        td.t_yellow {
            background-color: #f9e715;
        }

        #report_activities .table td, 
        #report_activities .table th {
            border: 1px solid #ccc;
            text-align: left;
            padding: 8px;
        }

        #report_activities .table th{
            background: #dddddd;
        }

    </style>
</head>
<body>
    <div class="container">
        {{-- PAGE 1: SCHEDULE DETAILS --}}
        {{-- <img class="logo" src="img/logo.png" height="50" alt=""> --}}
        <h3 style="font-family: sans-serif; color:#008cff">COELVISAC</h3>
        <h2 style="text-align: center;">Agenda de viaje</h2>
        <div id="schedule_info">
            <table>
                <tr>
                    <td width="50%">
                        <table class="table">
                            <tr>
                                <th>
                                    <p>Área</p>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <p>{{$schedule->user->position->area->nombre}}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%">
                        <table class="table">
                            <tr>
                                <th>
                                    <p>Nombre</p>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <p>{{$schedule->user->nombre}}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td width="50%">
                        <table class="table">
                            <tr>
                                <th>
                                    <p>Puesto / Cargo</p>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <p>{{$schedule->user->position->nombre}}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%">
                        <table class="table">
                            <tr>
                                <th><p>Sede Visitada</p></th>
                            </tr>
                            <tr>
                                <td><p>{{$schedule->branch->nombre}}</p></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td width="50%">
                        <table class="table">
                            <tr>
                                <th><p>Fecha de Llegada</p></th>
                            </tr>
                            <tr>
                                <td><p>{{date('d/m/Y', strtotime($schedule->viaje_comienzo))}}</p></td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%">
                        <table class="table">
                            <tr>
                                <th><p>Fecha de Retorno</p></th>
                            </tr>
                            <tr>
                                <td><p>{{date('d/m/Y', strtotime($schedule->viaje_fin))}}</p></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div id="extras_info" style="margin-top: 2px;">
            <table class="table">
                <tr>
                    <th width="33%"><p>¿Vehículo?</p></th>
                    <th width="33%"><p>¿Hospedaje?</p></th>
                    <th><p>¿Viáticos?</p></th>
                </tr>
                <tr>
                    <td align="center"><p>{{$schedule->vehiculo==0?'NO':'SÍ'}}</p></td>
                    <td align="center"><p>{{$schedule->hospedaje==0?'NO':'SÍ'}}</p></td>
                    <td align="center"><p>{{$schedule->viaticos==0?'NO':'SÍ'}}</p></td>
                </tr>
            </table>
        </div>
        <div id="area_activities" class="act_area" style="margin-top: 2px;">
            <table>
                <tr>
                    <td>
                        <p style="margin:0;border-radius:5px;background:#ccc;padding:8px;">Actividades del área</p>
                    </td>
                </tr>
            </table>
            <table>
                @php
                    $counter = 0;
                @endphp
                @foreach ($schedule->activities->where('tipo',1) as $activity)
                    @php
                        $counter++;
                    @endphp
                    <tr>
                        <td width="25px">#{{$counter}}</td>
                        <td>
                            <p class="travel_activity">{{$activity->descripcion}}</p>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        @php
            $non_area_activities = $schedule->activities->where('tipo',2);
        @endphp
        @if (sizeof($non_area_activities) > 0)
            <div id="non_area_activities" class="act_area" style="margin-top: 2px;">
                <table>
                    <tr>
                        <td>
                            <p style="margin:0;border-radius:5px;background:#ccc;padding:8px;">Actividades de otras áreas</p>
                        </td>
                    </tr>
                </table>
                <table>
                    @php
                        $counter = 0;
                    @endphp
                    @foreach ($non_area_activities as $activity)
                        @php
                            $counter++;
                        @endphp
                        <tr>
                            <td width="25px">#{{$counter}}</td>
                            <td>
                                <p class="travel_activity">{{$activity->descripcion}}</p>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif
        
        <div id="val_signatures" style="margin-top: 45px;">
            <p><strong>Validado Por:</strong></p>
            <table>
                <tr>
                    <td width="50%">
                        <table class="table">
                            <tr>
                                <td align="center"><p>{{$schedule->val_one_by->nombre}}</p></td>
                            </tr>
                            <tr>
                                <th align="center"><p>{{$schedule->val_one_by->position->nombre}}</p></th>
                            </tr>
                        </table>
                    </td>
                    <td width="50%">
                        <table class="table">
                            <tr>
                                <td align="center"><p>{{$schedule->val_two_by->nombre}}</p></td>
                            </tr>
                            <tr>
                                <th align="center"><p>{{$schedule->val_two_by->position->nombre}}</p></th>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div class="page-break"></div>
        {{-- PAGE 2: REPORT ACTIVITIES --}}
        {{-- <img class="logo" src="img/logo.png" height="50" alt=""> --}}
        <h3 style="font-family: sans-serif; color:#008cff">COELVISAC</h3>
        <h2 style="text-align: center;">Informe del Viaje</h2>
        <div id="report_activities">
            <table>
                <tr>
                    <td>
                        <p style="margin:0;border-radius:5px;background:#ccc;padding:8px;">Actividades realizadas durante la visita</p>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered" style="margin-top:10px; margin-bottom:10px;">
                <thead>
                    <tr>
                        <th width="37%"><p>Descripción</p></th>
                        <th width="37%"><p>Acciones propuestas / Acuerdos</p></th>
                        <th width="10%"><p>Fecha de Inicio</p></th>
                        <th width="10%"><p>Fecha de Termino</p></th>
                        <th width="6%"><p>Estado</p></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedule->reportActivities->where('tipo', 1) as $activity)
                    <tr class="rep-act" act-id="{{$activity->id}}">
                        <td><p>{{$activity->descripcion}}</p></td>
                        <td><p>{{$activity->acuerdo}}</p></td>
                        <td><p>{{date('d/m/Y', strtotime($activity->fecha_comienzo))}}</p></td>
                        <td><p>{{date('d/m/Y', strtotime($activity->fecha_fin))}}</p></td>
                        @php
                            $s = ['t_red','t_yellow','t_green'];
                            $status = 0; // not done = RED
                            if($activity->estado == 2){
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
                        @endphp
                        <td class="{{ $s[$status] }}"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @php
                $non_area_activities = $schedule->reportActivities->where('tipo', 2);
            @endphp
            @if (sizeof($non_area_activities) > 0)
                <table>
                    <tr>
                        <td>
                            <p style="margin:0;border-radius:5px;background:#ccc;padding:8px;">Otras actividades o temas identificados durante la visita</p>
                        </td>
                    </tr>
                </table>

                <table class="table table-bordered" style="margin-top: 10px">
                    <thead>
                        <tr>
                            <th width="37%"><p>Descripción</p></th>
                            <th width="37%"><p>Acciones propuestas / Acuerdos</p></th>
                            <th width="10%"><p>Fecha de Inicio</p></th>
                            <th width="10%"><p>Fecha de Termino</p></th>
                            <th width="6%"><p>Estado</p></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($non_area_activities as $activity)
                        <tr class="rep-act" act-id="{{$activity->id}}">
                            <td><p>{{$activity->descripcion}}</p></td>
                            <td><p>{{$activity->acuerdo}}</p></td>
                            <td><p>{{date('d/m/Y', strtotime($activity->fecha_comienzo))}}</p></td>
                            <td><p>{{date('d/m/Y', strtotime($activity->fecha_fin))}}</p></td>
                            @php
                                $s = ['t_red','t_yellow','t_green'];
                                $status = 0; // not done = RED
                                if($activity->estado == 2){
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
                            @endphp
                            <td class="{{ $s[$status] }}"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</body>
</html>