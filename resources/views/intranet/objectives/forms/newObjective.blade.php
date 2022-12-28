<form id="form-newObjective" action="{{route('obj_strat.matrix.store')}}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="form-label" for="">Pilar</label>
                <select id="pilar_select" class="form-select">
                    @foreach ($pilars as $pilar)
                    <option value="{{$pilar['id']}}">{{$pilar['nombre']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="form-label" for="">Dimensión</label>
                <select name="dimension_id" id="dimension_select" class="form-select">
                    @foreach ($pilars[0]['dimensions'] as $dimensions)
                    <option value="{{$dimensions->id}}">{{$dimensions->nombre}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="form-label" for="">Objetivo Estratégico</label>
                <input type="text" name="nombre" class="form-control" placeholder="¿Cuál es el objetivo?" required/>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="form-label" for="">Sponsor</label>
                <select name="area_id" id="sponsor_select" class="form-select">
                    @foreach ($areas as $area)
                    <option value="{{$area['id']}}">{{$area['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="form-label" for="">Rol</label>
                <select name="rol_id" id="rol_select" class="form-select">
                    <option value="">-- No aplica --</option>
                    @foreach ($areas[0]["roles"] as $role)
                    <option value="{{$role->id}}">{{$role->nombres}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="form-label" for="">Usuarios</label>
                <div class="border" style="border-radius: 5px; height: 120px; max-height: 120px; border-color: #b1b7c1!important; overflow-y: scroll;">
                    <ul id="users_list" class="px-3 my-2" style="list-style: none;">
                        @foreach ($areas[0]["users"] as $area)
                        <li>
                            <input class="form-check-input" id="user{{$area['id']}}" name="users[]" value="{{$area['id']}}" type="checkbox" data-object="role">
                            <label class="form-check-label" for="user{{$area['id']}}">{{$area['nombre']}}</label>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-success text-white float-end mx-1">Crear</button>
            <a class="btn btn-secondary text-white float-end" data-coreui-dismiss="modal">Cerrar</a>
        </div>
    </div>
</form>
<script>
    obj_form_data = JSON.parse('{!! json_encode(['pilars'=>$pilars,'areas'=>$areas]) !!}');
</script>