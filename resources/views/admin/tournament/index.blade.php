@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Torneos</h1>
    <p>Acá podes agregar o modificar los torneos existentes</p>
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
            <div class="col-md-6">
                <h2>Torneos creados</h2>
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Torneos</h3>
            
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
                      <table class="table table-striped projects">
                          <thead>
                              <tr>
                                  <th>
                                      #
                                  </th>
                                  <th>
                                      Nombre
                                  </th>
                                  <th>
                                        Acciones
                                  </th>
                              </tr>
                          </thead>
                          @php
                          $i = 1;
                          @endphp
                          <tbody>
                              @forelse ($torneos as $torneo)
                              <tr>
                                <td>
                                    # {{$i}}
                                </td>
                                <td>
                                    <a>
                                        {{$torneo->name}}
                                    </a>
                                </td>
                                <td class="project-actions">
                                    <a class="btn btn-info btn-sm" href="/admin/torneos/editar/{{$torneo->id}}">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="/torneos/eliminar/{{$torneo->id}}">
                                        <i class="fas fa-trash">
                                        </i>
                                    </a>
                                </td>
                            </tr>
                            @php
                            $i++;
                            @endphp
                              @empty
                                  <h2 class="text-center">No hay torneos creados por el momento</h2>
                              @endforelse

                          </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-md-6">
                <h2>Agregar Torneo</h2>
                <!-- general form elements -->
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Agregar Torneo</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form method="POST">
                      @csrf
                    <div class="card-body">
                      <div class="form-group mb-0">
                        <label for="nombreTorneo">Nombre del torneo</label>
                        <input type="text" class="form-control" id="nombreTorneo" name="name" placeholder="Ej: Torneo Apertura 2021" required>
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