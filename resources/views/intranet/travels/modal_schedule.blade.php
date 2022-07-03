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
        <form id="form_schedule" action="{{route('agenda.nuevo')}}" method="POST" onkeydown="return event.key != 'Enter';">
            @php
                $user = Auth()->user();
            @endphp
            @csrf
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
                        <input id="sch_branch_name" class="form-control" type="text" value="" readonly>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-2">
                        <label for="">Fecha Desde</label>
                        <input id="sch_date_start" class="form-control" type="text" name="date_start" value="" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-2">
                        <label for="">Fecha Hasta</label>
                        <input id="sch_date_end" class="form-control" type="text" name="date_end" value="" required>
                    </div>
                </div>
            </div>
            <div class="my-2" id="sch_activities_list">
                <div class="card">
                    <div class="card-header">Actividades del área (max: 7)</div>
                    <div class="card-body p-2">
                        <div id="area_act" class="act_areas" count="0"></div>
                        <div class="text-end text-white">
                            <a href="#" class="btn btn-secondary btn-sm add-act" type="area">
                                <svg class="icon">
                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-plus"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="my-2" id="sch_activities_list">
                <div class="card">
                    <div class="card-header">Actividades de otras áreas (max: 7)</div>
                    <div class="card-body p-2">
                        <div id="non_area_act" class="act_areas" count="0"></div>
                        <div class="text-end text-white">
                            <a href="#" class="btn btn-secondary btn-sm add-act" type="non_area">
                                <svg class="icon">
                                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-plus"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
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
                </div>
            </div>
        </form>
    </div>
    <div class="modal-success" style="display: none">
        <p class="m-0">
            <span class="text-success"><strong>!ÉXITO!</strong></span>
            <br>
            Agenda registrada y enviada a revisión
        </p>
    </div>
</div>
<div class="modal-footer">
    <div class="form-btns">
        <button class="btn btn-secondary text-white" type="button" data-coreui-dismiss="modal" aria-label="Close">Cerrar</button>
        <input class="btn btn-info text-white" type="submit" form="form_schedule" value="Crear">
    </div>
</div>