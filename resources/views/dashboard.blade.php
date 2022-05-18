@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
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
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{$torneos}}</h3>

          <p>Torneos</p>
        </div>
        <div class="icon">
          <i class="ion ion-trophy"></i>
        </div>
        <a href="/admin/torneos" class="small-box-footer">Ir a editar <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3>{{$categorias}}</h3>

          <p>Categorías</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="/admin/categorias" class="small-box-footer">Ir a editar <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{$partidos}}</h3>

          <p>Partidos</p>
        </div>
        <div class="icon">
          <i class="ion ion-ios-football"></i>
        </div>
        <a href="/admin/partidos" class="small-box-footer">Ir a editar <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-danger">
        <div class="inner">
          <h3>{{$equipos}}</h3>

          <p>Equipos</p>
        </div>
        <div class="icon">
          <i class="ion ion-android-people"></i>
        </div>
        <a href="/admin/equipos" class="small-box-footer">Ir a editar <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
</div>
<div class="row">
  <div class="col-12">
    <h2>Proxima Fecha:</h2>
    @isset($fecha)
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
                    Torneo
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
                @forelse ($fecha->matches as $partido)
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
                  <td>
                    {{$partido->tournament->name}}
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
    @else
    <p>No hay una fecha programada</p>
    @endisset
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <h2>Noticia:</h2>
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Agregar Noticia:</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form method="POST" action="/admin/noticia/agregar" enctype="multipart/form-data">
          @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label for="titulo">Titulo:</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="resumen">Resumen:</label>
                <span class="text-muted">(esto es lo que se va a ver antes de entrar a la noticia)</span>
                <textarea name="resumen" id="resumen" class="form-control" cols="30" rows="4">{{ old('resumen') }}</textarea>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="text">Texto:</label>
                <textarea name="texto" id="text" class="form-control" cols="30" rows="10">{{ old('texto') }}</textarea>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="state">Estado:</label>
                <select name="estado" id="state" class="select2 form-control" style="width: 100%;">
                  <option selected disabled>Elija un estado...</option>
                  <option value="Importante">Importante</option>
                  <option value="Información">Información</option>
                  <option value="Programación">Programación</option>
                  <option value="Cancelación">Cancelación</option>
                </select>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group mb-0">
                <label for="state">Foto (opcional):</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="customFile" name="photo">
                  <label class="custom-file-label" for="customFile">Buscar archivo</label>
                </div>
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
  <div class="col-md-6">
    <h2>Estado del campo:</h2>
    <div class="{{$estado->color}} info-box text-center">
      <span class="info-box-icon"><i class="{{$estado->icon}}"></i></span>
      <div class="info-box-content pb-0 p-3">
        <h3>{{$estado->state}}</h3>
        <p>{{$estado->text}}</p>
        <a href="editar/estado" class="footer-actividad">Ir a editar <i class="fas fa-arrow-circle-right"></i></a>
      </div>
      <!-- /.info-box-content -->
    </div>
  </div>
</div>
@stop

@section('css')
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/css/admin.css">
@stop

@section('js')
<script src="/js/nicEdit.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(new nicEditor().panelInstance('text'));</script>
<script>
  document.querySelector('.custom-file-input').addEventListener('change',function(e){
      var fileName = document.getElementById("customFile").files[0].name;
      var nextSibling = e.target.nextElementSibling
      nextSibling.innerText = fileName
    })
  </script>
@stop