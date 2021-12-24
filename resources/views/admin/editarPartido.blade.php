@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Partidos - FECNBA')

@section('content_header')
    <h1>{{$partido->local->name}} {{$partido->goles->where('team_id', $partido->local->id)->count()}} - {{$partido->goles->where('team_id', $partido->visita->id)->count()}} {{$partido->visita->name}}</h1>
    <p>Acá podes editar el partido seleccionado, cambiar la fecha, agregar goles y demás</p>
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
                  <h3 class="card-title">Editar Partido</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST">
                    @csrf
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6">
                          <div class="form-group">
                              <label for="tournament">Torneo</label>
                              <select name="tournament_id" id="tournament" class="selectWithoutSearch form-control" style="width: 100%;">
                              <option selected disabled>Elegir Torneo...</option>
                              @foreach ($torneos as $torneo)
                                  <option value="{{$torneo->id}}" {{$partido->tournament->id === $torneo->id ? 'selected'  : ''}}>{{$torneo->name}}</option>
                              @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="col-6">
                          <div class="form-group">
                              <label for="category">Categoría</label>
                              <select name="category_id" id="category" class="selectWithoutSearch form-control" style="width: 100%;" onchange="obtenerEquipos()">
                              <option selected disabled>Elegir categoría...</option>
                              @foreach ($categorias as $categoria)
                                  <option value="{{$categoria->id}}" {{$partido->category->id == $categoria->id ? 'selected'  : ''}}>{{$categoria->name}}</option>
                              @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="col-6">
                          <div class="form-group">
                              <label for="team_1">Local</label>
                              <select name="team_id_1" id="team_1" class="select2 form-control" style="width: 100%;">
                                  @foreach ($equipos as $equipo)
                                      <option value="{{$equipo->id}}" {{$partido->local->id == $equipo->id ? 'selected'  : ''}}>{{$equipo->name}}</option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="col-6">
                          <div class="form-group">
                              <label for="team_2">Visitante</label>
                              <select name="team_id_2" id="team_2" class="select2 form-control" style="width: 100%;">
                                @foreach ($equipos as $equipo)
                                <option value="{{$equipo->id}}" {{$partido->visita->id == $equipo->id ? 'selected'  : ''}}>{{$equipo->name}}</option>
                            @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="col-md-6 mb-0">
                        <div class="form-group">
                          <label for="fecha">Fecha</label>
                              <select name="fecha_id" id="fecha" class="select2 form-control" style="width: 100%;">
                                <option selected disabled>Elegir Fecha...</option>
                                <option value="null">Sin definir</option>
                                  @foreach ($fechas as $fecha)
                                    <option value="{{$fecha->id}}" {{$partido->fecha->id == $fecha->id ? 'selected'  : ''}}>{{$fecha->name}}</option> 
                                  @endforeach
                              </select>
                        </div>
                      </div>
                      <div class="col-md-3 mb-0">
                        <div class="form-group">
                          <label for="horario">Horario</label>
                          <input type="time" name="horario" class="form-control" value="{{$partido->horario}}">
                        </div>
                      </div>
                      <div class="col-md-3 mb-0">
                        <div class="form-group">
                          <label for="cancha">Cancha</label>
                          <select name="cancha" id="cancha" class="selectWithoutSearch form-control" style="width: 100%;">
                            <option selected disabled>Elegir Cancha...</option>
                            @for ($i = 1; $i < 6; $i++)
                            <option value="{{$i}}" {{$partido->cancha == $i ? 'selected'  : ''}}>{{$i}}</option>
                            @endfor
                        </select>
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
          <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Agregar Goles</b></h3>
                        </div>
                        <form method="POST" action="/admin/partido/{{$partido->id}}/agregar/gol">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tournament">Equipo:*</label>
                                            <select name="team_id" id="tournament_goal" class="selectWithoutSearch form-control" style="width: 100%;" onchange="getPlayers()" required>
                                                <option selected disabled>Elegir Equipo...</option>
                                                <option value="{{$partido->local->id}}">{{$partido->local->name}}</option>
                                                <option value="{{$partido->visita->id}}">{{$partido->visita->name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jugadores">Jugador:</label>
                                            <select name="player_id" id="jugadores" class="selectWithoutSearch form-control" style="width: 100%;">
                                                <option selected disabled>Elegir Jugador...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                            <input type="hidden" value={{$partido->id}} name="match_id" required>
                        </form>
                    </div>
              </div>
          </div>
          @if ($partido->goles->count())
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><b>Goles</b></h3>
                            <!-- /.card-tools -->
                            </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped projects">
                                <thead>
                                <tr>
                                    <th>
                                        #
                                    </th>
                                    <th>
                                        Equipo
                                    </th>
                                    <th>
                                        Jugador
                                    </th>
                                    <th>
                                        Acciones
                                    </th>
                                </tr>
                                </thead>      
                                <tbody id="bodyTable">
                                    @foreach ($partido->goles as $index => $gol)
                                    <tr>
                                        <td>
                                            # {{$index + 1}}
                                        </td>
                                        <td class="className">
                                            <b>{{$gol->team->name}}</b>
                                        </td>
                                        <td class="className">
                                            <b>{{isset($gol->player->first_name) ? $gol->player->first_name.' '.$gol->player->last_name : 'Sin definir'}}</b>
                                        </td>
                                        <td class="project-actions">
                                            <button class="btn btn-danger btn-sm" onclick="confirmacionGol({{$gol->id}})">
                                                <i class="fas fa-trash">
                                                </i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach 
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
            <form method="POST" id="deleteForm">
                @csrf
            </form>
          @endif
        </div>
    </div>
@stop

@section('js')
    <script type="text/javascript">
    function removeOptions(selectbox){
         var i;
         for(i = selectbox.options.length - 1 ; i >= 0 ; i--)
         {
             selectbox.remove(i);
         }
     }
    function getPlayers() {
      var sub = document.getElementById('jugadores');
      removeOptions(sub);
      var categoria = document.getElementById('tournament_goal');
      var elegido = categoria.options[categoria.selectedIndex].value;
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option = document.createElement('option');
            option.value = null;
            var textnode = document.createTextNode("Sin definir");
            option.appendChild(textnode);
            document.getElementById('jugadores').appendChild(option);
          var subs = JSON.parse(xmlhttp.responseText);
          subs.forEach(function(element){
            var option = document.createElement('option');
            option.value = element.id;
            var textnode = document.createTextNode(`${element.first_name} ${element.last_name}`);
            option.appendChild(textnode);
            document.getElementById('jugadores').appendChild(option);
          });
        };
      };
     xmlhttp.open("GET", '/api/equipo/'+ elegido + '/jugadores', true);
     xmlhttp.setRequestHeader("content-type", "application/json");
     xmlhttp.send();
    }

    $(document).ready(function() {
        $('.select2').select2();
        $('.selectWithoutSearch').select2({
          minimumResultsForSearch: Infinity
        });
        
    });
    function confirmacion(){
        var respuesta = confirm('¿Esta seguro de eliminar el equipo? No podrá recuperarlo');
        if (!respuesta) {
            window.event.preventDefault();
        }
    }

    function confirmacionGol(id){

            var respuesta = confirm('¿Esta seguro de eliminar el gol? No podrá recuperarlo');
            if (!respuesta) {
                window.event.preventDefault();
            } else {
              let form = document.getElementById('deleteForm');
              form.action = `/admin/gol/eliminar/${id}`; 
              form.submit();
            }
        }
    </script>
@stop