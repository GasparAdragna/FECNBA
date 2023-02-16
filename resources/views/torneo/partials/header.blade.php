<nav class="navbar navbar-expand-lg navbar-light bg-light-blue">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <a class="navbar-brand font-weight-bold" href="/">
            <img src="/assets/logo.png" alt="" width="50" height="30" class="d-inline-block align-text-top">
            FECNBA
          </a>
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/inscripciones">Inscripciones</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="/">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="/programacion">Programación</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Categorías
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                @foreach ($categorias as $categoria)
                <li class="nav-item">
                  <a class="dropdown-item" href="/categoria/{{$categoria->slug}}">{{$categoria->name}}</a>
                </li>
                @endforeach
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="/noticias">Noticias</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="/contacto">Contacto</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>