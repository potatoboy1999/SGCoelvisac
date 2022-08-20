<div class="modal-header bg-modal-header">
    <p class="modal-title m-0" id="editThemeModalLabel">Reunión</p>
    <button class="btn-close" type="button" data-{{$source == "front"?"bs":"coreui"}}-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body" style="height: 100%;">
    <a href="{{route('doc.download')}}" class="btn btn-success btn-sm mb-2 text-white btn-download" docid="{{$document->id}}">Descargar</a> <strong>{{$document->nombre}}</strong>
    <div class="row" style="height: calc(100% - 2rem);">
        <div class="col-12">
            <div class="h-100 w-100">
                {{-- <iframe src="http://docs.google.com/gview?url={{asset($document->file)}}&embedded=true" style="width:100%; height:1000px;" frameborder="0"></iframe> --}}
                <iframe src="http://docs.google.com/gview?url=http://54.83.34.49:8000/uploads/file20220817-141009-17785402.pdf&embedded=true" style="width:100%; height:100%;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-{{$source == "front"?"bs":"coreui"}}-dismiss="modal" aria-label="Close">Cerrar</button>
    </div>
</div>