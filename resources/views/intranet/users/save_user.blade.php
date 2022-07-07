<div class="modal-header">
    <h5>{{$user?('Editar Usuario: '.$user->nombre):'Nuevo Usuario'}}</h5>
    <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form id="form_user_save" action="{{route($user?'user.popup.save.update':'user.popup.save.new')}}" method="POST" onkeydown="return event.key != 'Enter';">
        @csrf
        @if ($user)
            <input type="hidden" name="id" value="{{$user->id}}">
        @endif
        <div class="row">
            <div class="col-12">
                <div class="mb-2">
                    <label for="">Nombre</label>
                    <input class="form-control" type="text" name="nombre" value="{{$user?$user->nombre:''}}" required>
                </div>
            </div>
            @if (!$user)
            <div class="col-12">
                <div class="mb-2">
                    <label for="">Contraseña</label>
                    <input class="form-control" type="password" name="password" value="" required>
                </div>
            </div>
            @endif
            <div class="col-12">
                <div class="mb-2">
                    <label for="">Email</label>
                    <input class="form-control" type="email" name="email" value="{{$user?$user->email:''}}" required>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="mb-2">
                    <label for="">Área</label>
                    <select class="form-select" name="area" id="user_area">
                        @php
                            $cur_area = null;
                        @endphp
                        @foreach ($areas as $area)
                            @if ($user)
                                @php
                                    if($area->id == $user->position->area->id){
                                        $cur_area = $area;
                                    }
                                @endphp
                                <option value="{{$area->id}}" {{$area->id == $user->position->area->id?'selected':''}}>{{$area->nombre}}</option>
                            @else
                                <option value="{{$area->id}}"> {{$area->nombre}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="mb-2">
                    <label for="">Posición</label>
                    <select class="form-select" name="position" id="user_position">
                        @if ($user && $cur_area)
                            @foreach ($cur_area->positions as $position)
                                <option value="{{$position->id}}" {{$position->id == $user->position->id?'selected':''}}>{{$position->nombre}}</option>
                            @endforeach
                        @else
                            @foreach ($areas[0]->positions as $position)
                                <option value="{{$position->id}}">{{$position->nombre}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-12">
                <label for="">Perfil</label>
                @php
                    $selected = null;
                    if($user){
                        $selected = $user->profiles;
                    }
                @endphp
                <select name="profile" class="form-select">
                    @foreach ($profiles as $profile)
                    <option value="{{$profile->id}}" {{$selected?($selected->contains('id', $profile->id)?'selected':''):''}}>
                        {{$profile->descripcion}}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <a href="javascript:;" class="btn btn-secondary text-white" data-coreui-dismiss="modal">Cerrar</a>
    <input id="save_user_btn" class="btn btn-success text-white" type="submit" form="form_user_save" value="Guardar">
</div>
<script>
    areas = JSON.parse('{!! json_encode($areas_arr) !!}');
</script>