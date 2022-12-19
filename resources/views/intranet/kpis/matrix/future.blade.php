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
@endphp
<div class="card mb-4">
    <div class="card-body p-0">
        <table class="table table-bordered m-0">
            <thead>
                <tr>
                    <th class="text-center align-middle" width="110">Metas</th>
                    @for ($i = 1; $i <= $cicles[$frequency]["count"]; $i++)
                        <th class="text-center align-middle">
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
                    <td class="text-center align-middle">Planificado</td>
                    @for ($i = 1; $i <= $cicles[$frequency]["count"]; $i++)
                        <td class="text-center align-middle">0</td>
                    @endfor
                </tr>
            </tbody>
        </table>
    </div>
</div>