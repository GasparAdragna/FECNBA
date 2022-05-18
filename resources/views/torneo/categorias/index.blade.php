@extends('torneo.base')

@section('title')
    <title>FECNBA - {{$categoria->name}}</title>
@endsection

@section('main')
    <h1>{{$categoria->name}}</h1>
    <br>
    <h3 class="bg-light-blue p-2 w-100">Equipos</h3>
    <table class="table table-bordered">
        <tbody>
            @for ($i = 0; $i < count($equipos); $i = $i + 3)
                <tr>
                    @if (isset($equipos[$i]))
                        <td><b>{{$equipos[$i]->name}}</b></td>
                    @else
                        <td></td>
                    @endif

                    @if (isset($equipos[$i+1]))
                        <td><b>{{$equipos[$i+1]->name}}</b></td>
                    @else
                        <td></td>
                    @endif

                    @if (isset($equipos[$i+2]))
                        <td><b>{{$equipos[$i+2]->name}}</b></td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endfor
        </tbody>
    </table>
    <br>
    <h3 class="bg-light-blue p-2 w-100">Tabla de posiciones</h3>
    <br>
    @if (count($table) > 1)
        @foreach ($table as $numero => $zona)
            <h5 class="bg-dark-blue text-white p-2 w-100">Zona {{$numero + 1}}</h5>
            <table class="table table-responsive table-striped">
                <thead>
                    <tr>
                        <th scope="col">Pos</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">PJ</th>
                        <th scope="col">G</th>
                        <th scope="col">E</th>
                        <th scope="col">P</th>
                        <th scope="col">DG</th>
                        <th scope="col">PTS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($zona as $index => $equipo)
                        <tr>
                            <th scope="row">{{$index +1}}</th>
                            <td>{{$equipo->name}}</td>
                            <td>{{$equipo->PJ}}</td>
                            <td>{{$equipo->G}}</td>
                            <td>{{$equipo->E}}</td>
                            <td>{{$equipo->P}}</td>
                            <td>{{$equipo->DIF}}</td>
                            <td><b>{{$equipo->PTS}}</b></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
        @endforeach
    @else
        @php
        if (isset($table[0])) {
            $zona = $table[0];
        } else {
            $zona = [];
        }
        @endphp
        <table class="table table-responsive table-striped">
                <thead>
                    <tr>
                        <th scope="col">Pos</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">PJ</th>
                        <th scope="col">G</th>
                        <th scope="col">E</th>
                        <th scope="col">P</th>
                        <th scope="col">DG</th>
                        <th scope="col">PTS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($zona as $index => $equipo)
                        <tr>
                            <th scope="row">{{$index +1}}</th>
                            <td>{{$equipo->name}}</td>
                            <td>{{$equipo->PJ}}</td>
                            <td>{{$equipo->G}}</td>
                            <td>{{$equipo->E}}</td>
                            <td>{{$equipo->P}}</td>
                            <td>{{$equipo->DIF}}</td>
                            <td><b>{{$equipo->PTS}}</b></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
    @endif
      <h3 class="bg-light-blue p-2 w-100">Fixture</h3>
      <div class="col-12">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                @foreach ($tournament->fechas as $index => $journey)
                    <button class="{{$index == 0 ? 'nav-link active' : 'nav-link'}}" id="nav-{{$index}}-tab" data-bs-toggle="tab" data-bs-target="#nav-{{$index}}" type="button" role="tab" aria-controls="nav-{{$index}}" aria-selected="true">{{$journey->name}}</button>
                @endforeach
            </div>
          </nav>
          <div class="tab-content" id="nav-tabContent">
              @foreach ($tournament->fechas as $index => $journey)
                <div class="{{$index == 0 ? 'tab-pane fade show active' : 'tab-pane fade'}}" id="nav-{{$index}}" role="tabpanel" aria-labelledby="nav-{{$index}}-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered text-center">
                            @if ($journey->matches->where('category_id', $categoria->id)->count())
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
                                </tr>
                            </thead>
                            @endif
                            <tbody id="bodyTable">
                                @forelse ($journey->matches->where('category_id', $categoria->id)->sortBy('horario') as $partido)
                                <tr>
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
                                </tr>
                                @empty
                                    <h3 class="text-center mt-4">No hay partidos creados por el momento</h3>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
              @endforeach
          </div>
      </div>
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
        </div>
      {{-- <h3 class="bg-light-blue p-2 w-100">Goleadores</h3> --}}
      
@endsection