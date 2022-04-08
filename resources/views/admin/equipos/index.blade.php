@extends('adminlte::page')

@section('title', 'Dashboard')

@section('plugins.Select2', true)

@section('content_header')
    <div class="row">
        <div class="col-2">
            <form action="">
                <select class="form-control" id="torneo" onchange="setCookie('tournament', this.value, 365); location.reload()" >
                    @foreach ($torneos as $tournament)
                        <option value="{{$tournament->id}}" {{App\Models\Tournament::active()->id == $tournament->id ? 'selected' : ''}}>{{$tournament->name}}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-12">
                <h1>Equipos</h1>
                <p>Acá podes agregar o modificar los equipos existentes</p>
        </div>
    </div>
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
            <div class="col-md-6">
                <input
                type="text"
                name="searchBar"
                class="form-control"
                placeholder="Buscar equipo..."
                onkeyup="filterTable()"
                id="myInput"
                />
                <br>
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Equipos</h3>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-body">
                      <table class="table table-striped projects" id="equipos">
                          <thead>
                              <tr>
                                	<th>
                                     #
                                  </th>
                                  <th>
                                    Nombre
                                  </th>
                                  <th>
                                  	Categoría
                                  </th>
																	<th>	
                                  	Zona
                                  </th>
                                  <th>
                                    Acciones
                                  </th>
                              </tr>
                          </thead>
                          <tbody id="bodyTable">
                            @php
                                $torneo = App\Models\Tournament::active();
                            @endphp
                              @forelse ($torneo->equiposActivos() as $index => $equipo)
                              <tr>
                                <td>
                                    # {{$index + 1}}
                                </td>
                                <td class="className">
                                    <b>{{$equipo->name}}</b> 
                                </td>
                                <td>
                                    {{$equipo->category()->name}}
                                </td>
                                <td>
                                    {{$equipo->category()->pivot->zone}}
                                </td>
                                <td class="project-actions">
                                    <a class="btn btn-primary btn-sm" href="equipos/{{$equipo->id}}">
                                        <i class="fas fa-eye">
                                        </i>
                                    </a>
                                    <a class="btn btn-info btn-sm" href="equipos/editar/{{$equipo->id}}">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="equipo/eliminar/{{$equipo->id}}/torneo/{{$torneo->id}}" onclick="confirmacion()">
                                        <i class="fas fa-trash">
                                        </i>
                                    </a>
                                </td>
                            </tr>
                              @empty
                                  <h2 class="text-center">No hay equipos en el torneo seleccionado por el momento</h2>
                              @endforelse

                          </tbody>
                      </table>
                    </div>
                  </div>
            </div>
            <div class="col-md-6">
                <div class="card card-orange">
                  <div class="card-header">
                    <h3 class="card-title">Agregar Equipo al torneo</h3>
                  </div>
                  <form method="POST" action="/admin/equipos/agregar/torneo">
                      @csrf
                    <div class="card-body">
                      <div class="form-group">
                        <label for="team_id">Equipo</label>
                        <select name="team_id" id="team_id" class="select2 form-control team-selector">
                            @foreach ($equipos as $equipo)
                                <option value="{{$equipo->id}}">{{$equipo->name}}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Categoría</label>
                        <select class="form-control select2" style="width: 100%;" name="category_id">
                            @foreach ($categorias as $categoria)
                                <option value="{{$categoria->id}}">{{$categoria->name}}</option>
                            @endforeach
                        </select>
                      </div>
                    <div class="form-group">
                        <label>Torneo</label>
                        <select class="form-control select2" style="width: 100%;" name="tournament_id">
                            @foreach ($torneos as $torneo)
                                <option value="{{$torneo->id}}" {{$torneo->active ? 'selected' : ''}}>{{$torneo->name}}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">Agregar</button>
                    </div>
                  </form>
                </div>
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Crear Equipo</h3>
                  </div>
                  <form method="POST">
                      @csrf
                    <div class="card-body">
                      <div class="form-group">
                        <label for="nombreCategoria">Nombre del Equipo</label>
                        <input type="text" class="form-control" id="nombreCategoria" name="name" placeholder="Ej: Boca Juniors" required>
                      </div>
                      <div class="form-group">
                        <label>Categoría</label>
                        <select class="form-control select2" style="width: 100%;" name="category_id">
                            @foreach ($categorias as $categoria)
                            <option value="{{$categoria->id}}">{{$categoria->name}}</option>
                            @endforeach
                        </select>
                      </div>
                    <div class="form-group">
                        <label>Torneo</label>
                        <select class="form-control select2" style="width: 100%;" name="tournament_id">
                            @foreach ($torneos as $torneo)
                            <option value="{{$torneo->id}}" {{$torneo->active ? 'selected' : ''}}>{{$torneo->name}}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">Agregar</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script type="text/javascript">
        function confirmacion(){
            var respuesta = confirm('¿Esta seguro de eliminar el equipo de este torneo?');
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

        function setCookie(cookieName, cookieValue, nDays) {
            var today = new Date();
            var expire = new Date();

            if (!nDays) 
                nDays=1;

            expire.setTime(today.getTime() + 3600000*24*nDays);
            document.cookie = cookieName+"="+escape(cookieValue) + ";expires="+expire.toGMTString();
        }

        $('#equipos').DataTable({
          "paging": false,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": false,
          "autoWidth": false,
          "responsive": true,
        });

        $(document).ready(function() {
            $('.team-selector').select2();
        });
    </script>
@stop


