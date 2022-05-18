@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Editar Columna - FECNBA')

@section('content_header')
    <h1>Editar Columna:</h1>
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
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Editar Columna:</h3>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                  <div class="card-body">
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group">
                          <label for="titulo">Título:</label>
                          <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $columna->titulo }}" required>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="resumen">Resumen:</label>
                          <span class="text-muted">(esto es lo que se va a ver antes de entrar a la noticia)</span>
                          <textarea name="resumen" id="resumen" class="form-control" cols="30" rows="4">{{ $columna->resumen }}</textarea>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="text">Texto:</label>
                          <textarea name="texto" id="text" class="form-control" cols="30" rows="10">{{ $columna->texto }}</textarea>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="category_id">Categoría:</label>
                          <select name="category_id" id="category" class="select2 form-control" style="width: 100%;" required>
                            <option>General</option>
                            @foreach ($categorias as $categoria)
                              <option value="{{$categoria->id}}" {{$categoria->id == $columna->category_id ? 'selected' : ''}}>{{$categoria->name}}</option>  
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="tournament_id">Torneo:</label>
                          <select name="tournament_id" id="tournament" class="select2 form-control" style="width: 100%;" required>
                            @foreach ($torneos as $torneo)
                              <option value="{{$torneo->id}}" {{$torneo->id == $columna->tournament_id ? 'selected' : ''}}>{{$torneo->name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      @if (isset($columna->photo))
                        <div class="col-md-4">
                          <label>Foto:</label>
                          <img src="{{asset(str_replace('public/', '/storage/', $columna->photo))}}" class="img-fluid">
                        </div>
                        <div class="col-md-8">
                          <label for="photo">Cambiar foto:</label>
                          <input type="file" class="form-control-file" name="photo">
                        </div>
                      @else
                        <div class="col-12">
                          <div class="form-group">
                            <label for="photo">Foto (opcional):</label>
                            <input type="file" class="form-control-file" name="photo">
                          </div>
                        </div>
                      @endif
                      <div class="col-12">
                        <div class="form-group">
                          <label for="autor">Autor:</label>
                            <input type="text" class="form-control" id="autor" name="autor" value="{{$columna->autor}}" required>
                            <input type="hidden" id="user_id" name="user_id" value="{{ Auth::id() }}" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Editar</button>
                  </div>
                </form>
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
          var respuesta = confirm('¿Esta seguro de eliminar el jugador? No podrá recuperarlo');
          if (!respuesta) {
              window.event.preventDefault();
          } else {
            let form = document.getElementById('deleteForm');
            form.action = `/cdj/columna/eliminar/${id}`; 
            form.submit();
          }
      }
    </script>
    <script src="/js/nicEdit.js" type="text/javascript"></script>
    <script type="text/javascript">bkLib.onDomLoaded(new nicEditor().panelInstance('text'));</script>
@stop