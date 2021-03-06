@extends('torneo.base')

@section('title')
    <title>FECNBA - Programación {{$tournament->name}}</title>
@endsection

@section('main')
    @php
        $colores = ["table-danger", "table-secondary", "table-success", "table-primary", "table-warning", "table-info", "table-light", "table-dark", "table-dark"];
    @endphp
    <h1>Programación: {{$tournament->name}}</h1>
    <br>
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
                <br>
                <h3>Fecha programada para el día: {{isset($journey->dia) ? date('d/m', strtotime($journey->dia)) : 'Día sin definir'}}</h3>  
                <div class="table-responsive">
                      <table class="table table-hover table-bordered text-center" id="matches{{$index}}">
                          @if ($journey->matches->count())
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
                              @forelse ($journey->matches->sortBy('horario') as $partido)
                              <tr class="{{$colores[$partido->category_id -1]}}">
                                  <td class="className">
                                        <a class="teamAnchor" href="/equipo/{{$partido->local->id}}">{{$partido->local->name}}</a>
                                  </td>
                                  <td>
                                    @if ($partido->finished)
                                        {{$partido->goles->where('team_id', $partido->local->id)->count()}} - {{$partido->goles->where('team_id', $partido->visita->id)->count()}}
                                    @else
                                        -
                                    @endif
                                  </td>
                                  <td class="className">
                                        <a class="teamAnchor" href="/equipo/{{$partido->visita->id}}">{{$partido->visita->name}}</a>
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
                                  <h3 class="text-center">No hay partidos programados por el momento</h3>
                              @endforelse
                          </tbody>
                      </table>
                  </div>
              </div>
            @endforeach
        </div>
    </div>
@endsection

@section('js')
    <script>
        <?php
            foreach($tournament->fechas as $index => $date) {
                if(count($date->matches)) {
                ?>
                $("#matches{{$index}}").DataTable({
                    "paging": false,
                    "lengthChange": false,
                    "searching": false,
                    "ordering": true,
                    "order": [[ 3, "asc" ]],
                    "info": false,
                    "autoWidth": false,
                    "responsive": true,
            });
                
        <?php
                }
            }
        ?>
    </script>
@endsection