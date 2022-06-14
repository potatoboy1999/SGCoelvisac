<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editRoleModalLabel">Editar Rol</h5>
            <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="edit_role_form" action="{{route('role.popup.update')}}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="role_upd_id" value="{{$role->id}}">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group py-1">
                            <label class="form-label" for="role_desc">Nombre del Rol:</label>
                            <input id="role_desc" class="form-control" type="text" name="upd_role_desc" placeholder="Descripcion del rol" value="{{$role->nombre}}" required>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button id="role_delete" class="btn btn-danger text-white" type="button" route="{{route('role.popup.delete')}}" roleid="{{$role->id}}">Eliminar</button>
            <button id="role_update" class="btn btn-info text-white" type="button">Guardar</button>
        </div>
    </div>
</div>