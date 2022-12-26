@foreach ($action->documents as $doc)
<div class="col-12 doc-item doc-{{$doc->id}}">
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