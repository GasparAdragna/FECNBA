@extends('torneo.base')

@section('title')
    <title>FECNBA - Home</title>
@endsection

@section('main')
@php
    $colores = ["table-danger", "table-secondary", "table-success", "table-primary", "table-warning", "table-info", "table-light", "table-dark", "table-dark"];
@endphp
    <h3 class="bg-light-blue p-2 w-100">Noticias</h3>
    <div class="row">
        <div class="col-12">
            @php
                $index = 1;
            @endphp
            @foreach ($noticias as $noticia)
                @if ($index % 2 != 0)
                    <div class="row">
                        <div class="col-md-9 pr-0">
                            <div class="p-2 noticia">
                                <strong class="d-inline-block mb-2 text-danger">{{strtoupper($noticia->estado)}}</strong>
                                <h3> <a href="/noticia/{{$noticia->id}}" class="noticia-titulo">{{$noticia->titulo}}</a></h3>
                                <div class="mb-1 text-muted">{{$noticia->created_at->toFormattedDateString()}}</div>
                                <div class="card-text mb-auto">
                                    {!!\Illuminate\Support\Str::limit($noticia->texto, 200, '...')!!}
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
                                {!!\Illuminate\Support\Str::limit($noticia->texto, 400, '...')!!}
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
    @if ($fecha)
        <h3 class="bg-light-blue p-2 w-100">Próxima Fecha - {{$fecha->name}} - {{isset($fecha->dia) ? date('d/m', strtotime($fecha->dia)) : 'Día sin definir'}}</h3>
    <div class="row">
        <div class="col-12">
            @isset($fecha)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered text-center" id="matches">
                        @if ($fecha->matches->count())
                        <thead class="bg-dark-blue text-white">
                            <tr>
                                <th>
                                Local
                                </th>
                                <th>
                                Resultado
                                </th>
                                <th>
                                Visitante
                                </th>
                                <th>
                                Horario
                                </th>
                                <th>
                                Cancha
                                </th>
                                <th>
                                Categoría
                                </th>
                            </tr>
                        </thead>
                        @endif
                        <tbody id="bodyTable">
                            @forelse ($fecha->matches->sortBy('horario') as $partido)
                            <tr class="{{$colores[$partido->category_id -1]}}">
                                <td class="className">
                                    <b>{{$partido->local->name}}</b>
                                </td>
                                <td>
                                    {{$partido->goles->where('team_id', $partido->local->id)->count()}} - {{$partido->goles->where('team_id', $partido->visita->id)->count()}}
                                </td>
                                <td class="className">
                                    <b>{{$partido->visita->name}}</b>
                                </td>
                                <td>
                                    {{$partido->horario}}
                                </td>
                                <td>
                                    {{$partido->cancha}}
                                </td>
                                <td>
                                    {{$partido->category->name}}
                                </td>
                            </tr>
                            @empty
                                <h3 class="text-center mt-4">No hay partidos creados por el momento</h3>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                    <br><br>
            @else
                <h2>No hay una fecha programada todavía</h2>  
            @endisset
        </div>
    </div>
    @endif
@endsection

@section('js')
    <script>
        $('#matches').DataTable({
          "paging": false,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "order": [[ 3, "asc" ]],
          "info": false,
          "autoWidth": false,
          "responsive": true,
        });
    </script>
@endsection