@extends('torneo.base')

@section('title')
    <title>FECNBA - Home</title>
@endsection

@section('main')
  <h2>{{$noticia->titulo}}</h2>
  <div class="mb-1 text-muted">{{$noticia->created_at->toFormattedDateString()}} por {{$noticia->user->name}}</div>
  <hr>
  <div>
      {!!$noticia->texto!!}
  </div>
@endsection