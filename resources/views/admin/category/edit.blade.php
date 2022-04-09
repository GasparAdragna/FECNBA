@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Categoría:</h1>
    <p>Acá podes editar la categoría seleccionada</p>
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
                <!-- general form elements -->
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Editar Categoría</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form method="POST">
                      @csrf
                    <div class="card-body">
                      <div class="form-group">
                        <label for="nombreCategoria">Nombre de la categoría</label>
                        <input type="text" class="form-control" id="nombreCategoria" value="{{$category->name}}" name="name" placeholder="Ej: Menores D" required>
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