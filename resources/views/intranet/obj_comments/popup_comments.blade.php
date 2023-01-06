@if ($objective)
    @foreach ($objective->comments as $comment)
    <div class="form-group py-1 comm-group" commid="{{$comment->user->id == Auth::user()->id ? $comment->id:''}}">
        @if ($comment->user->id == Auth::user()->id)
            <a href="{{route('obj_comments.delete')}}" class="btn btn-sm btn-danger comm-delete" commid="{{$comment->id}}">
                <svg class="icon">
                    <use xlink:href="{{asset("icons/sprites/free.svg")}}#cil-trash"></use>
                </svg>
            </a>
        @endif
        <label class="form-label">Fecha: {{date("d-m-Y", strtotime($comment->created_at))}}. Autor: {{$comment->user->nombre}}</label>
        <p class="p-2 mb-2 rounded border comm-border">
            {{$comment->descripcion}}
        </p>
    </div>
    @endforeach
@endif