@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Sancionados - FECNBA')

@section('content_header')
  <div class="row">
      <div class="col-12">
          <h1>Editar Sanción</h1>
          <p>Acá podes editar la sanción</p>
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
          <!-- general form elements -->
          <div class="card card-success">
            <div class="card-header">
              <h3 class="card-title">Editar sancionado</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST">
                @csrf
              <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="name" value="{{$sancionado->name}}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                          <label for="motivo">Motivo</label>
                          <input type="text" class="form-control" id="motivo" name="motive" value="{{$sancionado->motive}}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="tournament">Torneo</label>
                            <select name="tournament_id" id="tournament" class="selectWithoutSearch form-control" style="width: 100%;" required>
                                <option selected disabled>Elegir Torneo...</option>
                                @foreach ($torneos as $torneo)
                                    <option value="{{$torneo->id}}" {{$sancionado->tournament_id === $torneo->id ? 'selected'  : ''}}>{{$torneo->name}}</option>
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
                                    <option value="{{$categoria->id}}" {{$sancionado->category_id == $categoria->id ? 'selected'  : ''}}>{{$categoria->name}}</option>
                                @endforeach
                          </select>
                      </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="team">Equipo</label>
                            <select name="team_id" id="team" class="select2 form-control" style="width: 100%;" required>
                                @foreach ($equipos as $equipo)
                                    <option value="{{$equipo->id}}" {{$sancionado->team_id == $equipo->id ? 'selected'  : ''}}>{{$equipo->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="fecha">Fecha</label>
                              <select name="fecha_id" id="fecha" class="select2 form-control" style="width: 100%;" required>
                                  @foreach ($fechas as $fecha)
                                    <option value="{{$fecha->id}}" {{$sancionado->fecha_id == $fecha->id ? 'selected'  : ''}}>{{$fecha->name}}</option> 
                                  @endforeach
                              </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="sancion">Sanción</label>
                            <input type="text" class="form-control" id="sancion" name="sanction" value="{{$sancionado->sanction}}" required>
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

        $(document).ready(function() {
            $('.select2').select2();
            $('.selectWithoutSearch').select2({
            minimumResultsForSearch: Infinity
            });
            $('[data-toggle="tooltip"]').tooltip()  
            
        });

        function confirmacion(){
            var respuesta = confirm('¿Esta seguro de eliminar la sanción? No podrá recuperarla');
            if (!respuesta) {
                window.event.preventDefault();
            }
        }
        function obtenerEquipos() {
            var sub = document.getElementById('team');
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
                        document.getElementById('team').appendChild(option);
                    });
                };
            };
            xmlhttp.open("GET", '/api/equipos/torneo/'+ torneo + '/categoria/'+ categoria, true);
            xmlhttp.setRequestHeader("content-type", "application/json");
            xmlhttp.send();
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