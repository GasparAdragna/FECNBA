@extends('adminlte::page')

@section('plugins.Select2', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Partidos - FECNBA')

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
          <h1>Partidos:</h1>
          <p>Acá podes ver todos los partidos programados y agregarlos fácilmente</p>
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
            <div class="col-12">
              <div class="card card-success">
                <div class="card-header">
                  <h3 class="card-title">Agregar Partido</h3>
                </div>
                <form method="POST">
                    @csrf
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                          <div class="form-group">
                              <label for="tournament">Torneo</label>
                              <select name="tournament_id" id="tournament" class="selectWithoutSearch form-control" style="width: 100%;">
                              <option selected disabled>Elegir Torneo...</option>
                              @foreach ($torneos as $torneo)
                                  <option value="{{$torneo->id}}" {{App\Models\Tournament::active()->id == $torneo->id ? 'selected' : ''}}>{{$torneo->name}}</option>
                              @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-group">
                              <label for="category">Categoría</label>
                              <select name="category_id" id="category" class="selectWithoutSearch form-control" style="width: 100%;" onchange="obtenerEquipos()">
                              <option selected disabled>Elegir categoría...</option>
                              @foreach ($categorias as $categoria)
                                  <option value="{{$categoria->id}}">{{$categoria->name}}</option>
                              @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-group">
                              <label for="zone">Zona</label>
                              <select name="zone_id" id="zone" class="select2 form-control" style="width: 100%;">
                                  <option selected disabled>Elegir zona...</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-6">
                          <div class="form-group">
                              <label for="team_1">Local</label>
                              <select name="team_id_1" id="team_1" class="select2 form-control" style="width: 100%;">
                                  <option selected disabled>Elegir local...</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-6">
                          <div class="form-group">
                              <label for="team_2">Visitante</label>
                              <select name="team_id_2" id="team_2" class="select2 form-control" style="width: 100%;">
                                  <option selected disabled>Elegir visitante...</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-md-6 mb-0">
                        <div class="form-group">
                          <label for="fecha">Fecha</label>
                              <select name="fecha_id" id="fecha" class="select2 form-control" style="width: 100%;">
                                  <option selected disabled>Elegir Fecha...</option>
                                  @foreach ($fechas as $fecha)
                                    <option value="{{$fecha->id}}">{{$fecha->name}}</option> 
                                  @endforeach
                              </select>
                        </div>
                      </div>
                      <div class="col-md-3 mb-0">
                        <div class="form-group">
                          <label for="horario">Horario</label>
                          <input type="time" name="horario" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-3 mb-0">
                        <div class="form-group">
                          <label for="cancha">Cancha</label>
                          <select name="cancha" id="cancha" class="selectWithoutSearch form-control" style="width: 100%;">
                            <option selected disabled>Elegir Cancha...</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
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
              <label for="myInput">Buscar partido por nombre de equipo:</label>
            <input
            type="text"
            name="searchBar"
            class="form-control"
            placeholder="Buscar partido..."
            onkeyup="filterTable()"
            id="myInput"
            />
            <br>
            <p>Para que se refleje el partido en la tabla el mismo tiene que estar en estado: <b>Terminado</b></p>
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Partidos:</h3>
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
                                Torneo
                              </th>
                              <th>
                                Estado
                              </th>
                              <th>
                                Acciones
                              </th>
                          </tr>
                      </thead>
                      <tbody id="bodyTable">
                          @forelse ($partidos->sortBy('horario') as $index => $partido)
                            <tr>
                              <td>
                                  # {{$partido->id}}
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
                              <td>
                                {{$partido->tournament->name}}
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
                          @empty
                              <h2 class="text-center">No hay partidos creados por el momento</h2>
                          @endforelse
                      </tbody>
                  </table>
                  {{$partidos->links()}}
                </div>
            </div>
          </div>
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
    function obtenerEquipos() {
      obtenerZonas();
      var sub = document.getElementById('team_1');
      removeOptions(sub);
      var categoria = document.getElementById('category');
      var torneo = document.getElementById('tournament');
      torneo = torneo.options[torneo.selectedIndex].value;
      categoria = categoria.options[categoria.selectedIndex].value;
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          var subs = JSON.parse(xmlhttp.responseText);
          subs.forEach(function(element){
            var option = document.createElement('option');
            option.value = element.id;
            var textnode = document.createTextNode(element.name);
            option.appendChild(textnode);
            document.getElementById('team_1').appendChild(option);
          });
          subs.forEach(function(element){
            var option = document.createElement('option');
            option.value = element.id;
            var textnode = document.createTextNode(element.name);
            option.appendChild(textnode);
            document.getElementById('team_2').appendChild(option);
          });
          //refrescar();
        };
      };
     xmlhttp.open("GET", '/api/equipos/torneo/'+ torneo + '/categoria/'+ categoria, true);
     xmlhttp.setRequestHeader("content-type", "application/json");
     xmlhttp.send();
    }

    function obtenerZonas() {
      var sub = document.getElementById('zone');
      removeOptions(sub);
      var categoria = document.getElementById('category');
      var torneo = document.getElementById('tournament');
      torneo = torneo.options[torneo.selectedIndex].value;
      categoria = categoria.options[categoria.selectedIndex].value;
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          var subs = JSON.parse(xmlhttp.responseText);
          subs.forEach(function(element){
            var option = document.createElement('option');
            option.value = element.id;
            var textnode = document.createTextNode(element.name);
            option.appendChild(textnode);
            document.getElementById('zone').appendChild(option);
          });
          //refrescar();
        };
      };
     xmlhttp.open("GET", '/api/zonas/categoria/'+ categoria, true);
     xmlhttp.setRequestHeader("content-type", "application/json");
     xmlhttp.send();
    }

    function obtenerFechas() {
      var sub = document.getElementById('team_1');
      removeOptions(sub);
      var categoria = document.getElementById('category');
      var elegido = categoria.options[categoria.selectedIndex].value;
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          var subs = JSON.parse(xmlhttp.responseText);
          subs.forEach(function(element){
            var option = document.createElement('option');
            option.value = element.id;
            var textnode = document.createTextNode(element.name);
            option.appendChild(textnode);
            document.getElementById('team_1').appendChild(option);
          });
        };
      };
     xmlhttp.open("GET", '/api/fechas/torneo/'+ elegido, true);
     xmlhttp.setRequestHeader("content-type", "application/json");
     xmlhttp.send();
    }

    $(document).ready(function() {
        $('.select2').select2();
        $('.selectWithoutSearch').select2({
          minimumResultsForSearch: Infinity
        });
        $('[data-toggle="tooltip"]').tooltip()  
        
    });

    function confirmacion(id){
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esta acción",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, borrarlo'
            }).then((result) => {
            if (result.value === true) {
                window.location.href = "/admin/partido/eliminar/"+id;
            }
        })
    }

    function setCookie(cookieName, cookieValue, nDays) {
      var today = new Date();
      var expire = new Date();

      if (!nDays) nDays=1;

      expire.setTime(today.getTime() + 3600000*24*nDays);
      document.cookie = cookieName+"="+escape(cookieValue) + ";expires="+expire.toGMTString();
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