@extends('adminlte::page')

@section('title', 'Editar Fecha')

@section('plugins.Select2', true)


@section('content_header')
    <h1>Editar Fecha:</h1>
    <p>Acá podes editar la fecha seleccionada</p>
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
                    <h3 class="card-title">Editar Fecha</h3>
                  </div>
                  <form method="POST">
                      @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="tournament">Torneo:*</label>
                            <select name="tournament_id" id="tournament" class="selectWithoutSearch form-control" style="width: 100%;">
                                @foreach ($torneos as $torneo)
                                    <option value="{{$torneo->id}}" {{$fecha->tournament->id == $torneo->id ? 'selected' : ''}}>{{$torneo->name}}</option>    
                                @endforeach
                            </select>
                        </div>
                      <div class="form-group">
                        <label for="nombreFecha">Nombre de la fecha:*</label>
                        <input type="text" class="form-control" id="nombreFecha" name="name" placeholder="Ej: Fecha 1" value="{{$fecha->name}}" required>
                      </div>
                      <div class="form-group">
                        <label for="diaFecha">Dia:</label>
                        <input type="date" class="form-control" id="diaFecha" name="dia" value="{{$fecha->dia}}">
                      </div>
                      <div class="form-check">
                        <input type="hidden" name="active" value="0"/>
                        <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{$fecha->active ? 'checked' : ''}}>
                        <label class="form-check-label" for="active">
                          Activa
                        </label>
                      </div>
                    </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">Editar</button>
                      <a href="/admin/fechas/torneo/{{$fecha->tournament->id}}" class="btn btn-warning">Volver</a>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
  <script>
    $(document).ready(function() {
        $('.selectWithoutSearch').select2({
          minimumResultsForSearch: Infinity
        });    
    });
    function confirmacion(){
      var respuesta = confirm('¿Esta seguro de eliminar el usuario? No podrá recuperarlo');
      if (!respuesta) {
          window.event.preventDefault();
      }
    }
  </script>
@endsection