@php
    function day_data($y,$m,$day){
        return [
            'date_d' => $day,
            'date' => date($y.'-'.$m.'-'.$day)
        ];
    }
    function calendar_data($y, $m){
        $fir_date = date($y.'-'.$m.'-01');
        $fir_day = date('w', strtotime($fir_date));

        $end_year = $y;
        $end_month = $m + 1;
        if($end_month > 12){
            $end_year = $y + 1;
            $end_month = 1;
        }

        $fin_date = date('Y-m-d', strtotime('-1 day', strtotime($end_year.'-'.$end_month.'-1')));
        $days_count = intval(date('d', strtotime($fin_date)));

        $weeks = [];
        $week = [
            0 => null, 1 => null, 2 => null,
            3 => null, 4 => null, 5 => null,
            6 => null
        ];
        for ($i = 1; $i <= $days_count; $i++){
            $date = date($y.'-'.$m.'-'.$i);
            $w_day = intval(date('w', strtotime($date)));
            $week[$w_day] = day_data($y,$m,$i);
            if($w_day == 6 || $i == $days_count){
                $weeks[] = $week;
                $week = [
                    0 => null, 1 => null, 2 => null,
                    3 => null, 4 => null, 5 => null,
                    6 => null
                ];
            }
        }
        return $weeks;
    }

    $months = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    $days = ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'];
@endphp
<span>AreaId: {{Auth::user()->position->area->id}}</span><br>
<span>IsAdmin: {{Auth::user()->is_admin}}</span>
<div class="p-1 mb-1">
    <span style="display: none;">{{$months[$month-1]}}</span>
    <div class="overflow-auto">
        @php
            $f_date = date($year.'-'.$month.'-01');
            $f_day = date('w', strtotime($f_date));
        @endphp
        <table id="travel_calendar_month" class="table table-bordered m-0">
            <thead>
                <tr>
                    @foreach ($days as $k => $day)
                        <th class="text-center th-day-{{$k}}" width="250">
                            <p class="m-0 px-2">{{$day}}</p>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $weeks = calendar_data($year, $month);
                @endphp
                @foreach ($weeks as $w => $week)
                    <tr class="r-week-{{$w}}">
                        @foreach ($days as $k => $day)
                            @php
                                $w_day = $week[$k];
                            @endphp
                            <td class="d-week-{{$w}} {{$w_day?'day-cell day-clickable':''}}" data-date="{{$w_day ? $w_day['date'] : ''}}">
                                <p class="text-center m-0">{{$w_day ? $w_day['date_d'] : ''}}</p>
                                @if ($w_day)
                                    @php
                                        $cur_date = strtotime($w_day['date']);
                                        $travels = $schedules->filter(function($value, $key) use ($cur_date){
                                            $a_date = strtotime($value->viaje_comienzo);
                                            $d_date = strtotime($value->viaje_fin);
                                            return ($a_date <= $cur_date && $cur_date <= $d_date);
                                        });
                                    @endphp
                                    @foreach ($travels as $travel)
                                        <p class="m-0 p-1 rounded area-travel mb-1 text-white branch{{$travel->branch->id}}" data-date="{{$w_day['date']}}" data-travelid="{{$travel->id}}">
                                            {{$travel->user->position->nombre}}
                                        </p>
                                    @endforeach
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>