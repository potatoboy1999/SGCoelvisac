<div class="table-responsive">
    <table class="table table-bordered m-0">
        <thead>
            <tr>
                <th class="align-middle text-center" align="center">Highlights</th>
            </tr>
        </thead>
        <tbody>
            @if ($kpi_date && $kpi_date->highlights && sizeof($kpi_date->highlights))
            @foreach ($kpi_date->highlights as $highlight)
            <tr>
                <td class="align-middle">{{$highlight->descripcion}}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td align="center">...</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>