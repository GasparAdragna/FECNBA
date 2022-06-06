@extends('adminlte::page')

@section('title', 'Notificaciones - FECNBA')

@section('content_header')
    <h1>Notificaciones</h1>
    <p>Acá podes enviar notificaciones a los usuarios de la app</p>
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
                    <h3 class="card-title">Enviar notificación</h3>
                  </div>
                  <form method="POST">
                      @csrf
                    <div class="card-body">
                      <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                              <label for="title">Título</label>
                              <input type="text" class="form-control" id="title" name="title" value="{{old('title')}}" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                              <label for="body">Mensaje</label>
                              <input type="text" class="form-control" id="body" name="body" value="{{old('body')}}" required>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                  </form>
                </div>
              </div>
            <div class="col-12">
                <h2>Notificaciones enviadas</h2>
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Notificaciones</h3>
            
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
                                      Título
                                  </th>
                                  <th>
                                      Mensaje
                                  </th>
                                  <th>
                                      Enviado
                                  </th>
                              </tr>
                          </thead>
                          <tbody>
                              @forelse ($notifications as $notificacion)
                              <tr>
                                <td>
                                    {{$notificacion->title}}
                                </td>
                                <td>
                                    {{$notificacion->body}}
                                </td>
                                <td>
                                  {{$notificacion->created_at}}
                                </td>
                            </tr>
                              @empty
                                  <h2 class="text-center">No hay notificaciones creados por el momento</h2>
                              @endforelse
                          </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
@stop