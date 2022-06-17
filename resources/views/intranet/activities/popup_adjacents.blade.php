<div class="modal-header">
    <h5 class="modal-title" id="adjacentModalLabel">Documento: Adjunto</h5>
    <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        @foreach ($activity->docAdjacents as $doc)
        <div class="col-12">
            <div id="" class="file-downloadable mb-3">
                <p><strong>Documento Adjunto:</strong></p>
                <p id="a_filename">{{$doc->nombre}}</p>
                <div class="mt-3">
                    <a id="" href="{{route("doc.download")}}" file-id="{{$doc->id}}" class="btn btn-success text-white btn-file-download">Descargar</a>
                    <a id="" href="{{route("doc.delete")}}" file-id="{{$doc->id}}" file-type="adj" class="btn btn-danger text-white btn-file-delete">Eliminar</a>
                </div>
            </div>
        </div>
        @endforeach
        <div class="col-12">
            <form id="adjacent-form" action="{{route('upd_activity_adjacent')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="">Nuevo Documento Adjunto:</label>
                    <input type="file" name="a_file" id="adjacent_upd_file" class="form-control" required>
                    <input type="hidden" name="a_edit" value="false">
                    <input type="hidden" name="a_act_id" value="{{$activity->id}}">
                </div>
            </form>
        </div>
        <div class="col-12">
            <div id="a_error" class="text-danger"></div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button id="adj_save" class="btn btn-info text-white" type="button" data-route="{{route('activity.popup.adjacents')}}">Guardar</button>
</div>