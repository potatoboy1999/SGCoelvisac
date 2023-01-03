@php
    $months = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
    $cicles = [
        "men" => [
            "count" => 12,
            "label" => "mes"
        ],
        "bim" => [
            "count" => 6,
            "label" => "Bimestre"
        ],
        "tri" => [
            "count" => 4,
            "label" => "Trimestre"
        ],
        "sem" => [
            "count" => 2,
            "label" => "Semestre"
        ],
        "anu" => [
            "count" => 1,
            "label" => date('Y')
        ],
    ];
    $cicles_data = [];
    for ($i=0; $i < 12; $i++) { 
        $cicles_data[] = [
            "id" => null,
            "real" => 0,
            "plan" => 0
        ];
    }
    if($kpi){
        $kpiDates = $kpi->kpiDates;
        foreach ($kpiDates as $k => $kpiDate) {
            $cicles_data[$k]["id"] = ($kpiDate->id?:null);
            $cicles_data[$k]["real"] = ($kpiDate->real_cantidad?:0)+0; // add +0 to remove excess of ceros
            $cicles_data[$k]["plan"] = ($kpiDate->meta_cantidad?:0)+0; // add +0 to remove excess of ceros
        }
    }
@endphp
<table class="table table-bordered m-0">
    <thead>
        <tr>
            <th class="text-center align-middle" width="110">Metas</th>
            @for ($i = 1; $i <= $cicles[$frequency]["count"]; $i++)
                <th class="text-center align-middle {{$frequency == "men"?'f-14':''}} show-highlights" dateid="{{$cicles_data[$i-1]["id"]}}" data-bs-toggle="modal" data-bs-target="#highlightModal">
                    @switch($frequency)
                        @case("men")
                            {{$months[$i-1]}}
                            @break
                        @case("anu")
                            {{$cicles[$frequency]["label"]}}
                            @break
                        @default
                            {{$cicles[$frequency]["label"]}} {{$i}}
                    @endswitch
                </th>
            @endfor
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center align-middle">Real</td>
            @for ($i = 1; $i <= $cicles[$frequency]["count"]; $i++)
                <td class="text-center align-middle p-0">
                    <span>{{$cicles_data[$i-1]["real"]}}</span>
                </td>
            @endfor
        </tr>
        <tr>
            <td class="text-center align-middle">Planificado</td>
            @for ($i = 1; $i <= $cicles[$frequency]["count"]; $i++)
                <td class="text-center align-middle p-0">
                    <span>{{$cicles_data[$i-1]["plan"]}}</span>
                </td>
            @endfor
        </tr>
    </tbody>
</table>