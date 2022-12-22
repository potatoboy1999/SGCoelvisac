<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered m-0">
                <thead>
                    <tr>
                        <th class="align-middle text-center" align="center">Highlights</th>
                        <th class="align-middle" align="center" width="48"></th>
                    </tr>
                </thead>
                <tbody>
                    @if ($kpi_date && $kpi_date->highlights && sizeof($kpi_date->highlights))
                    @foreach ($kpi_date->highlights as $highlight)
                    <tr>
                        <td class="align-middle">{{$highlight->descripcion}}</td>
                        <td class="align-middle">
                            <a href="{{route('kpi.highlights.delete')}}" class="btn btn-sm btn-danger text-white btn-delete" data-date="{{$kpi_date->id}}" data-id="{{$highlight->id}}">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td><i class="fa-solid fa-ellipsis"></i></td>
                        <td><i class="fa-solid fa-ellipsis"></i></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>