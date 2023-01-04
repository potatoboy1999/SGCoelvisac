@component('mail::message')
# Alerta de fin de ciclo

<table style="box-sizing:border-box; margin:30px auto; width:100%">
    <tbody style="box-sizing:border-box">
        <tr>
            <td align="left" style="box-sizing:border-box; border-bottom:1px solid #edeff2; color:#74787e; font-size:15px; margin:0; padding:10px 0 10px 5px">
                Estimados, favor tener en cuenta que, la fecha límite para completar la información en la Plataforma de gestión sobre los resultados de los Objetivos estratégicos, específicos y planes de acción es hasta el día 10 del presente mes.
                <br>
                Cualquier duda o consulta comunicarse con la Jefatura de Gestión. 
            </td>
        </tr>
    </tbody>
</table>

@component('mail::button', ['url' => $link])
Ver Objetivos
@endcomponent
<i style="font-size: 10px;">Si el botón no funciona, use el siguiente link: {{$link}}</i>
@endcomponent
