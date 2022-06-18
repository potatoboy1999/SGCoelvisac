<div class="modal-header">
    <h5 class="modal-title" id="adjacentModalLabel">Documento: Adjunto</h5>
    <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        @if (sizeof($activity->docAdjacents) > 0)
            <p>No hay documentos registrados</p>
        @endif
        @foreach ($activity->docAdjacents as $doc)
        <div class="col-12">
            <div id="" class="file-downloadable mb-3">
                <p><strong>Documento Adjunto:</strong></p>
                <p id="a_filename">{{$doc->nombre}}</p>
                <div class="mt-3">
                    <a id="" href="{{route("doc.download")}}" file-id="{{$doc->id}}" class="btn btn-success text-white btn-file-download">Descargar</a>
                </div>
            </div>
        </div>
        @endforeach
        <div class="col-12">
            <div id="a_error" class="text-danger"></div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button id="adj_save" class="btn btn-info text-white" type="button" data-route="{{route('activity.popup.adjacents')}}">Guardar</button>
</div>