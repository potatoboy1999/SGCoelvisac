@if ($obj)
<form id="form-editObjective" action="{{route('specifics.matrix.update')}}">
    @csrf
    <input type="hidden" name="id" value="{{$obj->id}}">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="form-label" for="">Objetivo Específico</label>
                <input type="text" name="nombre" class="form-control" placeholder="¿Cuál es el objetivo?" value="{{$obj->nombre}}" required/>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="form-label" for="">Sponsor</label>
                <select name="area_id" id="sponsor_edit_select" class="form-select">
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($areas as $a => $area)
                    @php
                        if($obj->area_id == $area['id']){
                            $i = $a;
                        }
                    @endphp
                    <option value="{{$area['id']}}" {{$obj->area_id == $area['id']?'selected':''}}>{{$area['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="form-label" for="">Usuarios</label>
                <div class="border" style="border-radius: 5px; height: 120px; max-height: 120px; border-color: #b1b7c1!important; overflow-y: scroll;">
                    <ul id="users_edit_list" class="px-3 my-2" style="list-style: none;">
                        @php
                            $usrs = [];
                            foreach($obj->users as $usr){
                                $usrs[] = $usr->id;
                            }
                        @endphp
                        @foreach ($areas[$i]["users"] as $u)
                        <li>
                            <input class="form-check-input" id="user{{$u['id']}}" name="users[]" value="{{$u['id']}}" type="checkbox" data-object="role" {{(array_search($u['id'], $usrs) !== false)?'checked':''}}>
                            <label class="form-check-label" for="user{{$u['id']}}">{{$u['nombre']}}</label>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-success text-white float-end mx-1">Guardar</button>
            <a class="btn btn-secondary text-white float-end" data-coreui-dismiss="modal">Cerrar</a>
        </div>
    </div>
</form>
@else
<h3>Objetivo no encontrado</h3>
@endif