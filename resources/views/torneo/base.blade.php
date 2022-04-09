<!doctype html>
<html lang="en">
    @include('torneo.partials.head')
    @yield('head')
  <body>
      @include('torneo.partials.header')

      @include('torneo.partials.banner')
      <div class="container mt-5">
        <div class="row mb-3">
            <div class="col-lg-9 order-2 order-lg-1">
              @yield('main')
            </div>
            <div class="col-lg-3 order-1 order-lg-2 overflow-auto">
                <h3 class="bg-light-blue p-2">Actividad del campo:</h3>
                <div class="{{$estado->color}} info-box text-center">
                    <span class="info-box-icon"><i class="{{$estado->icon}}"></i></span>
                    <div class="info-box-content pb-0 p-3">
                      <h4>{{$estado->state}}</h4>
                      <p>{{$estado->text}}</p>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                <h3 class="bg-light-blue p-2">Próximos partidos:</h3>
                <div class="row">
                    <div class="col-12">
                        <hr>
                        @if ($fecha)
                          @forelse ($fecha->matches->where('finished', 0) as $partido)
                            <div class="text-center">
                                <h4>{{$partido->local->name}} vs {{$partido->visita->name}}</h4>
                                <div class="text-center">
                                    <p class="text-muted">{{$partido->fecha->name}} - {{$partido->horario}} - Cancha {{$partido->cancha}}</p>
                                </div>
                            </div>
                          <hr>
                          @empty
                            <div class="text-center">
                                <h4>No hay partidos programados</h4>
                            </div>
                          @endforelse
                        @else
                            <div class="text-center">
                                <h4>No hay partidos programados</h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
      </div>

      @include('torneo.partials.footer')
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
      <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
      <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

      @yield('js')
  </body>
</html>