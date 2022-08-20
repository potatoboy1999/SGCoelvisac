<div class="p-1 mb-1">
    
    @php
    $docs = [];
    if($reunion){
        $areas_id = [];
        $docs = $reunion->documents;

        $area_and_docs = [];
        $area = [];
        for ($i=0; $i < sizeOf($docs); $i++) { 
            $doc = $docs[$i];
            if(!in_array($doc->pivot->area_id, $areas_id)){
                $areas_id[] = $doc->pivot->area_id;
                if($i!=0){
                    $area_and_docs[$area['id']] = $area;
                }
                $area['id'] = $doc->pivot->area_id;
                $area['documents'] = null;
            }
            $area_doc = [];
            $area_doc['id'] = $doc->id;
            $area_doc['name'] = $doc->nombre;
            $area_doc['filename'] = $doc->file;
            $area['documents'][] = $area_doc;
            if($i == (sizeOf($docs) - 1)){
                $area_and_docs[$area['id']] = $area;
            }
        }
    }
    @endphp 

    <div class="row">
        <div class="col-12">
            <p>
                <a href="#" class="btn btn-secondary btn-sm search-calendar" id="backToCalendar">
                    <svg class="icon">
                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-arrow-left"></use>
                    </svg> Atras
                </a> 
                Fecha: {{date('d/m/Y',strtotime($date))}}
            </p>
        </div>
    @foreach ($areas as $area)
        <div class="col-sm-4">
            <div class="box mb-2">
                <h3 class="titulo">{{$area->nombre}}</h3>
                <div class="cuerpo">
                    @if (isset($area_and_docs[$area->id]) && sizeof($area_and_docs[$area->id]['documents']) > 0)
                        <div class="old-files">
                        @foreach ($area_and_docs[$area->id]['documents'] as $document)
                            <div class="old-file mb-1">
                                <div class="file-section file-action">
                                    <div class="action-buttons bg-success btn-view text-white" href="{{route('doc.download')}}" docid="{{$document['id']}}">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                </div>
                                <div class="file-section file-name">
                                    <p class="filename m-0">{{$document['name']}}</p>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    @else
                        <p class="m-0 p-3 border rounded bg-light">No hay documentos para esta area</p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
        <div class="col-sm-4">
            <div class="box mb-2">
                <h3 class="titulo">Consolidado</h3>
                <div class="cuerpo">
                    @if ($reunion && sizeof($reunion->consolidado_documents) > 0)
                        <div class="old-files" areaid="0">
                        @foreach ($reunion->consolidado_documents as $document)
                            <div class="old-file mb-1">
                                <div class="file-section file-action">
                                    <div class="action-buttons bg-success btn-view text-white" href="{{route('doc.download')}}" docid="{{$document->id}}">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                </div>
                                <div class="file-section file-name">
                                    <p class="filename m-0">{{$document->nombre}}</p>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    @else
                        <p class="p-3 border rounded bg-light">No hay documentos para esta área</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>