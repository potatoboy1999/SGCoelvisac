<div class="modal-header">
    <h5>Agenda de Viaje</h5>
</div>
<div class="modal-body">
    <div class="modal-loading" style="display: none">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="modal-form">
        @php
            $form_action = "";
            if($action == 1){ // NEW
                route('agenda.nuevo');
            }
        @endphp
        <form id="form_schedule" action="{{$form_action}}" method="POST" onkeydown="return event.key != 'Enter';">
            @csrf
            @if ($schedule)
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-2">
                            <p class="m-0 border-bottom "><strong>Área</strong></p>
                            <p class="">{{$schedule->user->position->area->nombre}}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-2">
                            <p class="m-0 border-bottom "><strong>Nombre</strong></p>
                            <p class="">{{$schedule->user->nombre}}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-2">
                            <p class="m-0 border-bottom "><strong>Puesto / Cargo</strong></p>
                            <p class="">{{$schedule->user->position->nombre}}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-2">
                            <p class="m-0 border-bottom "><strong>Sede visitada</strong></p>
                            <p class="">{{$schedule->branch->nombre}}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <p class="m-0 border-bottom "><strong>Fecha Desde</strong></p>
                            <p class="">{{date("d/m/Y", strtotime($schedule->viaje_comienzo))}}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <p class="m-0 border-bottom "><strong>Fecha Hasta</strong></p>
                            <p class="">{{date("d/m/Y", strtotime($schedule->viaje_fin))}}</p>
                        </div>
                    </div>
                </div>
            @else
                @php
                    $user = Auth()->user();
                @endphp
                <input type="hidden" name="user" value="{{$user->id}}">
                <input type="hidden" name="branch" value="">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-2">
                            <label>Área</label>
                            <input id="sch_area" class="form-control" type="text" value="{{$user->position->area->nombre}}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-2">
                            <label>Nombre</label>
                            <input id="sch_user" class="form-control" type="text" value="{{$user->nombre}}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-2">
                            <label>Puesto / Cargo</label>
                            <input id="sch_position" class="form-control" type="text" value="{{$user->position->nombre}}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-2">
                            <label>Sede visitada</label>
                            {{-- <input id="sch_branch_name" class="form-control" type="text" value="" readonly> --}}
                            <select class="form-select" name="branch" id="branch_sel">
                                @foreach ($branches as $branch)
                                    <option value="{{$branch->id}}">{{$branch->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <label for="">Fecha Desde</label>
                            <input id="sch_date_start" class="form-control" type="text" name="date_start" value="{{date("d/m/Y", strtotime($s_date))}}" required readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <label for="">Fecha Hasta</label>
                            <input id="sch_date_end" class="form-control" type="text" name="date_end" value="{{date("d/m/Y", strtotime('+3 day',strtotime($s_date)))}}" required>
                        </div>
                    </div>
                </div>
            @endif
            <div class="my-2" id="sch_activities_list">
                <div class="card">
                    <div class="card-header">Actividades del área (max: 7)</div>
                    <div class="card-body p-2">
                        @if ($schedule)
                            <div id="area_act" class="act_areas">
                                @foreach ($schedule->activities->where('tipo',1) as $activity)
                                    <div class="mb-2 act-ta">
                                        <textarea rows="2" class="form-control" readonly>{{$activity->descripcion}}</textarea>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div id="area_act" class="act_areas" count="0"></div>
                            <div class="text-end text-white">
                                <a href="#" class="btn btn-secondary btn-sm add-act" type="area">
                                    <svg class="icon">
                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-plus"></use>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="my-2" id="sch_activities_list">
                <div class="card">
                    <div class="card-header">Actividades de otras áreas (max: 7)</div>
                    <div class="card-body p-2">
                        @if ($schedule)
                            <div id="non_area_act" class="act_areas">
                                @foreach ($schedule->activities->where('tipo',2) as $activity)
                                    <div class="mb-2 act-ta">
                                        <textarea rows="2" class="form-control" readonly>{{$activity->descripcion}}</textarea>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div id="non_area_act" class="act_areas" count="0"></div>
                            <div class="text-end text-white">
                                <a href="#" class="btn btn-secondary btn-sm add-act" type="non_area">
                                    <svg class="icon">
                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-plus"></use>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @if ($schedule)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" {{$schedule->vehiculo==1?'checked':''}} onclick="return false;">
                            <label class="form-check-label">¿Requiere Vehiculo?</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" {{$schedule->hospedaje==1?'checked':''}} onclick="return false;">
                            <label class="form-check-label">¿Requiere Hospedaje?</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" {{$schedule->viaticos==1?'checked':''}} onclick="return false;">
                            <label class="form-check-label">¿Requiere Viáticos?</label>
                        </div>
                    @else
                        <div class="form-check">
                            <input class="form-check-input" id="vehicle_check" name="vehicle_check" type="checkbox">
                            <label class="form-check-label" for="vehicle_check">¿Requiere Vehiculo?</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" id="hab_check" name="hab_check" type="checkbox">
                            <label class="form-check-label" for="hab_check">¿Requiere Hospedaje?</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" id="extras_check" name="extras_check" type="checkbox">
                            <label class="form-check-label" for="extras_check">¿Requiere Viáticos?</label>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
    <div class="modal-success" style="display: none">
        <p class="m-0">
            @if ($action == 1)
                <span class="text-success"><strong>!ÉXITO!</strong></span>
                <br> Agenda registrada y enviada a revisión
            @endif

            @if ($action >= 3)
                <span class="text-success"><strong>!ÉXITO!</strong></span>
                <br> La agenda fue validada
            @endif
        </p>
    </div>
</div>
<div class="modal-footer">
    <div class="form-btns">
        <button class="btn btn-secondary text-white" type="button" data-coreui-dismiss="modal" aria-label="Close">Cerrar</button>
        @if ($action == 1)
            <input class="btn btn-info text-white" type="submit" form="form_schedule" value="Crear">
        @elseif ($action >= 3)
            <button class="btn btn-danger text-white btn-actions travel-deny" data-travelid="{{$schedule->id}}" data-confirmation="{{$action==3?'1':'2'}}">Rechazar</button>
            <button class="btn btn-success text-white btn-actions travel-confirm" data-travelid="{{$schedule->id}}" data-confirmation="{{$action==3?'1':'2'}}">Confirmar</button>
        @endif
    </div>
</div>