@php
    function progressStatus($activity){
        // ['t_red','t_gray','t_blue','t_yellow','t_green'];
        $status = 0; // not done = RED
        if($activity->estado == 1){
            $status = 1; // not started = GRAY
        }elseif($activity->estado == 3){
            $status = 2; // done = BLUE
        }elseif($activity->estado == 4){
            $status = 0; // not done = RED
        }else{
            $status = 4; // working on it = GREEN
            $today = time();
            $d_start = strtotime($activity->fecha_comienzo);
            $d_end = strtotime($activity->fecha_fin);
            if($d_start <= $today && $today <= $d_end){
                // calculate 25% of time remaining
                $diff = ($d_end - $d_start)*0.25;
                $d_limit = $d_start + $diff;

                if($today < $d_limit){
                    $status = 4; // if today is within 25% of start, status OK = GREEN
                }
                
                if($d_limit <= $today){
                    $status = 3; // if today is past 25%, status warning = YELLOW
                }

            }else if($d_end < $today){
                $status = 0; // time expired, not done = RED
            }
        }
        return $status;
    }
@endphp

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
        .text-success{
            color:#2EB85C;
        }
        .text-danger{
            color: #e55353;
        }

        /* --- Activities Report --- */
        .t_gray {
            background-color: #d8dbe0!important;
        }
        .t_blue {
            background-color: #256ae2!important;
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

        #report_activities .table td, 
        #report_activities .table th {
            border: 1px solid #ccc;
            text-align: left;
            padding: 8px;
        }

        #report_activities .table th{
            background: #dddddd;
        }
        .empty-alert{
            padding: 0.5rem; 
            text-align: center;
            border: 1px solid #ccc;
            background-color: #e7e7e7;
            border-radius: 0.75rem;
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
            padding: 0.25rem 1rem;
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
        .text-block{
            padding: 0.1rem 0.25rem;
            border-radius: 2px;
        }

    </style>
</head>
<body>
    <div class="container">
        <h3 style="font-family: sans-serif; color:#008cff">CVC ENERGIA</h3>
        <h2 style="text-align: center;">Reporte de Actividades</h2>
        @if (sizeof($activities) > 0)
            <div id="report_activities">
                <table class="table table-bordered" style="margin-top:10px; margin-bottom:10px;">
                    <thead>
                        <tr>
                            <th class="th-branch bg-dark text-white"        width="5%"><p>Sede</p></th>
                            <th class="th-user bg-dark text-white"          width="5%"><p>Usuario</p></th>
                            <th class="th-travel-from bg-dark text-white"   width="12%"><p>Viaje Desde</p></th>
                            <th class="th-travel-to bg-dark text-white"     width="12%"><p>Viaje Hasta</p></th>
                            <th class="th-activity bg-dark text-white"><p>Actividad</p></th>
                            <th class="th-deal bg-dark text-white"><p>Acuerdo</p></th>
                            {{-- <th class="th-to bg-dark text-white"        width="12%"><p>Fecha Inicio</p></th> --}}
                            <th class="th-to bg-dark text-white"        width="12%"><p>Fecha Fin</p></th>
                            <th class="th-status bg-dark text-white"        width="5%"><p>Estado</p></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $s = ['t_red','t_gray','t_blue','t_yellow','t_green'];
                        @endphp
                        @foreach ($activities as $activity)
                        <tr class="rep-act" act-id="{{$activity->id}}">
                            <td class="t-branch align-middle"><p>{{$activity->travelSchedule->branch->nombre}}</p></td>
                            <td class="t-user align-middle"><p>{{$activity->travelSchedule->user->nombre}}</p></td>
                            <td class="t-travel-from align-middle"><p>{{date("d-m-y",strtotime($activity->travelSchedule->viaje_comienzo))}}</p></td>
                            <td class="t-travel-to align-middle"><p>{{date("d-m-y",strtotime($activity->travelSchedule->viaje_fin))}}</p></td>
                            <td class="t-activity align-middle"><p>{{$activity->descripcion}}</p></td>
                            <td class="t-deal align-middle"><p>{{$activity->acuerdo}}</p></td>
                            {{-- <td class="t-to align-middle"><p>{{date("d-m-Y", strtotime($activity->fecha_comienzo))}}</p></td> --}}
                            <td class="t-to align-middle"><p>{{date("d-m-Y", strtotime($activity->fecha_fin))}}</p></td>
                            <td class="t-status align-middle {{ $s[progressStatus($activity)] }}"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card">
                <div class="card-header">Leyenda</div>
                <div class="card-body">
                    <p>
                        <span class="d-inline-block text-block t_gray" style="width: 20px;">&nbsp;</span> 
                        <strong>Gris:</strong> Actividad no iniciada
                    </p>
                    <p>
                        <span class="d-inline-block text-block t_green" style="width: 20px;">&nbsp;</span> 
                        <strong>Verde:</strong> Actividad iniciada. Desde la fecha de inicio hasta faltando 25% de los días para la fecha de término.
                    </p>
                    <p>
                        <span class="d-inline-block text-block t_yellow" style="width: 20px;">&nbsp;</span>
                        <strong>Amarillo:</strong> Actividad iniciada. Entre el 25% de los días previo a la fecha de vencimiento hasta la fecha de vencimiento.
                    </p>
                    <p>
                        <span class="d-inline-block text-block t_blue" style="width: 20px;">&nbsp;</span> 
                        <strong>Azul:</strong> Actividad Completada.
                    </p>
                    <p>
                        <span class="d-inline-block text-block t_red" style="width: 20px;">&nbsp;</span>
                        <strong>Rojo:</strong> Cuando no se haya cumplido la accion y se ha vencido el plazo.
                    </p>
                </div>
            </div>
        @else
        <div class="empty-alert">
            <p>No hay resultados para este reporte</p>
        </div>
        @endif
    </div>
</body>
</html>