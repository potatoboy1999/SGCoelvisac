<div class="modal-header">{{isset($branch)?'Editar Sede':'Nueva Sede'}}</div>
<div class="modal-body">
    <div class="modal-area modal-loading" style="display: none">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>
    <div class="modal-area modal-form">
        <form id="form_branch" action="{{route(isset($branch)?'branches.save.update':'branches.save.new')}}" method="POST">
            @csrf
            <div class="mb-2">
                <label class="form-label" for="">Nombre</label>
                <input class="form-control" type="text" name="name" value="{{isset($branch)?$branch->nombre:''}}" required>
            </div>
        </form>
    </div>
    <div class="modal-area modal-error">
        <p class="text-danger" id="error_msg"></p>
    </div>
</div>
<div class="modal-footer">
    <div class="align-end form-btns">
        <button class="btn btn-secondary text-white" type="button" data-coreui-dismiss="modal" aria-label="Close">Cerrar</button>
        <button class="btn btn-success text-white btn-actions" form="form_branch">{{isset($branch)?'Guardar':'Crear'}}</button>
    </div>
</div>