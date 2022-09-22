<div class="modal-header">
    <h5>Agenda de Viaje</h5>
    <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="modal-loading" style="display: none">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>
    <div class="modal-form">
        <form action="{{route('agenda.tracking.update')}}" method="POST" id="form_tracking">
            @csrf
            <input type="hidden" name="id" value="{{$activity->id}}">
            <div class="card mb-3">
                <div class="card-header">Viaje</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-2">
                                <p class="m-0 border-bottom"><strong>Área</strong></p>
                                <p class="mb-1">{{$activity->travelSchedule->user->position->area->nombre}}</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-2">
                                <p class="m-0 border-bottom"><strong>Nombre</strong></p>
                                <p class="mb-1">{{$activity->travelSchedule->user->nombre}}</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-2">
                                <p class="m-0 border-bottom"><strong>Puesto/Cargo</strong></p>
                                <p class="mb-1">{{$activity->travelSchedule->user->position->nombre}}</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-2">
                                <p class="m-0 border-bottom"><strong>Sede Visitada</strong></p>
                                <p class="mb-1">{{$activity->travelSchedule->branch->nombre}}</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-2">
                                <p class="m-0 border-bottom"><strong>Viaje Desde</strong></p>
                                <p class="mb-1">{{date("d/m/Y",strtotime($activity->travelSchedule->viaje_comienzo))}}</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-2">
                                <p class="m-0 border-bottom"><strong>Viaje Hasta</strong></p>
                                <p class="mb-1">{{date("d/m/Y",strtotime($activity->travelSchedule->viaje_fin))}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Actividad</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-2">
                                <label class="form-label" for=""><strong>Actividad</strong></label>
                                <p class="p-2 border rounded">{{$activity->descripcion}}</p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="mb-2">
                                <label class="form-label" for=""><strong>Acuerdo</strong></label>
                                <p class="p-2 border rounded">{{$activity->acuerdo}}</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-2">
                                <label class="form-label" for=""><strong>Fecha Desde</strong></label>
                                <div class="input-group">
                                    <input class="form-control" id="date_from" type="text" name="from_date" value="{{date('d/m/Y', strtotime($activity->fecha_comienzo))}}" onkeypress="return false;" required>
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-calendar"></use>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-2">
                                <label class="form-label" for=""><strong>Fecha Hasta</strong></label>
                                <div class="input-group">
                                    <input class="form-control" id="date_to" type="text" name="to_date" value="{{date('d/m/Y', strtotime($activity->fecha_fin))}}" onkeypress="return false;" required>
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-calendar"></use>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="mb-2">
                                <label class="form-label" for=""><strong>Estado</strong></label>
                                <select class="form-select" name="status">
                                    <option value="1" {{$rep_activity->estado == 1? 'selected':''}}>No iniciado</option>
                                    <option value="2" {{$rep_activity->estado == 2? 'selected':''}}>En curso</option>
                                    <option value="3" {{$rep_activity->estado == 3? 'selected':''}}>Terminado</option>
                                    <option value="4" {{$rep_activity->estado == 4? 'selected':''}}>No terminado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-success" style="display: none">
        <p class="m-0">
            <span class="text-success"><strong>!ÉXITO!</strong></span>
            <br> Actividad fue actualizada
        </p>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-secondary text-white" type="button" data-coreui-dismiss="modal" aria-label="Close">Cerrar</button>
    <input class="btn btn-success text-white" type="submit" form="form_tracking" value="Guardar">
</div>