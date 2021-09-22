@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Jugadores - FECNBA')

@section('content_header')
    <h1>Jugadores:</h1>
    <p>Acá podes ver todos los jugadores y agregarlos fácilmente</p>
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
              <div class="card card-success">
                <div class="card-header">
                  <h3 class="card-title">Agregar Jugador</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="/admin/jugador/agregar">
                    @csrf
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="nombre">Nombre*</label>
                          <input type="text" class="form-control" id="nombre" name="first_name" value="{{ old('first_name') }}" placeholder="Ej: Carlos" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="apellido">Apellido*</label>
                          <input type="text" class="form-control" id="apellido" name="last_name" value="{{ old('last_name') }}" placeholder="Ej: Tevez" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="date">Fecha de nacimiento</label>
                          <input type="date" id="date" class="form-control" name="birthday" value="{{ old('birthday') }}" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="dni">DNI</label>
                          <input type="number" id="dni" class="form-control" required name="dni" value="{{ old('dni') }}" placeholder="Ej: 40999999">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="os">Obra Social</label>
                          <input type="text" id="os" name="os" class="form-control" value="{{ old('os') }}" placeholder="Ej: OSDE">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="egreso">Año de Egreso</label>
                          <input type="number" id="egreso" name="year" class="form-control" min="1950" value="{{ old('year') }}" placeholder="Ej: 2015">
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="mail">Email</label>
                          <input type="email" id="mail" name="email" class="form-control" value="{{ old('email') }}" placeholder="Ej: carlostevez@gmail.com">
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group mb-0">
                          <label for="team">Equipo*</label>
                          <select name="team_id" id="team" class="select2 form-control" style="width: 100%;">
                            <option selected disabled>Elegir equipo...</option>
                            @foreach ($equipos as $equipo)
                              <option value="{{$equipo->id}}">{{$equipo->name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                    <input type="hidden" name="team_id" value="{{$equipo->id}}">
                  </div>
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Agregar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        <div class="row">
          <div class="col-12">
              <label for="myInput">Buscar Jugador por nombre:</label>
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
                  <h3 class="card-title">Jugadores:</h3>
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
                                Equipo
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
                          @forelse ($jugadores as $player)
                          <tr>
                            <td>
                                # {{$i}}
                            </td>
                            <td class="className">
                                    {{$player->first_name}} {{$player->last_name}}
                            </td>
                            <td>
                              {{$player->team->name}}
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
                                <a class="btn btn-info btn-sm" href="/admin/jugador/editar/{{$player->id}}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                </a>
                                <a class="btn btn-danger btn-sm" href="/admin/jugador/eliminar/{{$player->id}}" onclick="confirmacion()">
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
    $(document).ready(function() {
        $('.select2').select2();
    });
        function confirmacion(){
            var respuesta = confirm('¿Esta seguro de eliminar el jugador? No podrá recuperarlo');
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