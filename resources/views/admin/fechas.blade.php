@extends('adminlte::page')

@section('title', 'Fechas')

@section('plugins.Select2', true)


@section('content_header')
    <h1>Fechas - {{$torneo->name}}</h1>
    <p>Acá podes agregar o modificar las fechas existentes</p>
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
                    <h3 class="card-title">Agregar Fecha</h3>
                  </div>
                  <form method="POST">
                      @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="tournament">Torneo:*</label>
                            <select name="tournament_id" id="tournament" class="selectWithoutSearch form-control" style="width: 100%;">
                                <option selected disabled>Elegir torneo...</option>
                                @foreach ($torneos as $torneo)
                                    <option value="{{$torneo->id}}">{{$torneo->name}}</option>    
                                @endforeach
                            </select>
                        </div>
                      <div class="form-group">
                        <label for="nombreFecha">Nombre de la fecha:*</label>
                        <input type="text" class="form-control" id="nombreFecha" name="name" placeholder="Ej: Fecha 1" required>
                      </div>
                      <div class="form-group mb-0">
                        <label for="diaFecha">Dia:</label>
                        <input type="date" class="form-control" id="diaFecha" name="dia">
                      </div>
                    </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">Agregar</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="col-12">
                  <h2>Fechas programadas:</h2>
                  <p>Para que se refleje el partido en la tabla el mismo tiene que estar en estado: <b>Terminado</b></p>
                  @forelse ($torneo->fechas as $fecha)
                  <div class="card card-outline card-primary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title"><b>{{$fecha->name}}</b> - <b>{{isset($fecha->dia) ? date('d-m-y', strtotime($fecha->dia)) : '??/??/???'}}</b></h3>
                        <div class="card-tools">
                            <a href="/admin/fecha/editar/{{$fecha->id}}" class="btn btn-info"><i class="fas fa-pencil-alt pl-1"></i></a>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                        </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                      <table class="table table-striped projects">
                        @if ($fecha->matches->count())
                          <thead>
                            <tr>
                                <th>
                                  #
                                </th>
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
                                  Fecha
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
                                <th>
                                  Estado
                                </th>
                                <th>
                                  Acciones
                                </th>
                            </tr>
                          </thead>
                        @endif         
                          @php
                          $i = 1;
                          @endphp
                          <tbody id="bodyTable">
                              @forelse ($fecha->matches->sortBy('horario') as $partido)
                              <tr>
                                <td>
                                    # {{$i}}
                                </td>
                                <td class="className">
                                  <b>{{$partido->local->name}}</b>
                                </td>
                                <td>
                                  {{$partido->goles->where('team_id', $partido->local->id)->count()}} - {{$partido->goles->where('team_id', $partido->visita->id)->count()}}
                                </td>
                                <td class="className">
                                    <b>{{$partido->visita->name}}</b>
                                </td>
                                <td>
                                  {{$partido->fecha->name}}
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
                                <td class="{{$partido->finished ? 'font-weight-bold' : ''}}">
                                  {{$partido->finished ? "Terminado" : "Sin terminar"}}
                                </td>
    
                                <td class="project-actions">
                                    <a class="btn btn-info btn-sm" href="/admin/partido/editar/{{$partido->id}}">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="/admin/partido/eliminar/{{$partido->id}}" onclick="confimacion()">
                                        <i class="fas fa-trash">
                                        </i>
                                    </a>
                                    <a class="btn {{$partido->finished ? 'btn-warning' : 'btn-primary' }} btn-sm" href="/admin/partido/{{$partido->id}}/terminado" data-toggle="tooltip" data-placement="top" title="{{$partido->finished ? 'Activar partido' : 'Terminar Partido'}}">
                                      @if ($partido->finished)
                                        <i class="fas fa-times">
                                        </i>
                                      @else
                                        <i class="fas fa-check">
                                        </i>
                                      @endif
                                    </a>
                                </td>
                            </tr>
                            @php
                            $i++;
                            @endphp
                              @empty
                                  <h2 class="text-center">No hay partidos creados por el momento</h2>
                              @endforelse
    
                          </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                  @empty
                      <p>No hay fechas disponibles</p>
                  @endforelse
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
        $('[data-toggle="tooltip"]').tooltip()    
    });
    function confirmacion(){
      var respuesta = confirm('¿Esta seguro de eliminar el usuario? No podrá recuperarlo');
      if (!respuesta) {
          window.event.preventDefault();
      }
    }
  </script>
@endsection