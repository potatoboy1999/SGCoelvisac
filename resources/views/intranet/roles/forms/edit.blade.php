<form id="form-editRole" action="{{route('areaRoles.update')}}" autocomplete="off">
    <input type="hidden" name="id" value="{{$role->id}}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="form-label" for="">Nombre</label>
                <input type="text" name="name" class="form-control" placeholder="Nombre del Rol" value="{{$role->nombres}}" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mt-2">
                <button type="submit" class="btn btn-success text-white float-end mx-1">Guardar</button>
                <a class="btn btn-secondary text-white float-end" data-coreui-dismiss="modal">Cerrar</a>
            </div>
        </div>
    </div>
</form>