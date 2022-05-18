@extends('torneo.base')

@section('title')
    <title>FECNBA - CDJ</title>
@endsection

@section('main')
    <h3 class="bg-light-blue p-2 w-100">Columnas y Crónicas - por <img src="{{asset('/storage/photos/cdj.webp')}}" alt="CDJ" class="cdj"> CDJ</h3>
    <div class="row">
        <div class="col-12">
            @php
                $index = 1;
            @endphp
            @forelse ($columnas as $columna)
                @if ($index % 2 != 0)
                    <div class="row">
                        <div class="col-md-9">
                            <div class="noticia p-3">
                                <strong class="d-inline-block mb-2 text-danger">{{isset($columna->category_id) ? strtoupper($columna->categoria->name) : 'GENERAL'}}</strong>
                                <h3> <a href="/columna/{{$columna->id}}" class="noticia-titulo">{{$columna->titulo}}</a></h3>
                                <div class="mb-1 text-muted">{{$columna->created_at->toFormattedDateString()}} - por {{$columna->autor}}</div>
                                <div class="card-text mb-auto">
                                    <p>{{$columna->resumen}}</p>
                                </div>
                                <br>
                                <a href="/columna/{{$columna->id}}" class="btn r-0 btn-dark-blue">Ir a la columna...</a>
                            </div>
                        </div>
                        <div class="pr-0 col-3 d-none d-md-flex align-items-center">
                            @isset($columna->photo)
                                <img src="{{asset(str_replace('public/', '/storage/', $columna->photo))}}" class="img-fluid">
                            @else
                                <img src="{{asset('/storage/photos/cdj.webp')}}" class="img-fluid">
                            @endisset                                    
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="pr-0 col-3 d-none d-md-flex align-items-center">
                            @isset($columna->photo)
                                <img src="{{asset(str_replace('public/', '/storage/', $columna->photo))}}" class="img-fluid">
                            @else
                                <img src="{{asset('/storage/photos/escudo.png')}}" class="img-fluid">
                            @endisset
                        </div>
                        <div class="col-md-9">
                            <div class="noticia text-right p-3">
                                <strong class="d-inline-block mb-2 text-danger">{{isset($columna->category_id) ? strtoupper($columna->categoria->name) : 'GENERAL'}}</strong>
                                <h3> <a href="/noticia/{{$columna->id}}" class="noticia-titulo">{{$columna->titulo}}</a></h3>
                                <div class="mb-1 text-muted">{{$columna->created_at->toFormattedDateString()}} - por {{$columna->autor}}</div>
                                <div class="card-text mb-auto">
                                    <p>{{$columna->resumen}}</p>
                                </div>
                                <br>
                                <a href="/noticia/{{$columna->id}}" class="btn r-0 btn-dark-blue">Ir a la columna...</a>
                            </div>
                        </div>
                    </div>
                @endif
                <hr>
                @php
                    $index++;
                @endphp
            @empty
            <div class="row">
                <div class="col-12 mb-5 mt-4 d-flex justify-content-center align-items-center">
                    <h2>No hay columnas creadas por el momento</h2>
                </div>
            </div>
            <hr>
            @endforelse
        </div>
        <div class="col-12 d-flex justify-content-center">
            {{ $columnas->links() }}
        </div>
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

