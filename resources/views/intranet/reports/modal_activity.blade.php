<div class="modal-header">
    <h5>Actividad</h5>
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
        @endphp
        <form id="form_activity" action="{{$form_action}}" method="POST" onkeydown="return event.key != 'Enter';">
            @csrf
            
            @php
                $user = Auth()->user();
            @endphp
            <input type="hidden" name="schedule_id" value="{{$schedule->id}}">
            <div class="row">
                <div class="col-12">
                    <div class="mb-2">
                        <label>Descripción</label>
                        <textarea class="form-control" name="descripcion" id="act_desc" rows="3">{{$rep_activity?$rep_activity->descripcion:''}}</textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-2">
                        <label>Acciones propuestas / Acuerdos</label>
                        <textarea class="form-control" name="acuerdo" id="act_deal" rows="3">{{$rep_activity?$rep_activity->acuerdo:''}}</textarea>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-2">
                        <label for="">Fecha Desde</label>
                        <input id="act_date_start" class="form-control read-clear" type="text" name="date_start" value="{{$rep_activity?date("d/m/Y", strtotime($rep_activity->fecha_comienzo)):''}}" required readonly>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-2">
                        <label for="">Fecha Hasta</label>
                        <input id="act_date_end" class="form-control read-clear" type="text" name="date_end" value="{{$rep_activity?date("d/m/Y", strtotime($rep_activity->fecha_fin)):''}}" required readonly>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-2">
                        <label for="">Estado</label>
                        <select class="form-select" name="estado" id="act_status">
                            @if ($rep_activity)
                                <option value="0" {{$rep_activity->fecha_fin == 0? 'selected':''}}>No terminado</option>
                                <option value="1" {{$rep_activity->fecha_fin == 1? 'selected':''}}>Terminado</option>
                            @else
                                <option value="0">No terminado</option>
                                <option value="1">Terminado</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-success" style="display: none">
        <p class="m-0">
            <span class="text-success"><strong>¡ÉXITO!</strong></span>
            <br> Actividad registrada
        </p>
    </div>
    <div class="modal-error" style="display: none">
        <p class="m-0">
            <span class="text-danger"><strong>¡ERROR!</strong></span>
            <br> Ocurrió un error
        </p>
    </div>
</div>
<div class="modal-footer">
    <div class="form-btns">
        <button class="btn btn-secondary text-white" type="button" data-coreui-dismiss="modal" aria-label="Close">Cerrar</button>
        <button class="btn btn-success text-white btn-actions activity-confirm">Guardar</button>
    </div>
</div>