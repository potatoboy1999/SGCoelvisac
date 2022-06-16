<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="commentsModalLabel">Comentarios</h5>
            <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="comments_form" action="{{route('comment.popup.update')}}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="act_id" value="{{$activity->id}}">
                <div class="row">
                    <div class="col-12">
                        @foreach ($comments as $comment)
                        <div class="form-group py-1 comm-group" commid="{{$comment->user->id == Auth::user()->id ? $comment->id:''}}">
                            @if ($comment->user->id == Auth::user()->id)
                                <a href="{{route('comment.popup.delete')}}" class="btn btn-sm btn-danger comm-delete" commid="{{$comment->id}}">
                                    <svg class="icon">
                                        <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-trash"></use>
                                    </svg>
                                </a>
                                <input type="hidden" name="comm_id[]" value="{{$comment->id}}">
                            @endif
                            <label class="form-label">Fecha: {{date("d-m-Y", strtotime($comment->created_at))}}. Autor: {{$comment->user->nombre}}</label>
                            <textarea {{$comment->user->id == Auth::user()->id?"name=comm_desc[] ":''}} 
                                class="form-control" 
                                rows="2" 
                                maxlength="500" 
                                comment-id="{{$comment->user->id == Auth::user()->id?$comment->id:''}}" 
                                {{$comment->user->id == Auth::user()->id?'required':'readonly'}}>{{$comment->descripcion}}</textarea>
                        </div>
                        @endforeach

                        <div class="form-group py-1 comm-group" commid="new">
                            <label class="form-label">Nuevo comentario:</label>
                            <textarea name="comm_desc[]" class="form-control" rows="2" maxlength="500" comment-id=""></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button id="comm_update" class="btn btn-info text-white" type="button" src="{{route('comment.popup.show').'?activity='.$activity->id}}">Guardar</button>
        </div>
    </div>
</div>