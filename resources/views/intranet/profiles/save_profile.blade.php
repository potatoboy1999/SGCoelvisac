<div class="modal-header">
    <h5>{{$profile?('Editar Perfil: '.$profile->descripcion):'Nuevo Perfil'}}</h5>
    <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form id="form_profile_save" action="{{route($profile?'user.profiles.popup.save.update':'user.profiles.popup.save.new')}}" method="POST">
        @csrf
        @if ($profile)
            <input type="hidden" name="id" value="{{$profile->id}}">
        @endif
        <div class="row">
            <div class="col-12">
                <div class="mb-2">
                    <label for="">Nombre</label>
                    <input class="form-control" type="text" name="description" value="{{$profile?$profile->descripcion:''}}" required>
                </div>
            </div>
            <div class="col-12">
                <label for="">Opciones</label>
                <div class="rounded border p-2">
                    @php
                        $selected = null;
                        if($profile){
                            $selected = $profile->options;
                        }
                    @endphp
                    @foreach ($options as $option)
                        <div class="mb-1" style="border-bottom: 1px solid #ccc">{{$option->opcion}}</div>
                        @foreach ($option->childrenOption as $child)
                        <div class="form-check">
                            <input class="form-check-input" id="option{{$child->id}}" name="options[]" value="{{$child->id}}" type="checkbox" {{$selected?($selected->contains('id', $child->id)?'checked':''):''}}>
                            <label class="form-check-label" for="option{{$child->id}}">{{$child->opcion}}</label>
                        </div>
                        @endforeach
                    @endforeach
                </div>
                {{-- <select class="form-select" name="options" id="" multiple >
                    @php
                        $selected = null;
                        if($profile){
                            $selected = $profile->options;
                        }
                    @endphp
                    @foreach ($options as $option)
                        <optgroup label="{{$option->opcion}}">
                            @foreach ($option->childrenOption as $child)
                            <option value="{{$child->id}}" {{$selected?($selected->contains('id', $child->id)?'selected':''):''}}>
                                {{$child->opcion}}
                            </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select> --}}
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <a href="javascript:;" class="btn btn-secondary" data-coreui-dismiss="modal">Cerrar</a>
    <input class="btn btn-success" type="submit" form="form_profile_save" value="Guardar">
</div>