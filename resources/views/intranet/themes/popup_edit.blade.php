<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editThemeModalLabel">Editar Tema</h5>
            <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="edit_theme_form" action="{{route('theme.popup.update')}}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="theme_upd_id" value="{{$theme->id}}">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group py-1">
                            <label class="form-label" for="theme_desc">Nombre del Tema:</label>
                            <input id="theme_desc" class="form-control" type="text" name="upd_theme_desc" placeholder="Descripcion del tema" value="{{$theme->nombre}}" required>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button id="theme_delete" class="btn btn-danger text-white" type="button" route="{{route('theme.popup.delete')}}" theme-id="{{$theme->id}}" theme-name="{{$theme->nombre}}">Eliminar</button>
            <button id="theme_update" class="btn btn-info text-white" type="button">Guardar</button>
        </div>
    </div>
</div>