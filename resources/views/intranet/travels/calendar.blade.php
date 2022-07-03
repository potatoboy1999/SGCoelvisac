@php
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
            $week[$w_day] = $i;
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
                        <th class="text-center th-day-{{$k}}" width="250">{{$day}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $weeks = calendar_data($year, $month);
                @endphp
                @foreach ($weeks as $w => $week)
                    <tr class="r-week-{{$w}}">
                        <td class="d-week-{{$w}} {{$week[0]?'day-cell area-travel day-clickable':''}}" data-date="{{$week[0] ? date($year.'-'.$month.'-'.$week[0]) : ''}}">
                            <p class="text-center m-0">{{$week[0]?$week[0]:''}}</p>
                        </td>
                        <td class="d-week-{{$w}} {{$week[1]?'day-cell area-travel day-clickable':''}}" data-date="{{$week[1] ? date($year.'-'.$month.'-'.$week[1]) : ''}}">
                            <p class="text-center m-0">{{$week[1]?$week[1]:''}}</p>
                        </td>
                        <td class="d-week-{{$w}} {{$week[2]?'day-cell area-travel day-clickable':''}}" data-date="{{$week[2] ? date($year.'-'.$month.'-'.$week[2]) : ''}}">
                            <p class="text-center m-0">{{$week[2]?$week[2]:''}}</p>
                        </td>
                        <td class="d-week-{{$w}} {{$week[3]?'day-cell area-travel day-clickable':''}}" data-date="{{$week[3] ? date($year.'-'.$month.'-'.$week[3]) : ''}}">
                            <p class="text-center m-0">{{$week[3]?$week[3]:''}}</p>
                        </td>
                        <td class="d-week-{{$w}} {{$week[4]?'day-cell area-travel day-clickable':''}}" data-date="{{$week[4] ? date($year.'-'.$month.'-'.$week[4]) : ''}}">
                            <p class="text-center m-0">{{$week[4]?$week[4]:''}}</p>
                        </td>
                        <td class="d-week-{{$w}} {{$week[5]?'day-cell area-travel day-clickable':''}}" data-date="{{$week[5] ? date($year.'-'.$month.'-'.$week[5]) : ''}}">
                            <p class="text-center m-0">{{$week[5]?$week[5]:''}}</p>
                        </td>
                        <td class="d-week-{{$w}} {{$week[6]?'day-cell area-travel day-clickable':''}}" data-date="{{$week[6] ? date($year.'-'.$month.'-'.$week[6]) : ''}}">
                            <p class="text-center m-0">{{$week[6]?$week[6]:''}}</p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>