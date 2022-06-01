@extends('adminlte::page')

@section('title', 'Dashboard')

@section('plugins.Select2', true)
@section('plugins.Sweetalert2', true)

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
          <h1>Fechas - {{App\Models\Tournament::active()->name}}</h1>
          <p>Acá podes agregar o modificar las fechas existentes</p>
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
                @if (session('error'))
                  <div class="callout callout-danger">
                    <h5>{{ session('error') }}</h5>
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
                                @foreach ($torneos as $torneo2)
                                    <option value="{{$torneo2->id}}"  {{App\Models\Tournament::active()->id == $tournament->id ? 'selected' : ''}}>{{$torneo2->name}}</option>    
                                @endforeach
                            </select>
                        </div>
                      <div class="form-group">
                        <label for="nombreFecha">Nombre de la fecha:*</label>
                        <input type="text" class="form-control" id="nombreFecha" name="name" placeholder="Ej: Fecha 1" value="{{old('name')}}" required>
                      </div>
                      <div class="form-group">
                        <label for="diaFecha">Dia:</label>
                        <input type="date" class="form-control" id="diaFecha" name="dia" value="{{old('dia')}}">
                      </div>
                      <div class="form-check">
                        <input type="hidden" name="active" value="0"/>
                        <input class="form-check-input" type="checkbox" name="active" id="active" value="1" checked>
                        <label class="form-check-label" for="active">
                          Activa
                        </label>
                      </div>
                      <br>
                      <p class="mb-0">Si marca la fecha como "Activa" esta se mostrará en la página principal y en la sección de "Programación" de la APP</p>
                      <p>Solo puede haber 1 fecha activa por vez. Si marca esta como activa, se desactivará la que este actualmente</p>
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
                  @forelse (App\Models\Tournament::active()->fechas as $fecha)
                    <div class="card card-outline card-primary collapsed-card">
                      <div class="card-header">
                          <h3 class="card-title"><b>{{$fecha->name}}</b> - <b>{{isset($fecha->dia) ? date('d-m-y', strtotime($fecha->dia)) : '??/??/???'}} {{$fecha->active ? '- FECHA ACTIVA' : ''}}</b></h3>
                          <div class="card-tools">
                            <div class="row align-items-center">
                              <div class="col">
                                <form action="/admin/fecha/eliminar/{{$fecha->id}}" method="POST" id="fecha{{$fecha->id}}">
                                  @csrf
                                  <button type="button" class="btn btn-danger" onclick="confirmacionFecha({{$fecha->id}})"><i class="fa fa-trash"></i></button>
                                </form>
                              </div>
                              <div class="col">
                                <a href="/admin/fecha/editar/{{$fecha->id}}" class="btn btn-info"><i class="fas fa-pencil-alt pl-1"></i></a>
                              </div>
                              <div class="col">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                  <i class="fas fa-plus"></i>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
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
                                      <button class="btn btn-danger btn-sm" onclick="confirmacion({{$partido->id}})">
                                          <i class="fas fa-trash">
                                          </i>
                                        </button>
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

    function confirmacionFecha(id){
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Se borrarán TODOS los partidos y los goles. No podrás revertir esta acción",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, borrarlo'
            }).then((result) => {
            if (result.value === true) {
              document.getElementById("fecha"+id).submit();
            }
        })
    }
    
    function setCookie(cookieName, cookieValue, nDays) {
        var today = new Date();
        var expire = new Date();

        if (!nDays) 
            nDays=1;

        expire.setTime(today.getTime() + 3600000*24*nDays);
        document.cookie = cookieName+"="+escape(cookieValue) + ";expires="+expire.toGMTString();
    }
  </script>
@endsection
