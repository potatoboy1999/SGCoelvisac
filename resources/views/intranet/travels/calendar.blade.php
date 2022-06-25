@php
    $months = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
@endphp

@for ($i = 1; $i <= 12; $i++) <!-- month loop -->
    <div class="p-1 mb-1">
        <div class="card">
            <div class="card-header">
                <div class="float-end">
                    <button class="btn btn-outline-secondary btn-sm" data-coreui-target="#collapseTheme{{$i}}" data-coreui-toggle="collapse" role="button" aria-expanded="false" month-id="{{$i}}">
                        <svg class="icon">
                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-chevron-double-down"></use>
                        </svg>
                    </button>
                </div>
                <span>{{$months[$i-1]}}</span>
            </div>
            <div class="card-body p-0">
                <div id="collapseTheme{{$i}}" class="collapse">
                    <div class="collapse-body">
                        <div class="overflow-auto">
                            @php
                                $f_day = date($year.'-'.$i.'-01');
                                $a_day = $f_day;
                                $b_day = null;
                                $dates_r = [];
                                $dates = [];
                                for ($z = 1; $z <= 4; $z++) { 
                                    if($z == 1){
                                        $a_day = $f_day;
                                        $b_day = date('Y-m-d',strtotime($a_day.'+1 week'));
                                        $dates['start'] = $a_day;
                                        $dates['end'] = $b_day;
                                    }else if($z == 4){
                                        $a_day = $b_day;
                                        $b_day = date('Y-m-d',strtotime($f_day.'+1 month'));
                                        $dates['start'] = $a_day;
                                        $dates['end'] = $b_day;
                                    }else{
                                        $a_day = $b_day;
                                        $b_day = date('Y-m-d',strtotime($a_day.'+1 week'));
                                        $dates['start'] = $a_day;
                                        $dates['end'] = $b_day;
                                    }
                                    $dates_r[] = $dates;
                                }
                            @endphp
                            <table class="table table-bordered m-0">
                                <thead>
                                    <tr>
                                        <th class="th-branch" width="100">Sede</th>
                                        <th class="th-week-1" width="250">1ra semana</th>
                                        <th class="th-week-2" width="250">2da semana</th>
                                        <th class="th-week-3" width="250">3ra semana</th>
                                        <th class="th-week-4" width="250">4ta semana</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($branches as $branch)
                                    <tr class="r-branch" data-branchname="{{$branch->nombre}}" data-branchid="{{$branch->id}}">
                                        <td class="d-branch align-middle">{{$branch->nombre}}</td>
                                        @foreach ($dates_r as $d)
                                        <td class="d-week week-clickable" data-start="{{$d['start']}}" data-end="{{$d['end']}}">
                                            @php
                                                $aDateLimit = strtotime($d['start']);
                                                $bDateLimit = strtotime($d['end']);
                                                $travels = $branch->travel_schedules->filter(function($value, $key) use ($aDateLimit, $bDateLimit){
                                                    $aDate = strtotime($value->viaje_comienzo);
                                                    return ($aDateLimit <= $aDate && $aDate < $bDateLimit);
                                                });
                                            @endphp
                                            @foreach ($travels as $travel)
                                                <p class="m-0 p-1 rounded area-travel mb-3">{{$travel->user->position->nombre}}</p>
                                            @endforeach
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endfor <!-- end month loop -->