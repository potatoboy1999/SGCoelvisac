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
    <?php 
        $allowed_areas = [1, 11];
        $is_allowed = in_array(Auth::user()->position->area_id, $allowed_areas);
    ?>
    @foreach ($areas as $area)
        <div class="col-sm-4">
            <div class="card mb-2">
                <div class="card-header" areaid={{$area->id}}>{{$area->nombre}}</div>
                <div class="card-body">
                    @if (isset($area_and_docs[$area->id]) && sizeof($area_and_docs[$area->id]['documents']) > 0)
                        <div class="old-files" areaid="{{$area->id}}">
                        @foreach ($area_and_docs[$area->id]['documents'] as $document)
                            <!-- NEW -->
                            <div class="old-file mb-1" docid="{{$document['id']}}">
                                @if ($is_allowed)
                                <div class="file-section file-action">
                                    <div class="action-buttons bg-danger dlt-old-file" docid="{{$document['id']}}">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-x"></use>
                                        </svg>
                                    </div>
                                </div>
                                @endif
                                <div class="file-section file-action">
                                    <div class="action-buttons bg-success btn-download" href="{{route('doc.download')}}" docid="{{$document['id']}}">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-arrow-thick-to-bottom"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="file-section file-name">
                                    <p class="filename m-0">{{$document['name']}}</p>
                                </div>
                            </div>
                            <!-- OLD -->
                            {{-- <div class="old-file border rounded mb-1 p-2" docid="{{$document['id']}}">
                                @if ($is_allowed)
                                <a class="btn btn-sm btn-danger dlt-old-file" href="#" docid="{{$document['id']}}">
                                    <svg class="icon">
                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-x"></use>
                                    </svg>
                                </a>
                                @endif
                                <a class="btn btn-sm btn-success btn-download" href="{{route('doc.download')}}" docid="{{$document['id']}}">
                                    <svg class="icon">
                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-arrow-thick-to-bottom"></use>
                                    </svg>
                                </a>
                                <span>{{$document['name']}}</span>
                            </div> --}}
                        @endforeach
                        </div>
                    @else
                        <div class="old-files" areaid="{{$area->id}}"></div>
                        @if (!$is_allowed)
                            <p class="m-0 p-3 border rounded bg-light">No hay documentos para esta area</p>
                        @endif
                    @endif
                    @if ($is_allowed)
                    <form id="newFileForm{{$area->id}}" action="{{route('results.doc.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="date" value="{{date('Y-m-d',strtotime($year."-".$month."-28"))}}">
                        <input type="hidden" name="reunion" value="{{$reunion?$reunion->id:'0'}}">
                        <input type="hidden" name="area" value="{{$area->id}}">
                        <label class="form-label">Agregar archivos:</label>
                        <input type="file" class="form-control add-file mb-2" name="file" areaid="{{$area->id}}">
                    </form>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
        <div class="col-sm-4">
            <div class="card mb-2">
                <div class="card-header">Consolidado</div>
                <div class="card-body">
                    @if ($reunion && sizeof($reunion->consolidado_documents) > 0)
                        <div class="old-files" areaid="0">
                        @foreach ($reunion->consolidado_documents as $document)
                            <!--NEW-->
                            <div class="old-file mb-1" docid="{{$document['id']}}">
                                @if ($is_allowed)
                                <div class="file-section file-action">
                                    <div class="action-buttons bg-danger dlt-old-file" docid="{{$document->id}}">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-x"></use>
                                        </svg>
                                    </div>
                                </div>
                                @endif
                                <div class="file-section file-action">
                                    <div class="action-buttons bg-success btn-download" href="{{route('doc.download')}}" docid="{{$document->id}}">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-arrow-thick-to-bottom"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="file-section file-name">
                                    <p class="filename m-0">{{$document->nombre}}</p>
                                </div>
                            </div>
                            <!--OLD-->
                            {{-- <div class="old-file border rounded mb-1 p-2" docid="{{$document->id}}">
                                @if ($is_allowed)
                                <a class="btn btn-sm btn-danger dlt-old-file" href="#" docid="{{$document->id}}">
                                    <svg class="icon">
                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-x"></use>
                                    </svg>
                                </a>
                                @endif
                                <a class="btn btn-sm btn-success btn-download" href="{{route('doc.download')}}" docid="{{$document->id}}">
                                    <svg class="icon">
                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-arrow-thick-to-bottom"></use>
                                    </svg>
                                </a>
                                <span>{{$document->nombre}}</span>
                            </div> --}}
                        @endforeach
                        </div>
                    @else
                        <div class="old-files" areaid="0"></div>
                        @if (!$is_allowed)
                            <p class="p-3 border rounded bg-light">No hay documentos para esta área</p>
                        @endif
                    @endif
                    @if ($is_allowed)
                    <form id="newFileForm0" action="{{route('results.doc.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="date" value="{{date('Y-m-d',strtotime($year."-".$month."-28"))}}">
                        <input type="hidden" name="reunion" value="{{$reunion?$reunion->id:'0'}}">
                        <input type="hidden" name="area" value="0">
                        <label class="form-label">Agregar archivos:</label>
                        <input type="file" class="form-control add-file mb-2" name="file" areaid="0">
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>