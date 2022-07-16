@component('mail::message')
# Agenda de viaje por validar

Una agenda de viaje del area "{{$name}}" fue validada.
<br>
Por favor apruebe o rechaze esta agenda.

@component('mail::button', ['url' => $link])
Ver agendas
@endcomponent

@endcomponent
