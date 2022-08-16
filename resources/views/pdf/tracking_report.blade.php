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
        .empty-alert{
            padding: 0.5rem; 
            text-align: center;
            border: 1px solid #ccc;
            background-color: #e7e7e7;
            border-radius: 0.75rem;
        }

    </style>
</head>
<body>
    <div class="container">
        <h3 style="font-family: sans-serif; color:#008cff">COELVISAC</h3>
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
                            <th class="th-to bg-dark text-white"        width="5%"><p>Estado</p></th>
                        </tr>
                    </thead>
                    <tbody>
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
                            <td class="t-deal align-middle {{$activity->estado=='1'?'text-danger':'text-success'}}"><p>{{$activity->estado=='1'?'NO TERMINADO':'TERMINADO'}}</p></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
        <div class="empty-alert">
            <p>No hay resultados para este reporte</p>
        </div>
        @endif
    </div>
</body>
</html>