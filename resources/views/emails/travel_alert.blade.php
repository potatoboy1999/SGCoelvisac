@component('mail::message')
# Nueva agenda de viaje

Una nueva agenda de viaje fue creada por "{{$name}}", miembro de su area.

<table style="box-sizing:border-box; margin:30px auto; width:100%">
    <tbody style="box-sizing:border-box">
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

Por favor apruebe o rechaze esta agenda

@component('mail::button', ['url' => $link])
Ver agendas
@endcomponent

@endcomponent
