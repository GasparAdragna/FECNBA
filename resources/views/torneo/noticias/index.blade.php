@extends('torneo.base')

@section('title')
    <title>FECNBA - Noticias</title>
@endsection

@section('main')
    <h3 class="bg-light-blue p-2 w-100">Noticias</h3>
    <div class="row">
        <div class="col-12">
            @php
                $index = 1;
            @endphp
            @foreach ($noticias as $noticia)
                @if ($index % 2 != 0)
                    <div class="row">
                        <div class="col-md-9">
                            <div class="noticia p-3">
                                <strong class="d-inline-block mb-2 text-danger">{{strtoupper($noticia->estado)}}</strong>
                                <h3> <a href="/noticia/{{$noticia->id}}" class="noticia-titulo">{{$noticia->titulo}}</a></h3>
                                <div class="mb-1 text-muted">{{$noticia->created_at->toFormattedDateString()}}</div>
                                <div class="card-text mb-auto">
                                    {!!$noticia->texto!!}
                                </div>
                                <br>
                                <a href="/noticia/{{$noticia->id}}" class="btn r-0 btn-dark-blue">Ir a la noticia...</a>
                            </div>
                        </div>
                        <div class="pr-0 col-3 d-none d-md-flex align-items-center">
                            @isset($noticia->photo)
                                <img src="{{asset(str_replace('public/', '/storage/', $noticia->photo))}}" class="img-fluid">
                            @else
                                <img src="{{asset('/storage/photos/escudo.png')}}" class="img-fluid">
                            @endisset                                    
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="pr-0 col-3 d-none d-md-flex align-items-center">
                            @isset($noticia->photo)
                                <img src="{{asset(str_replace('public/', '/storage/', $noticia->photo))}}" class="img-fluid">
                            @else
                                <img src="{{asset('/storage/photos/escudo.png')}}" class="img-fluid">
                            @endisset
                        </div>
                        <div class="col-md-9">
                            <div class="noticia text-right p-3">
                                <strong class="d-inline-block mb-2 text-danger">{{strtoupper($noticia->estado)}}</strong>
                                <h3> <a href="/noticia/{{$noticia->id}}" class="noticia-titulo">{{$noticia->titulo}}</a></h3>
                                <div class="mb-1 text-muted">{{$noticia->created_at->toFormattedDateString()}}</div>
                                <div class="card-text mb-auto">
                                    {!!$noticia->texto!!}
                                </div>
                                <br>
                                <a href="/noticia/{{$noticia->id}}" class="btn r-0 btn-dark-blue">Ir a la noticia...</a>
                            </div>
                        </div>
                    </div>
                @endif
                <hr>
                @php
                    $index++;
                @endphp
            @endforeach
        </div>
        <div class="col-12 d-flex justify-content-center">
            {{ $noticias->links() }}
        </div>
    </div>
@endsection

