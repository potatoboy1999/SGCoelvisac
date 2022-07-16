@component('mail::message')
# Nueva agenda de viaje

Una nueva agenda de viaje fue creada por "{{$name}}", miembro de su area.
<br>
Por favor apruebe o rechaze esta agenda

@component('mail::button', ['url' => $link])
Ver agendas
@endcomponent

@endcomponent
