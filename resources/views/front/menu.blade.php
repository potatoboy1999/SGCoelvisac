@extends('layouts.front')

@section('title', 'Matriz')
    
@section('style')
<style>
  .matriz-option{
    margin: 0.25rem;
  }
</style>
@endsection

@section('content')

<div class="row">
  <div class="marco col-12">
    <div class="box">
      <h2 class="titulo">Matríz de Agenda de Gestión Estratégica</h2>
    </div>
  </div>
  <div class="marco col-12">
    <div class="box">
      <h3 class="titulo">ÁREAS</h3>
      <div class="cuerpo text-center">
        <!--<img src="{{asset('img/grap1.png')}}" alt="">-->
        <div class="d-flex flex-row flex-wrap">
          @foreach ($m_areas as $area)
            <a href="{{route('front.objectives').'?area='.$area->id}}" class="btn btn-secondary p-4 matriz-option">{{$area->nombre}}</a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')

@endsection