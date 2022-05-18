@extends('torneo.base')

@section('title')
    <title>FECNBA - {{$columna->titulo}}</title>
@endsection

@section('main')
  <h2>{{$columna->titulo}}</h2>
  <div class="mb-1 text-muted">{{$columna->created_at->toFormattedDateString()}} por {{$columna->autor}}</div>
  <hr>
  @if (isset($columna->photo))
  <div class="row mb-5">
    <div class="col-12">
      <img src="{{asset(str_replace('public/', '/storage/', $columna->photo))}}" class="img-fluid">
    </div>
  </div>
  @endif
  <div>
      {!!$columna->texto!!}
  </div>
  <div class="mt-3">
    <p>por <b>{{$columna->autor}}</b></p>
  </div>
  <hr>
  <div class="row">
    <div class="col-md-6 text-center">
        <h4>Visitá nuestras redes</h4>
        <p>Si querés formar parte de <b>CDJ</b> y dar una mano en la cobertura del Torneo de Ex Alumnos no dudes en contactarnos por nuestras redes</p>
    </div>
    <div class="col-md-6 d-flex align-items-center justify-content-center">
        <div class="row">
            <div class="col">
                <a class="btn btn-primary" style="background-color: #55acee; border-color: #55acee;" href="https://twitter.com/campodejuegoexa" role="button" target="_blank"><i class="fab fa-twitter"></i></a>
            </div>
            <div class="col">
                <a class="btn btn-primary" style="background-color: #ed302f; border-color: #ed302f;" href="https://www.youtube.com/channel/UCOSBm6JF-t8nyYph8REiVvA" role="button" target="_blank"><i class="fab fa-youtube"></i></a>
            </div>
            <div class="col">
                <a class="btn btn-primary" style="background-color: #ac2bac; border-color: #ac2bac;" href="https://www.instagram.com/campodejuegoexa/" role="button" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
            <div class="col">
                <a class="btn btn-primary" style="background-color: #3b5998; border-color: #3b5998;" href="https://www.facebook.com/campodejuego" role="button" target="_blank"><i class="fab fa-facebook"></i></a> 
            </div>
        </div>
    </div>
  </div>
@endsection