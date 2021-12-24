@extends('adminlte::page')

@section('title', 'Contacto')

@section('content_header')
    <h1>Contacto</h1>
    <p>Ver contacto de: {{$contact->first_name}} {{$contact->last_name}}</p>
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
            <h1>Hola</h1>
        </div>
    </div>
@stop

@section('js')
  <script>
    function confirmacion(){
      var respuesta = confirm('¿Esta seguro de eliminar la categoría? Se eliminaran TODOS los equipos pertenecientes a esta categoria y no podra recuperarlos.');
      if (!respuesta) {
          window.event.preventDefault();
      }
    }
  </script>
@endsection