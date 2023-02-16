@extends('torneo.base')

@section('head')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('title')
    <title>FECNBA - Inscripciones</title>
@endsection

@section('main')
    <h3>Inscripciones torneo 2023</h3>
    <br>
    <div class="row">
        <div class="col-12">
            @if (session('status'))
            <div class="alert alert-success">
                <h5>{{ session('status') }}</h5>
            </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    <h5>{{ session('error') }}</h5>
                </div>
            @endif
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    <form method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <h4>Descargar planilla</h4>
          <div class="col-12">
            <small class="mb-3">Descargar la plantilla para inscribir a tu equipo al torneo 2023</small>
            <br>
            <small class="mb-3">Una vez completada, cargar la misma en el formulario de aqui abajo</small>
            <br>
            <br>
            <a href="/inscripciones/planilla" class="btn btn-success">Descargar <i class="fa fa-download"></i></a>
          </div>
        </div>
        <hr>
        <div class="row mb-3">
          <h4>Cargar planilla</h4>
          <br>
          <br>
          <h5>Equipo</h5>
          <div class="row">
            <div class="col-12">
              <label for="team_id" class="form-label">Elegir equipo existente:</label>
              <select name="team_id" class="form-select">
                <option selected disabled>Elegir equipo...</option>
                @foreach ($equipos as $equipo)
                  <option value="{{$equipo->id}}">{{$equipo->name}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12">
              <label for="team_name" class="form-label">O crear nuevo equipo</label>
              <input name="name" type="text" class="form-control" placeholder="Nombre de nuevo equipo" id="team_name" value="{{old('name')}}">
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12">
              <label for="planilla" class="form-label">Seleccionar archivo</label>
            </div>
            <div class="col-12">
            <input type="file" class="form-control-file" name="planilla" id="planilla" accept=".xlsx, .xls, .csv" required>
            </div>
          </div>
        </div>
        <div class="mb-3">
            <div class="g-recaptcha" data-sitekey="6LfFZYgcAAAAAG7Ir5I207VaSVI3aHzCLrqcQzYo"></div>
        </div>
        <button type="submit" class="btn btn-primary">Enviar inscripción</button>        
    </form>
@endsection