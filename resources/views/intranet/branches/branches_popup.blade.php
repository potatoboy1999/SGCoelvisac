<div class="modal-header">
    <h5 class="modal-title">Sede</h5>
    <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="modal-area modal-loading">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>
    <div class="modal-area modal-form">
        <form action="{{route(isset($branch)?'branches.save.update':'branches.save.new')}}" method="POST" id="branchForm">
            @csrf
            @if (isset($branch))
                <input type="hidden" name="id" value="{{$branch->id}}">
            @endif
            <div class="row">
                <div class="col-8">
                    <div class="mb-2">
                        <label class="form-label" for="">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="branchName" value="{{isset($branch)?$branch->nombre:''}}" required>
                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-2">
                        <label class="form-label" for="">Color</label>
                        <input class="form-control" type="text" name="color" id="branchColor" value="{{isset($branch)?$branch->color:''}}" required>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-area modal-success">
        <p>
            @if (isset($branch))
            ¡La sede ha sido modificada correctamente!
            @else
            ¡La sede ha sido creada correctamente!
            @endif
        </p>
    </div>
    <div class="modal-area modal-error">
        <p class="text-danger" id="error_msg"></p>
    </div>
</div>
<div class="modal-footer">
    <div class="text-end">
        <button class="btn btn-success btn-actions text-white" form="branchForm">{{isset($branch)?'Guardar':'Crear'}}</button>
        <button type="button" class="btn btn-secondary text-white" data-coreui-dismiss="modal" aria-label="Close">Cerrar</button>
    </div>
</div>