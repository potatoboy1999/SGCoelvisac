<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editActivityModalLabel">Editar Actividad</h5>
            <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="edit_activity_form" action="{{route('activity.popup.update')}}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="act_upd_id" value="{{$activity->id}}">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group py-1">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label" for="activity_desc">Actividad:</label>
                                </div>
                                <div class="col-6 text-right">
                                    <div class="form-check form-switch float-end">
                                        <input class="form-check-input" id="act_done" name="act_done" type="checkbox" {{$activity->cumplido == 1?'checked':''}}>
                                        <label class="form-check-label" for="act_done">Cumplido</label>
                                    </div>
                                </div>
                            </div>
                            <input id="activity_desc" class="form-control" type="text" name="upd_activity_desc" placeholder="Descripcion de la actividad" value="{{$activity->nombre}}" required>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-group py-1">
                            <label class="form-label" for="act_upd_date_start">Fecha Inicio:</label>
                            <div class="input-group">
                                <input id="act_upd_date_start" class="form-control" type="text" name="act_upd_date_start" value="{{date('d/m/Y',strtotime($activity->fecha_comienzo))}}" required>
                                <span class="input-group-text">
                                    <svg class="icon">
                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-calendar"></use>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-group py-1">
                            <label class="form-label" for="act_upd_date_end">Fecha Fin:</label>
                            <div class="input-group">
                                <input id="act_upd_date_end" class="form-control" type="text" name="act_upd_date_end" value="{{date('d/m/Y',strtotime($activity->fecha_fin))}}" required>
                                <span class="input-group-text">
                                    <svg class="icon">
                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-calendar"></use>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button id="item_update" class="btn btn-info text-white" type="button">Guardar</button>
        </div>
    </div>
</div>