@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Equipos</h1>
    <p>Acá podes agregar o modificar los equipos existentes</p>
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
                    <div class="card-body p-0">
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
                                    Categoría
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
                              @forelse ($equipos as $equipo)
                              <tr>
                                <td>
                                    # {{$i}}
                                </td>
                                <td class="className">
                                    <b>{{$equipo->name}}</b> 
                                </td>
                                <td>
                                    {{$equipo->category->name}}
                                </td>
                                <td class="project-actions">
                                    <a class="btn btn-primary btn-sm" href="equipo/{{$equipo->id}}">
                                        <i class="fas fa-eye">
                                        </i>
                                    </a>
                                    <a class="btn btn-info btn-sm" href="equipo/editar/{{$equipo->id}}">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="equipo/eliminar/{{$equipo->id}}" onclick="confirmacion()">
                                        <i class="fas fa-trash">
                                        </i>
                                    </a>
                                </td>
                            </tr>
                            @php
                            $i++;
                            @endphp
                              @empty
                                  <h2 class="text-center">No hay equipos creados por el momento</h2>
                              @endforelse

                          </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
            </div>
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Agregar Equipo</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form method="POST">
                      @csrf
                    <div class="card-body">
                      <div class="form-group">
                        <label for="nombreCategoria">Nombre del Equipo</label>
                        <input type="text" class="form-control" id="nombreCategoria" name="name" placeholder="Ej: Menores D" required>
                      </div>
                      <div class="form-group mb-0">
                        <label>Categoría</label>
                        <select class="form-control select2" style="width: 100%;" name="category_id">
                            @foreach ($categorias as $categoria)
                            <option value="{{$categoria->id}}">{{$categoria->name}}</option>
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
            var respuesta = confirm('¿Esta seguro de eliminar el equipo? Se eliminarán todos los jugadores, partidos jugados y jugadores. No podrá recuperarlos');
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


