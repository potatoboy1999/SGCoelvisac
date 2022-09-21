<div class="modal-header">
    <h5>Generar Reporte</h5>
</div>
<div class="modal-body">
    <form action="{{route('agenda.tracking.pdf')}}" method="post" target="_blank" id="report_pdf" onkeydown="return event.key != 'Enter';">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="mb-2">
                    <label class="form-label">Estado:</label>
                    <select class="form-select" name="status">
                        <option value="ALL">Todos</option>
                        <option value="1">No Terminado</option>
                        <option value="2">Terminado</option>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <div class="mb-2">
                    <label class="form-label">Sede:</label>
                    <div class="p-2 border rounded">
                        @foreach ($branches as $branch)
                            <div class="form-check">
                                <input class="form-check-input" id="branch_check{{$branch->id}}" name="branches[]" value="{{$branch->id}}" type="checkbox" checked>
                                <label class="form-check-label" for="branch_check{{$branch->id}}">{{$branch->nombre}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="w-100">
                    <p class="branch_error w-100 text-danger border border-danger rounded m-0 p-2" style="display: none">Seleccione una o más sedes</p>
                </div>
            </div>
            <div class="col-12">
                <div class="mb-2">
                    <label class="form-label">Area:</label>
                    <div class="p-2 border rounded">
                        @foreach ($areas as $area)
                            <div class="form-check">
                                <input class="form-check-input" id="area_check{{$area->id}}" name="areas[]" value="{{$area->id}}" type="checkbox" checked>
                                <label class="form-check-label" for="area_check{{$area->id}}">{{$area->nombre}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="w-100">
                    <p class="area_error w-100 text-danger border border-danger rounded m-0 p-2" style="display: none">Seleccione una o más areas</p>
                </div>
            </div>
            <div class="col-12">
                <div class="mb-2">
                    <label class="form-label">Usuario <i>(No seleccione ninguno para buscar todos)</i></label>
                    <input class="form-control" type="text" id="user_name">
                </div>
                <div id="users" class="mb-2" style="display: none"></div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="form-group mb-2">
                    <label class="form-label" for="searchFrom">Buscar desde:</label>
                    <div class="input-group">
                        <input id="search_from" class="form-control clear-readonly" type="text" name="search_from" value="{{date('d/m/Y', strtotime("-1 month"))}}" onkeydown="return false;" readonly required>
                        <span class="input-group-text">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-calendar"></use>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="form-group mb-2">
                    <label class="form-label" for="searchTo">Buscar hasta:</label>
                    <div class="input-group">
                        <input id="search_to" class="form-control clear-readonly" type="text" name="search_to" value="{{date('d/m/Y', strtotime("now"))}}" onkeydown="return false;" readonly required>
                        <span class="input-group-text">
                            <svg class="icon">
                                <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-calendar"></use>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button class="btn btn-secondary text-white" type="button" data-coreui-dismiss="modal" aria-label="Close">Cerrar</button>
    <input class="btn btn-success text-white form_submit" type="submit" form="report_pdf" value="Buscar">
</div>