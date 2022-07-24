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
                        <td><a class="teamAnchor" href="/equipo/{{$equipos[$i]->id}}"><b>{{$equipos[$i]->name}}</b></a></td>
                    @else
                        <td></td>
                    @endif

                    @if (isset($equipos[$i+1]))
                        <td><a class="teamAnchor" href="/equipo/{{$equipos[$i+1]->id}}"><b>{{$equipos[$i+1]->name}}</b></a></td>
                    @else
                        <td></td>
                    @endif

                    @if (isset($equipos[$i+2]))
                        <td><a class="teamAnchor" href="/equipo/{{$equipos[$i+2]->id}}"><b>{{$equipos[$i+2]->name}}</b></a></td>
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
                            <td><a class="teamAnchor" href="/equipo/{{$equipo->id}}">{{$equipo->name}}</a></td>
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
                            <td><a class="teamAnchor" href="/equipo/{{$equipo->id}}">{{$equipo->name}}</a></td>
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
                                        @if ($partido->finished)
                                            {{$partido->goles->where('team_id', $partido->local->id)->count()}} - {{$partido->goles->where('team_id', $partido->visita->id)->count()}}
                                        @else
                                            -
                                        @endif
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
      <br>
      <h3 class="bg-light-blue p-2 w-100">Sancionados</h3>
      @if (count($sancionados))
            <div class="col-12">
                <table class="table table-striped table-hover table-bordered text-center">
                    <thead class="bg-dark-blue text-white">
                        <tr>
                            <th>
                                Jugador
                            </th>
                            <th>
                                Equipo
                            </th>
                            <th>
                                Sanción
                            </th>
                            <th>
                                Motivo
                            </th>
                            <th>
                                Fecha
                            </th>
                        </tr>
                    </thead>
                    <tbody id="bodyTable">
                        @forelse ($sancionados as $sancion)
                            <tr>
                                <td>
                                    {{$sancion->name}}
                                </td>
                                <td class="className">
                                    <a class="teamAnchor" href="/equipo/{{$sancion->team->id}}">{{$sancion->team->name}}</a>
                                </td>
                                <td>
                                    {{$sancion->sanction}}
                                </td>
                                <td>
                                    {{$sancion->motive}}
                                </td>
                                <td>
                                    {{$sancion->fecha->name}}
                                </td>
                            </tr>
                        @empty
                            <h3 class="text-center mt-4">No hay sanciones creadas por el momento</h3>
                        @endforelse
                    </tbody>
                </table>
            </div>
      @else
        <h3 class="text-center mt-4">No hay sancionados para esta categoría</h3>  
      @endif
      
@endsection