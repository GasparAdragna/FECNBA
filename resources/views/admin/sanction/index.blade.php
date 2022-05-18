@extends('adminlte::page')

@section('plugins.Select2', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Sancionados - FECNBA')

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
            <h1>Sancionados</h1>
            <p>Acá podes agregar o modificar los sancionados</p>
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
          <div class="card card-danger">
            <div class="card-header">
              <h3 class="card-title">Agregar sancionado</h3>
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
                            <input type="text" class="form-control" id="nombre" name="name" value="{{old('name')}}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                        <label for="motivo">Motivo</label>
                        <input type="text" class="form-control" id="motivo" name="motive" value="{{old('motive')}}" required>
                        </div>
                    </div>
                    <div class="col-6">
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
                    <div class="col-6">
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
                    <div class="col-6">
                        <div class="form-group">
                            <label for="team">Equipo</label>
                            <select name="team_id" id="team" class="select2 form-control" style="width: 100%;" required>
                                <option selected disabled>Elegir equipo...</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                    <div class="col-6">
                        <div class="form-group">
                            <label for="sancion">Sanción</label>
                            <input type="text" class="form-control" id="sancion" name="sanction" value="{{old('sanction')}}" required>
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
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Sancionados</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped projects">
                            <thead>
                                <tr>
                                    <th>
                                        #ID
                                    </th>
                                    <th>
                                        Nombre
                                    </th>
                                    <th>
                                        Motivo
                                    </th>
                                    <th>
                                        Categoría
                                    </th>
                                    <th>
                                        Equipo
                                    </th>
                                    <th>
                                        Fecha
                                    </th>
                                    <th>
                                        Sanción
                                    </th>
                                    <th>
                                        Estado
                                    </th>
                                    <th>
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sancionados as $sancionado)
                                <tr>
                                    <td>
                                        # {{$sancionado->id}}
                                    </td>
                                    <td>
                                        <a>
                                            {{$sancionado->name}}
                                        </a>
                                    </td>
                                    <td>
                                        <a>
                                            {{$sancionado->motive}}
                                        </a>
                                    </td>
                                    <td>
                                        <a>
                                            {{$sancionado->category->name}}
                                        </a>
                                    </td>
                                    <td>
                                        <a>
                                            {{$sancionado->team->name}}
                                        </a>
                                    </td>
                                    <td>
                                        <a>
                                            {{$sancionado->fecha->name}}
                                        </a>
                                    </td>
                                    <td>
                                        <a>
                                            {{$sancionado->sanction}}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($sancionado->active)
                                            <a>Activo</a>
                                        @else
                                            <a><b>Terminado</b></a>
                                        @endif
                                    </td>
                                    <td class="project-actions">
                                        <a class="btn btn-info btn-sm" href="/admin/sancionados/editar/{{$sancionado->id}}">
                                            <i class="fas fa-pencil-alt">
                                            </i>
                                        </a>
                                        <button class="btn btn-danger btn-sm" onclick="confirmacion({{$sancionado->id}})">
                                            <i class="fas fa-trash">
                                            </i>
                                        </button>
                                        <a class="btn {{$sancionado->active ? 'btn-primary' : 'btn-warning'}} btn-sm" href="/admin/sancionados/{{$sancionado->id}}/terminar" data-toggle="tooltip" data-placement="top" title="{{$sancionado->active ? 'Terminar Sanción' : 'Activar Sanción'}}">
                                            @if (!$sancionado->active)
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
                                        <h2 class="text-center">No hay sancionados creados por el momento</h2>
                                @endforelse
                            </tbody>
                        </table>
                        {{$sancionados->links()}}
                    </div>
                </div>
                <!-- /.card-body -->
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
                    window.location.href = "/admin/sancionados/eliminar/"+id;
                }
            })
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