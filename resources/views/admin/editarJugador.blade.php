@extends('adminlte::page')

@section('title', 'Editar Equipo - FECNBA')

@section('content_header')
    <h1>Jugador: {{$jugador->first_name.' '.$jugador->last_name}}</h1>
    <p>Acá podes editar el jugador seleccionado</p>
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
          <div class="col-12 col-lg-6">
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Editar Jugador - {{$jugador->team->name}}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST" action="/admin/jugador/editar/{{$jugador->id}}">
                  @csrf
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="nombre">Nombre*</label>
                        <input type="text" class="form-control" id="nombre" name="first_name" value="{{$jugador->first_name}}" placeholder="Ej: Carlos" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="apellido">Apellido*</label>
                        <input type="text" class="form-control" id="apellido" name="last_name" value="{{ $jugador->last_name }}" placeholder="Ej: Tevez" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="date">Fecha de nacimiento</label>
                        <input type="date" id="date" class="form-control" name="birthday" value="{{ $jugador->birthday }}" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="dni">DNI</label>
                        <input type="number" id="dni" class="form-control" required name="dni" value="{{$jugador->dni }}" placeholder="Ej: 40999999">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="os">Obra Social</label>
                        <input type="text" id="os" name="os" class="form-control" value="{{ $jugador->os }}" placeholder="Ej: OSDE">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="egreso">Año de Egreso</label>
                        <input type="number" id="egreso" name="year" class="form-control" min="1950" value="{{ $jugador->year }}" placeholder="Ej: 2015">
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-group">
                        <label for="mail">Email</label>
                        <input type="email" id="mail" name="email" class="form-control" value="{{ $jugador->email }}" placeholder="Ej: carlostevez@gmail.com">
                      </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-0">
                          <label for="team">Equipo</label>
                          <select name="team_id" id="team" class="form-control select2" style="width: 100%;">
                            @foreach ($equipos as $equipo)
                              <option value="{{$equipo->id}}" {{($jugador->team_id == $equipo->id) ? "selected" : ""}}>{{$equipo->name}}</option>
                            @endforeach
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
    </div>
@stop

@section('js')
    <script type="text/javascript">
        function confirmacion(){
            var respuesta = confirm('¿Esta seguro de eliminar el equipo? No podrá recuperarlo');
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
@stop