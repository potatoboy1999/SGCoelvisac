@extends('layouts.admin')

@section('title', 'Reunion')
    
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{asset('css/intranet/reunion.css')}}">
    <style>
        .border-right{
            border-right: 1px solid #ccc;
        }
        .area_select{
            width: calc(100% - 42px);
            display: inline-block;
        }
    </style>
@endsection

@section('content')
<div class="modal fade" id="alertModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Alerta
                <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <form id="new_results_form" action="{{route(isset($reunion)?'results.update':'results.store')}}" method="POST" enctype="multipart/form-data" autocomplete="off" onkeydown="return event.key != 'Enter';">
            @csrf
            @if (isset($reunion))
                <input type="hidden" name="reunion_id" value="{{$reunion->id}}">
            @endif
            <div class="card mb-4">
                <div class="card-header">
                    <span>
                        {{isset($reunion)?'Editar':'Nueva'}} Reunión
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-4">
                                        <label class="form-label" for="title">Título:</label>
                                        <input class="form-control" type="text" name="title" id="title" value="{{isset($reunion)?$reunion->titulo:''}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="date">Fecha:</label>
                                        <input class="form-control" type="text" name="date" id="date" value="{{date('d/m/Y',strtotime(isset($reunion)?$reunion->fecha:$date))}}" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="title">Descripción:</label>
                                <textarea class="form-control" name="description" id="description" rows="3" maxlength="500" required>{{isset($reunion)?$reunion->descripcion:''}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label" for="presenter">Presentadores:</label>
                                <input class="form-control" type="text" id="presenters">
                            </div>
                            <div id="presenters_names" class="mb-4">
                                @if (isset($reunion))
                                @foreach ($reunion->reunionPresenters as $presenter)
                                    <div class="presenter-name user{{$presenter->usuario_id}}" userid="{{$presenter->usuario_id}}">{{$presenter->user->nombre}}</div>
                                    <input class="user{{$presenter->usuario_id}}" type="hidden" name="users[]" value="{{$presenter->usuario_id}}">
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-100 text-end mb-3">
                <a href="#" class="btn btn-info text-white" id="addTheme">+ Nuevo Tema</a>
            </div>
            @if (isset($reunion))
                <?php $t_counter = 1; ?>
                {{-- <div id="themes_div" counter="{{$t_counter}}"> --}}
                <div id="themes_div" counter="{{sizeOf($reunion->reunionThemes)}}">
                    @foreach ($reunion->reunionThemes as $theme)
                        <div class="theme_elem card mb-4" id="theme{{$t_counter}}" theme_code="{{$t_counter}}" themeid="{{$theme->id}}">
                            <div class="card-header">
                                <div class="float-end">
                                    <a href="#" class="btn btn-sm btn-danger text-white rm_theme" theme="{{$t_counter}}" style="line-height: 1;">X</a>
                                </div>
                                <span>Tema:</span>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="theme_ids[]" value="{{$theme->id}}">
                                <div class="row">
                                    <div class="col-sm-4 border-right">
                                        <div class="mb-2">
                                            <label for="theme{{$t_counter}}" class="form-label">Nombre de Tema</label>
                                            <input type="text" class="form-control" name="theme[theme{{$t_counter}}]" value="{{$theme->titulo}}" required>
                                        </div>
                                        <div>
                                            <a class="btn btn-secondary text-white newAreaBtn" href="#" target="theme{{$t_counter}}">+ Nueva Área</a>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
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
                                        <div class="area_docs_list" counter="{{sizeOf($area_and_docs)+1}}">
                                            @foreach ($area_and_docs as $doc_area)
                                            {{-- <div class="area_docs border rounded p-3 mb-2" area-count="{{$a_counter}}"> --}}
                                            <div class="area_docs border rounded mb-2" area-count="{{$a_counter}}">
                                                <div class="w-100 text-end">
                                                    <a href="#" class="rm_area" area="{{$a_counter}}">X</a>
                                                </div>
                                                <div class="p-3">
                                                    <label class="form-label" for="area_name">Área:</label>
                                                    {{-- <select class="form-select area_select" name="area[theme{{$t_counter}}][]" id="area_name{{$a_counter}}">
                                                        @foreach ($areas as $area)
                                                            <option value="{{$area->id}}" {{$area->id == $doc_area['id']?'selected':''}}>{{$area->nombre}}</option>
                                                        @endforeach
                                                    </select> --}}
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
                                                    <input class="form-control area_select" type="text" id="area_name{{$a_counter}}" value="{{$area_name}}" readonly>
                                                    <input type="hidden" name="area[theme{{$t_counter}}][area{{$a_counter}}]" value="{{$area_id}}">
                                                    <hr>
                                                    <div class="old-files mb-3">
                                                        <label class="form-label">Archivos:</label>
                                                        @foreach ($doc_area['documents'] as $document)
                                                            <div class="old-file border rounded mb-1 p-2">
                                                                <a class="btn btn-sm btn-danger dlt-old-file" href="#" docid="{{$document['id']}}">
                                                                    <svg class="icon">
                                                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-x"></use>
                                                                    </svg>
                                                                </a>
                                                                <span>{{$document['name']}}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <label class="form-label">Agregar archivos:</label>
                                                    <input type="file" class="form-control not-filled mb-2" name="files[theme{{$t_counter}}][area{{$a_counter}}][]">
                                                </div>
                                            </div>
                                            <?php $a_counter++; ?>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php $t_counter++; ?>
                    @endforeach
                </div>
                <div id="themes-deleted"></div>
                <div id="docs-deleted"></div>
            @else
            <div id="themes_div" counter="1">
                <div class="theme_elem card mb-4" id="theme1" theme_code="1">
                    <div class="card-header">
                        <span>Tema:</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="mb-2">
                                    <label for="theme1" class="form-label">Nombre de Tema</label>
                                    <input type="text" class="form-control" name="theme[theme1]" required>
                                </div>
                                <div>
                                    <a class="btn btn-secondary text-white newAreaBtn" href="#" target="theme1">+ Nueva Área</a>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="area_docs_list" counter="2">
                                    <div class="area_docs border rounded p-3 mb-2" area-count="1">
                                        <label class="form-label" for="area_name">Área:</label>
                                        <select class="form-select area_select" name="area[theme1][area1]" id="area_name1">
                                            @foreach ($areas as $area)
                                                <option value="{{$area->id}}">{{$area->nombre}}</option>
                                            @endforeach
                                        </select>
                                        <hr>
                                        <label class="form-label">Archivos:</label>
                                        <input type="file" class="form-control not-filled mb-2" name="files[theme1][area1][]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="mb-4">
                <button form="new_results_form" class="btn btn-success text-white">{{isset($reunion)?'Guardar Cambios':'Crear Reunión'}}</button>
                <input type="submit" id="hidden-form-submit" style="display: none;" />
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script>
    let usernames_route = "{{route('user.name_list')}}";
</script>
<script src="{{asset("js/intranet/reunion_create.js")}}"></script>
<script>
    areas = JSON.parse('{!! json_encode($areas_arr) !!}');
</script>
@endsection