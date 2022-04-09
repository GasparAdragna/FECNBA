@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{$equipo->name}}</h1>
    <p>Acá podes ver los jugadores del equipo para el torneo: {{App\Models\Tournament::active()->name}}</p>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
                @if (session('status'))
                <div class="callout callout-success">
                    <h5>{{ session('status') }}</h5>
                  </div>
                @endif
                @if ($errors->any())
                <div class="callout callout-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-12">
              <input
              type="text"
              name="searchBar"
              class="form-control"
              placeholder="Buscar jugador..."
              onkeyup="filterTable()"
              id="myInput"
              />
              <br>
              <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Equipo: <b>{{$equipo->name}}</b></h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body table-responsive p-0">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>
                                  #
                                </th>
                                <th>
                                  Nombre
                                </th>
                                <th>
                                  Fecha
                                </th>
                                <th>
                                  DNI
                                </th>
                                <th>
                                  OS
                                </th>
                                <th>
                                  Egreso
                                </th>
                                <th>
                                  Acciones
                                </th>
                            </tr>
                        </thead>
                        @php
                        $i = 1;
                        @endphp
                        <tbody id="bodyTable">
                            @forelse ($equipo->players as $player)
                            <tr>
                              <td>
                                # {{$i}}
                              </td>
                              <td class="className">
                                {{$player->first_name}} {{$player->last_name}}
                              </td>
                              <td>
                                {{$player->birthday}}
                              </td>
                              <td>
                                {{$player->dni}}
                              </td>
                              <td>
                                {{$player->os}}
                              </td>
                              <td>
                                {{$player->year}}
                              </td>
                              <td class="project-actions">
                                  <a class="btn btn-info btn-sm" href="/admin/jugadores/editar/{{$player->id}}">
                                      <i class="fas fa-pencil-alt">
                                      </i>
                                  </a>
                                  <a class="btn btn-danger btn-sm" href="/admin/jugadores/eliminar/{{$player->id}}" onclick="confimacion()">
                                      <i class="fas fa-trash">
                                      </i>
                                  </a>
                              </td>
                          </tr>
                          @php
                          $i++;
                          @endphp
                            @empty
                                <h2 class="text-center">No hay jugadores en este equipo por el momento</h2>
                            @endforelse
  
                        </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
          </div>
          </div>
    </div>
@stop

@section('js')
    <script type="text/javascript">
        function confirmacion(){
            var respuesta = confirm('¿Esta seguro de eliminar el equipo? No podrá recuperarlo');
            if (!respuesta) {
                window.event.preventDefault();
            }
        }
        function filterTable(){
            var input, filter, table, tr, a, i, txtValue;
            input = document.getElementById('myInput');
            filter = input.value.toUpperCase();
            table = document.getElementById("bodyTable");
            tr = table.getElementsByTagName('tr');
            for (i = 0; i < tr.length; i++) {
                a = tr[i].getElementsByClassName('className')[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
                } else {
                tr[i].style.display = "none";
                }
            }
        }
    </script>
@stop


