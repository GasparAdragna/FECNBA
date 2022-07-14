@extends('torneo.base')

@section('title')
    <title>FECNBA - {{$equipo->name}}</title>
@endsection

@section('main')
    <h1>{{$equipo->name}}</h1>
    <br>
      <h3 class="bg-light-blue p-2 w-100">Fixture</h3>
      <div class="col-12 p-2">
        <div class="row border-top border-start">
          @foreach ($matches as $partido)
              <div class="col-md-6 p-3 border-bottom border-end">
                <div class="row">
                  <p class="mb-2 fechaTitle">{{$partido->category->name}} - {{$partido->fecha->name}} - {{isset($partido->fecha->dia) ? date('d/m', strtotime($partido->fecha->dia)) : 'Día sin definir'}}</p>
                  <div class="col-9 border-end mt-2 mb-2">
                    <div class="d-flex align-items-center justify-content-between">
                      @if ($partido->local->id == $equipo->id)
                        <div>
                          <p>
                            <strong>{{$partido->local->name}}</strong>
                          </p>
                        </div>
                        <div>
                          <p>
                            <strong>{{$partido->goles->where('team_id', $partido->local->id)->count()}}</strong>
                          </p>
                        </div>
                      @else
                        <div>
                          <p>
                            <a class="teamAnchor fw-normal" href="/equipo/{{$partido->local->id}}">{{$partido->local->name}}</a>
                          </p>
                        </div>
                        <div>
                          <p>
                            {{$partido->goles->where('team_id', $partido->local->id)->count()}}
                          </p>
                        </div>
                      @endif
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                      @if ($partido->visita->id == $equipo->id)
                        <div>
                          <p class="mb-0">
                            <strong>{{$partido->visita->name}}</strong>
                          </p>
                        </div>
                        <div>
                          <p class="mb-0">
                            <strong>{{$partido->goles->where('team_id', $partido->visita->id)->count()}}</strong>
                          </p>
                        </div>
                      @else
                        <div>
                          <p class="mb-0">
                            <a class="teamAnchor fw-normal" href="/equipo/{{$partido->visita->id}}">{{$partido->visita->name}}</a>
                          </p>
                        </div>
                        <div>
                          <p class="mb-0">
                            {{$partido->goles->where('team_id', $partido->visita->id)->count()}}
                          </p>
                        </div>
                      @endif
                    </div>
                  </div>
                  <div class="col-3 d-flex align-items-center justify-content-center">
                    <div>{{$partido->horario}} - {{$partido->cancha}}</div>
                  </div>
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
        <h3 class="text-center mt-4">No hay sancionados para este equipo</h3>  
      @endif
      
@endsection