<div class="modal-header bg-modal-header">
    <h5 class="modal-title" id="editThemeModalLabel">Reunión</h5>
    <button class="btn-close" type="button" data-{{$source == "front"?"bs":"coreui"}}-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-8 col-sm-8">
            <div class="mb-2">
                <p class="m-0"><strong>Título</strong></p>
                <p class="p-2 bg-light rounded">{{$reunion->titulo}}</p>
            </div>
        </div>
        <div class="col-md-4 col-sm-4">
            <div class="mb-2">
                <p class="m-0"><strong>Fecha</strong></p>
                <p class="p-2 bg-light rounded">{{date('d/m/Y', strtotime($reunion->fecha))}}</p>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-2">
                <p class="m-0"><strong>Descripción</strong></p>
                <p class="p-2 bg-light rounded">{{$reunion->descripcion}}</p>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-2">
                <p class="m-0"><strong>Presentadores</strong></p>
                <p class="p-2 bg-light rounded">
                    @for ($i = 0; $i < sizeof($reunion->reunionPresenters); $i++)
                        <?php $presenter = $reunion->reunionPresenters[$i] ?>
                        {{$presenter->user->nombre}}{{$i == (sizeof($reunion->reunionPresenters)-1)?'':', '}}
                    @endfor
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-2">
                <p class="m-0"><strong>Temas</strong></p>
            </div>
            @foreach ($reunion->reunionThemes as $theme)
            <div class="rounded bg-dark p-2 mb-2">
                <p class="m-0 mb-2 text-center border-bottom text-white">{{$theme->titulo}}</p>
                @php
                    $areas_id = [];
                    $doc_areas = $theme->documents->filter(function($item) use ($theme){
                        return $item->pivot->reu_tema_id == $theme->id;
                    });

                    $area_and_docs = [];
                    $area = [];
                    for ($i=0; $i < sizeOf($doc_areas); $i++) { 
                        $doc = $doc_areas[$i];
                        if(!in_array($doc->pivot->area_id, $areas_id)){
                            $areas_id[] = $doc->pivot->area_id;
                            if($i!=0){
                                $area_and_docs[] = $area;
                            }
                            $area['id'] = $doc->pivot->area_id;
                            $area['documents'] = null;
                        }
                        $area_doc = [];
                        $area_doc['id'] = $doc->id;
                        $area_doc['name'] = $doc->nombre;
                        $area_doc['filename'] = $doc->file;
                        $area['documents'][] = $area_doc;
                        if($i == (sizeOf($doc_areas) - 1)){
                            $area_and_docs[] = $area;
                        }
                    }
                    $a_counter = 1;
                @endphp 
                @foreach ($area_and_docs as $doc_area)

                @php
                    $area_id = 0;
                    $area_name = "";
                    foreach($areas as $area){
                        if($area->id == $doc_area['id']){
                            $area_id = $area->id;
                            $area_name = $area->nombre;
                        }
                    }
                @endphp
                <div class="rounded bg-white mt-2 p-2">
                    <p class="m-0 mb-2">Área: {{$area_name}}</p>
                    @foreach ($doc_area['documents'] as $document)
                        <div class="border rounded mb-1 p-2">
                            <a class="btn btn-sm btn-success btn-download" href="{{route('doc.download')}}" docid="{{$document['id']}}">
                                <svg class="icon">
                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-arrow-thick-to-bottom"></use>
                                </svg>
                            </a>
                            <span>{{$document['name']}}</span>
                        </div>
                    @endforeach
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-{{$source == "front"?"bs":"coreui"}}-dismiss="modal" aria-label="Close">Cerrar</button>
    </div>
</div>