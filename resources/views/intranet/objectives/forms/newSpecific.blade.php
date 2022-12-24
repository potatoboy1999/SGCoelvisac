<form id="form-newObjective" action="{{route('specifics.matrix.store')}}">
    @csrf
    <input type="hidden" name="strat_id" value="{{$obj_strat}}">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="form-label" for="">Objetivo Específico</label>
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
    obj_form_data = JSON.parse('{!! json_encode(['areas'=>$areas]) !!}');
</script>