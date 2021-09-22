@extends('torneo.base')

@section('title')
    <title>FECNBA - Contacto</title>
@endsection

@section('main')
    <h3>Contactate con nosotros</h3>
    <form action="" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-6">
                <label for="first_name" class="form-label">Nombre</label>
                <input type="first_name" class="form-control" id="first_name" aria-describedby="first_name" required>
            </div>
            <div class="col-6">
                <label for="last_name" class="form-label">Apellido</label>
                <input type="last_name" class="form-control" id="last_name" aria-describedby="last_name" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="mail" class="form-label">Mail</label>
            <input type="email" class="form-control" id="mail" required>
          </div>
          <div class="mb-3">
              <label for="message" class="form-label">Mensaje</label>
              <textarea name="message" id="message" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
@endsection