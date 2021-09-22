@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Noticias - FECNBA')

@section('content_header')
    <h1>Noticias:</h1>
    <p>Acá podes agregar o modificar las noticias del home</p>
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
                  <h3 class="card-title">Agregar Noticia:</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="/admin/noticia/agregar" enctype="multipart/form-data">
                    @csrf
                  <div class="card-body">
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group">
                          <label for="titulo">Titulo:</label>
                          <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="text">Texto:</label>
                          <textarea name="texto" id="text" class="form-control" cols="30" rows="10" required>{{ old('texto') }}</textarea>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="state">Estado:</label>
                          <select name="estado" id="state" class="select2 form-control" style="width: 100%;" required>
                            <option selected disabled>Eliga un estado...</option>
                            <option value="Importante" {{"Importante" == old('estado') ?? 'selected'}}>Importante</option>
                            <option value="Información" {{"Información" == old('estado') ?? 'selected'}}>Información</option>
                            <option value="Programación" {{"Programación" == old('estado') ?? 'selected'}}>Programación</option>
                            <option value="Cancelación" {{"Cancelación" == old('estado') ?? 'selected'}}>Cancelación</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group mb-0">
                          <label for="photo">Foto (opcional):</label>
                          <input type="file" class="form-control-file" name="photo">
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
              <label for="myInput">Buscar Noticia por título:</label>
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
                  <h3 class="card-title">Noticias:</h3>
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
                                Descripción
                              </th>
                              <th>
                                Estado
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
                      @php
                      $i = 1;
                      @endphp
                      <tbody id="bodyTable">
                          @forelse ($noticias as $noticia)
                          <tr>
                            <td>
                                # {{$i}}
                            </td>
                            <td class="className">
                                {{$noticia->titulo}}
                            <td>
                                {!! Illuminate\Support\Str::limit($noticia->texto, 10) !!}
                            </td>
                            <td>
                                {{$noticia->estado}}
                            </td>
                            <td>
                                {{$noticia->user->name}}
                            </td>
                            <td>
                                {{date('d-m-y', strtotime($noticia->updated_at))}}
                            </td>
                            <td class="project-actions">
                                <a class="btn btn-primary btn-sm" href="/noticia/{{$noticia->id}}">
                                  <i class="fas fa-eye">
                                  </i>
                                </a>
                                <a class="btn btn-info btn-sm" href="/admin/noticia/editar/{{$noticia->id}}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                </a>
                                <a class="btn btn-danger btn-sm" href="/admin/noticia/eliminar/{{$noticia->id}}" onclick="confirmacion()">
                                    <i class="fas fa-trash">
                                    </i>
                                </a>
                            </td>
                        </tr>
                        @php
                        $i++;
                        @endphp
                          @empty
                              <h2 class="text-center">No hay noticias creadas por el momento</h2>
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
    <script src="/js/nicEdit.js" type="text/javascript"></script>
    <script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>
@stop