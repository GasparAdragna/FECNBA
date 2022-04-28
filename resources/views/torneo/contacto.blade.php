@extends('torneo.base')

@section('head')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('title')
    <title>FECNBA - Contacto</title>
@endsection

@section('main')
    <h3>Contactate con nosotros</h3>
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
    <form method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-6">
                <label for="first_name" class="form-label">Nombre</label>
                <input name="first_name" type="text" class="form-control" id="first_name" aria-describedby="first_name" required>
            </div>
            <div class="col-6">
                <label for="last_name" class="form-label">Apellido</label>
                <input name="last_name" type="text" class="form-control" id="last_name" aria-describedby="last_name" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="mail" class="form-label">Mail</label>
            <input name="email" type="text" class="form-control" id="mail" required>
          </div>
          <div class="mb-3">
              <label for="message" class="form-label">Mensaje</label>
              <textarea name="message" id="message" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <div class="g-recaptcha" data-sitekey="6LfFZYgcAAAAAG7Ir5I207VaSVI3aHzCLrqcQzYo"></div>
          </div>
          <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
@endsection