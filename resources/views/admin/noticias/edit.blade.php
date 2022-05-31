@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Editar Noticia - FECNBA')

@section('content_header')
    <h1>Editar Noticia:</h1>
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
                  <h3 class="card-title">Editar Noticia:</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                  <div class="card-body">
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group">
                          <label for="titulo">Titulo:</label>
                          <input type="text" class="form-control" id="titulo" name="titulo" value="{{$noticia->titulo}}" required>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="resumen">Resumen:</label>
                          <span class="text-muted">(esto es lo que se va a ver antes de entrar a la noticia)</span>
                          <textarea name="resumen" id="resumen" class="form-control" cols="30" rows="4">{{$noticia->resumen}}</textarea>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="text">Texto:</label>
                          <textarea name="texto" id="text" class="form-control" cols="30" rows="10" required>{{ $noticia->texto }}</textarea>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="state">Estado:</label>
                          <select name="estado" id="state" class="select2 form-control" style="width: 100%;" required>
                            <option value="Importante" {{"Importante" == $noticia->estado ?? 'selected'}}>Importante</option>
                            <option value="Información" {{"Información" == $noticia->estado ?? 'selected'}}>Información</option>
                            <option value="Programación" {{"Programación" == $noticia->estado ?? 'selected'}}>Programación</option>
                            <option value="Cancelación" {{"Cancelación" == $noticia->estado ?? 'selected'}}>Cancelación</option>
                          </select>
                        </div>
                      </div>
                      
                      @if (isset($noticia->photo))
                        <div class="col-md-4">
                          <label>Foto:</label>
                          <img src="{{asset(str_replace('public/', '/storage/', $noticia->photo))}}" class="img-fluid">
                        </div>
                        <div class="col-md-8">
                          <label for="photo">Cambiar foto:</label>
                          <input type="file" class="form-control-file" name="photo">
                        </div>
                      @else
                        <div class="col-12">
                          <div class="form-group mb-0">
                            <label for="photo">Foto (opcional):</label>
                            <input type="file" class="form-control-file" name="photo">
                          </div>
                        </div>
                      @endif
                    </div>
                  </div>
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Editar</button>
                  </div>
                </form>
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
    </script>
    <script src="/js/nicEdit.js" type="text/javascript"></script>
    <script type="text/javascript">bkLib.onDomLoaded(new nicEditor().panelInstance('text'));</script>
@stop