<form id="form-newAction" action="{{route('action.store')}}" autocomplete="off">
    <input type="hidden" name="obj_id" value="{{$obj}}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="form-label" for="">Hito</label>
                <input type="text" name="hito" class="form-control" placeholder="¿A qué hito pertenece?" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="form-label" for="">Acción Específica</label>
                <input type="text" name="name" class="form-control" placeholder="¿Qué acción se realizará?" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="form-label" for="">Sponsor</label>
                <select name="area_id" id="sponsor_select" class="form-select">
                    @foreach ($areas as $area)
                    <option value="{{$area->id}}">{{$area->nombre}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-2">
                <label class="form-label" for="">Inicio</label>
                <input id="act_new_date_start" type="text" name="start_date" class="form-control" value="{{date('d/m/Y')}}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-2">
                <label class="form-label" for="">Fin</label>
                <input id="act_new_date_end" type="text" name="end_date" class="form-control" value="{{date('d/m/Y', strtotime('+3 days'))}}" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="form-label" for="">Estado</label>
                <select name="status" id="status_select" class="form-select">
                    {{-- 
                    0 = Deleted
                    1 = Not started
                    2 = Working on it
                    3 = Finished
                    4 = Not Finished 
                    --}}
                    <option value="1">No Iniciado</option>
                    <option value="2">En Curso</option>
                    <option value="3">Terminado</option>
                    <option value="4">No Terminado</option>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mt-2">
                <button class="btn btn-success text-white float-end mx-1">Crear</button>
                <a class="btn btn-secondary text-white float-end" data-coreui-dismiss="modal">Cerrar</a>
            </div>
        </div>
    </div>
</form>