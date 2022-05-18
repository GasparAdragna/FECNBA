@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Columnas - FECNBA')

@section('content_header')
    <h1>Columnas:</h1>
    <p>Acá podes agregar o modificar las columnas de la home</p>
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
                  <h3 class="card-title">Agregar Columna:</h3>
                </div>
                <form method="POST" action="/cdj/columnas" enctype="multipart/form-data">
                    @csrf
                  <div class="card-body">
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group">
                          <label for="titulo">Título:</label>
                          <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="resumen">Resumen:</label>
                          <span class="text-muted">(esto es lo que se va a ver antes de entrar a la noticia)</span>
                          <textarea name="resumen" id="resumen" class="form-control" cols="30" rows="4">{{ old('resumen') }}</textarea>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="text">Texto:</label>
                          <textarea name="texto" id="text" class="form-control" cols="30" rows="10">{{ old('texto') }}</textarea>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="category_id">Categoría:</label>
                          <select name="category_id" id="category" class="select2 form-control" style="width: 100%;" required>
                            <option selected disabled>General</option>
                            @foreach ($categorias as $categoria)
                              <option value="{{$categoria->id}}" {{$categoria->id == old('categoria') ?? 'selected'}}>{{$categoria->name}}</option>  
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="tournament_id">Torneo:</label>
                          <select name="tournament_id" id="tournament" class="select2 form-control" style="width: 100%;" required>
                            @foreach ($torneos as $torneo)
                              <option value="{{$torneo->id}}" {{App\Models\Tournament::active()->id == $torneo->id ? 'selected' : ''}}>{{$torneo->name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="photo">Foto (opcional):</label>
                          <input type="file" class="form-control-file" name="photo">
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="autor">Autor:</label>
                            <input type="text" class="form-control" id="autor" name="autor" value="{{ old('autor') }}" required>
                            <input type="hidden" id="user_id" name="user_id" value="{{ Auth::id() }}" required>
                        </div>
                      </div>
                    </div>
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
              <label for="myInput">Buscar columna por título:</label>
              <input
              type="text"
              name="searchBar"
              class="form-control"
              placeholder="Buscar noticia..."
              onkeyup="filterTable()"
              id="myInput"
              />
            <br>
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Columnas:</h3>
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
                                Título
                              </th>
                              <th>
                                Categoría
                              </th>
                              <th>
                                Autor
                              </th>
                              <th>
                                Fecha de creación
                              </th>
                              <th>
                                Acciones
                              </th>
                          </tr>
                      </thead>
                      <tbody id="bodyTable">
                          @forelse ($columnas as $index => $columna)
                          <tr>
                            <td>
                                # {{$index +1 }}
                            </td>
                            <td class="className">
                                {{$columna->titulo}}
                            <td>
                                {{$columna->categoria->name}}
                            </td>
                            <td>
                                {{$columna->autor}}
                            </td>
                            <td>
                                {{date('d-m-y', strtotime($columna->updated_at))}}
                            </td>
                            <td class="project-actions">
                                <a class="btn btn-primary btn-sm" href="/columnas/{{$columna->id}}">
                                  <i class="fas fa-eye">
                                  </i>
                                </a>
                                <a class="btn btn-info btn-sm" href="/cdj/columnas/editar/{{$columna->id}}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="confirmacion({{$columna->id}})">
                                  <i class="fas fa-trash">
                                  </i>
                                </button>
                            </td>
                        </tr>
                          @empty
                              <h2 class="text-center">No hay columnas creadas por el momento</h2>
                          @endforelse
                      </tbody>
                  </table>
                </div>
              </div>
        </div>
        </div>
        <form method="POST" id="deleteForm">
          @csrf
        </form>
    </div>
@stop

@section('js')
    <script type="text/javascript">
      $(document).ready(function() {
          $('.select2').select2();
      });
        function confirmacion(id){
            var respuesta = confirm('¿Esta seguro de eliminar la columna? No podrá recuperarla');
            if (!respuesta) {
                window.event.preventDefault();
            } else {
              let form = document.getElementById('deleteForm');
              form.action = `/cdj/columnas/eliminar/${id}`; 
              form.submit();
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
    <script src="/js/nicEdit.js" type="text/javascript"></script>
    <script type="text/javascript">bkLib.onDomLoaded(new nicEditor().panelInstance('text'));</script>
@stop