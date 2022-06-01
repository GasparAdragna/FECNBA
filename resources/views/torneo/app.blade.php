<!doctype html>
<html lang="en">
  <head>
    @include('torneo.partials.head')
  </head>
  <body>
    <div class="row justify-content-center bg-app">
      <div class="col-4 text-center align-items-center">
        <h1>FECNBA</h1>
        <img src="{{asset('/storage/photos/escudo sin fondo.png')}}" class="img-fluid">
        <br>
        <a href="https://apps.apple.com/ar/app/fecnba/id1620974260" target="_blank">
          <img src="{{asset('/storage/photos/apple-en.png')}}" class="img-fluid mt-3">
        </a>
        <br>
        <a href="https://play.google.com/store/apps/details?id=com.maqui.FECNBA" target="_blank">
          <img src="{{asset('/storage/photos/google-en.png')}}" class="img-fluid mt-3">
        </a>
        <br>
        <h5 class="mt-5">fecnba.com.ar</h5>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  </body>
</html>