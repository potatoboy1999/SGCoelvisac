@foreach ($action->documents as $doc)
<div class="col-12 doc-item doc-{{$doc->id}}">
    <div id="" class="file-downloadable mb-3">
        <p><strong>Documento Adjunto:</strong></p>
        <p id="a_filename">{{$doc->nombre}}</p>
        <div class="mt-3">
            <a id="" href="{{route("doc.download")}}" file-id="{{$doc->id}}" class="btn btn-success text-white btn-file-download">Descargar</a>
            <a id="" href="{{route("doc.delete")}}" file-id="{{$doc->id}}" class="btn btn-danger text-white btn-file-delete">Eliminar</a>
        </div>
    </div>
</div>
@endforeach
<div class="col-12">
    <form id="docs-form" action="{{route('action.docs.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="action_id" value="{{$action->id}}">
        <div class="form-group">
            <label for="">Nuevo Documento Adjunto:</label>
            <input type="file" name="a_file" class="form-control" required>
            <input type="hidden" name="a_edit" value="false">
        </div>
        <div class="mt-2">
            <button type="submit" class="btn btn-info text-white float-end" form="docs-form">Guardar</button>
        </div>
    </form>
</div>
<div class="col-12">
    <div id="a_error" class="text-danger"></div>
</div>