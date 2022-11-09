@component('mail::message')
# Nueva agenda de viaje validada

Una agenda de viaje del área "{{$name}}" fue validada.

<table style="box-sizing:border-box; margin:30px auto; width:100%">
    <tbody style="box-sizing:border-box">
        <tr>
            <th align="center" style="box-sizing:border-box; border:1px solid #edeff2; margin:0; padding:10px 0" width="175px">Aprobado por</th>
            <td align="left" style="box-sizing:border-box; border-bottom:1px solid #edeff2; color:#74787e; font-size:15px; margin:0; padding:10px 0 10px 5px">{{$schedule->val_one_by->nombre}}</td>
        </tr>
        <tr>
            <th align="center" style="box-sizing:border-box; border:1px solid #edeff2; margin:0; padding:10px 0" width="175px">Área</th>
            <td align="left" style="box-sizing:border-box; border-bottom:1px solid #edeff2; color:#74787e; font-size:15px; margin:0; padding:10px 0 10px 5px">{{$schedule->user->position->area->nombre}}</td>
        </tr>
        <tr>
            <th align="center" style="box-sizing:border-box; border:1px solid #edeff2; margin:0; padding:10px 0" width="175px">Nombre</th>
            <td align="left" style="box-sizing:border-box; border-bottom:1px solid #edeff2; color:#74787e; font-size:15px; margin:0; padding:10px 0 10px 5px">{{$name}}</td>
        </tr>
        <tr>
            <th align="center" style="box-sizing:border-box; border:1px solid #edeff2; margin:0; padding:10px 0" width="175px">Puesto / Cargo</th>
            <td align="left" style="box-sizing:border-box; border-bottom:1px solid #edeff2; color:#74787e; font-size:15px; margin:0; padding:10px 0 10px 5px">{{$schedule->user->position->nombre}}</td>
        </tr>
        <tr>
            <th align="center" style="box-sizing:border-box; border:1px solid #edeff2; margin:0; padding:10px 0" width="175px">Sede</th>
            <td align="left" style="box-sizing:border-box; border-bottom:1px solid #edeff2; color:#74787e; font-size:15px; margin:0; padding:10px 0 10px 5px">{{$schedule->branch->nombre}}</td>
        </tr>
        <tr>
            <th align="center" style="box-sizing:border-box; border:1px solid #edeff2; margin:0; padding:10px 0" width="175px">Desde</th>
            <td align="left" style="box-sizing:border-box; border-bottom:1px solid #edeff2; color:#74787e; font-size:15px; margin:0; padding:10px 0 10px 5px">{{date('d/m/Y', strtotime($schedule->viaje_comienzo))}}</td>
        </tr>
        <tr>
            <th align="center" style="box-sizing:border-box; border:1px solid #edeff2; margin:0; padding:10px 0" width="175px">Hasta</th>
            <td align="left" style="box-sizing:border-box; border-bottom:1px solid #edeff2; color:#74787e; font-size:15px; margin:0; padding:10px 0 10px 5px">{{date('d/m/Y', strtotime($schedule->viaje_fin))}}</td>
        </tr>
    </tbody>
</table>

@component('mail::button', ['url' => $link])
Aprobar o Rechazar
@endcomponent

@endcomponent
