@extends('adminlte::page')

@section('title', 'Estado del Campo - FECNBA')

@section('content_header')
    <h1>Estado del campo:</h1>
    <p>Acá podes editar el estado del campo para que se vea desde la página de inicio</p>
@stop

@section('content')
<style>
    .form-check-inline{
        font-size: 3em!important;
    }
</style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
                @if (session('status'))
                  <div class="callout callout-success">
                    <h5>{{ session('status') }}</h5>
                  </div>
                @endif
                @if (session('warning'))
                  <div class="callout callout-warning">
                    <h5>{{ session('warning') }}</h5>
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
            <div class="col-md-6">
                <div class="row">
                    <div class="col-12">
                        <h3 class="bg-light-blue p-2">Actividad del campo:</h3>
                        <div class="{{$estado->color}} info-box text-center">
                          <span class="info-box-icon"><i class="{{$estado->icon}}"></i></span>
                          <div class="info-box-content pb-0 p-3">
                            <h4>{{$estado->state}}</h4>
                            <p>{{$estado->text}}</p>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                              <h3 class="card-title">Cambiar Estado:</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="POST" action="/admin/cambiar/estado">
                                @csrf
                              <div class="card-body">
                                <div class="form-group">
                                  <label for="state">Estado:</label>
                                  <select name="id" id="state" class="select2 form-control" style="width: 100%;" required onchange="obtenerTexto()">
                                    <option disabled selected>Eliga un estado...</option>  
                                    @foreach ($estados as $estado)
                                        <option value="{{$estado->id}}">{{$estado->state}}</option>
                                    @endforeach
                                  </select>
                                </div>
                                <div class="row">
                                  <div class="col-12">
                                      <div class="d-none" id="state_card">
                                          <span class="info-box-icon"><i class="" id="icon"></i></span>
                                          <div class="info-box-content pb-0 p-3">
                                            <h3 id="title">Actividad normal</h3>
                                            <p id="texto">Todas las actividades se desarrollan normalmente</p>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-12">
                                    <div class="form-group mb-0">
                                      <label for="notification">Notificar usuarios:</label>
                                      <br>
                                      <div class="form-check">
                                        <input type="hidden" name="notification" value="0"/>
                                        <input class="form-check-input" type="checkbox" name="notification" id="notification" value="1" checked>
                                        <label class="form-check-label" for="notification">
                                          Notificar
                                        </label>
                                        <br>
                                        <small>Si esta opción está tildada, todos los usuarios de la app recibirán una notificación en su celular.</small>
                                      </div>
                                    </div>
                                </div>
                                </div>
                              </div>
                              <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Cambiar</button>
                              </div>
                            </form>
                          </div>
                    </div>
                </div>
              </div>
              <div class="col-6">
                  <div class="row">
                      <div class="col-12">
                        <div class="card card-success">
                            <div class="card-header">
                              <h3 class="card-title">Agregar Estado:</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="POST" action="/admin/agregar/estado">
                                @csrf
                              <div class="card-body">
                                <div class="form-group">
                                  <label for="state">Estado:</label>
                                  <input name="state"  type="text" class="form-control" required>
                                </div>
                                <div class="form-group">
                                  <label for="texto">Texto por defecto:</label>
                                  <textarea name="text" class="form-control" rows="2" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="texto">Color de fondo:</label>
                                    <select name="color" class="form-control" required>
                                        <option value="bg-primary" class="bg-primary">Azul</option>
                                        <option value="bg-success" class="bg-success">Verde</option>
                                        <option value="bg-danger" class="bg-danger">Rojo</option>
                                        <option value="bg-warning" class="bg-warning">Amarillo</option>
                                        <option value="bg-info" class="bg-info">Celeste</option>
                                        <option value="bg-secondary" class="bg-secondary">Gris</option>
                                        <option value="bg-light" class="bg-light">Gris Claro</option>
                                        <option value="bg-dark" class="bg-dark">Negro</option>
                                    </select>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="icon" id="icon1" value="fa fa-sun" checked>
                                    <label class="form-check-label" for="icon1"><i class="fa fa-sun"></i></label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="icon" id="icon3" value="fa fa-cloud-sun">
                                    <label class="form-check-label" for="icon3"><i class="fa fa-cloud-sun"></i></label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="icon" id="icon2" value="fa fa-cloud">
                                    <label class="form-check-label" for="icon2"><i class="fa fa-cloud"></i></label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="icon" id="icon4" value="fa fa-cloud-showers-heavy">
                                    <label class="form-check-label" for="icon4"><i class="fa fa-cloud-showers-heavy"></i></label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="icon" id="icon5" value="fa fa-tint">
                                    <label class="form-check-label" for="icon5"><i class="fa fa-tint"></i></label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="icon" id="icon6" value="fa fa-ban">
                                    <label class="form-check-label" for="icon6"><i class="fa fa-ban"></i></label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="icon" id="icon7" value="fa fa-futbol">
                                    <label class="form-check-label" for="icon7"><i class="fa fa-futbol"></i></label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="icon" id="icon8" value="fa fa-clock">
                                    <label class="form-check-label" for="icon8"><i class="fa fa-clock"></i></label>
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
        </div>
    </div>
@stop


    

@section('js')
    <script>
    function obtenerTexto() {
      var text = document.getElementById('texto');
      var title = document.getElementById('title');
      var icon = document.getElementById('icon');
      var card = document.getElementById('state_card');
      var categoria = document.getElementById('state');
      console.log(categoria);
      var elegido = categoria.options[categoria.selectedIndex].value;
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          var state = JSON.parse(xmlhttp.responseText);
          text.innerHTML = state.text;
          title.innerHTML = state.state;
          icon.classList += state.icon;
          card.classList = 'info-box text-center ' + state.color;
        };
      };
     xmlhttp.open("GET", '/api/estado/'+ elegido, true);
     xmlhttp.setRequestHeader("content-type", "application/json");
     xmlhttp.send();
    }
    </script>
@endsection
