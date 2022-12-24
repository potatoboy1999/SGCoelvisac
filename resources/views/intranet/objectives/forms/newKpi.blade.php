<div class="row">
    <div class="col-md-12">
        <div class="form-group mb-2">
            <label class="form-label" for="">Objetivo Estratégico</label>
            <select class="form-select" name="obj_strat" id="new_kpi_strat">
                @foreach ($objectives as $obj)
                    <option value="{{$obj->id}}">{{$obj->codigo}}: {{$obj->nombre}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-12">
        <button href="{{route('kpi')}}" id="kpi_redirect_btn" class="btn btn-success text-white float-end mx-1">Crear</button>
    </div>
</div>