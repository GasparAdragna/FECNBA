<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
Use App\Models\Tournament;
Use App\Models\Category;
Use App\Models\Team;
Use App\Models\Player;
Use App\Models\Match;
Use App\Models\Fecha;
Use App\Models\Goal;
Use App\Models\Noticia;
Use App\Models\State;

use Auth;


class AdminController extends Controller
{
    public function dashboard()
    {
        $categorias = Category::all()->count();
        $equipos = Team::all()->count();
        $partidos = Match::all()->count();
        $torneos = Tournament::all()->count();
        $estado = State::where('active', true)->first();
        $fecha = Fecha::latest('dia')->first();

        return view('dashboard', ['categorias' => $categorias, 'equipos' => $equipos, 'partidos' => $partidos,'torneos' => $torneos, "fecha" => $fecha, "estado" => $estado]);
    }
    //-----------------------TORNEOS-------------------
    public function torneos()
    {
        $torneos = Tournament::all();
        return view('/admin/torneos', ['torneos' => $torneos]);
    }
    public function agregarTorneo(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:tournaments|max:255',
        ]);

        $torneo = new Tournament;
        $torneo->name = $request->name;
        $torneo->save();
        return redirect()->back()->with('status', 'Se agregó correctamente el torneo');

    }

    // -------------------------CATEGORIAS------------------
    public function categorias()
    {
        $categorias = Category::all();
        return view('/admin/categorias', ['categorias' => $categorias]);
    }
    public function agregarCategoria(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:categories|max:255',
        ]);

        $categoria = new Category;
        $categoria->name = $request->name;
        $categoria->save();
        return redirect()->back()->with('status', 'Se agregó correctamente la categoría');

    }
    public function vistaEditarCategoria($id)
    {
        $categoria = Category::find($id);
        return view('/admin/editarCategoria', ['categoria' => $categoria]);
    }
    public function editarCategoria(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:categories|max:255',
        ]);

        $categoria = Category::find($request->id);
        $categoria->name = $request->name;
        $categoria->save();
        return redirect('/admin/categorias')->with('status', 'Se editó correctamente la categoría');
    }
    public function eliminarCategoria($id){
        $categoria = Category::find($id);
        $categoria->delete();
        return redirect('/admin/categorias')->with('status', 'Se eliminó correctamente la categoría');
    }
    // -------------------------EQUIPOS------------------
    public function equipos()
    {
        $equipos = Team::all();
        $categorias = Category::all();
        return view('/admin/equipos', ['equipos' => $equipos, 'categorias' => $categorias]);
    }
    public function agregarEquipo(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:teams|max:255',
        ]);

        $equipo = new Team;
        $equipo->name = $request->name;
        $equipo->category_id = $request->category_id;
        $equipo->save();
        return redirect()->back()->with('status', 'Se agregó correctamente el equipo');
    }
    public function vistaEditarEquipo($id)
    {
        $equipo = Team::find($id);
        $categorias = Category::all();
        return view('/admin/editarEquipo', ['equipo' => $equipo, 'categorias' => $categorias]);
    }
    public function verEquipo($id)
    {
        $equipo = Team::find($id);
        return view('/admin/equipo', ['equipo' => $equipo]);
    }
    public function editarEquipo(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:teams|max:255',
        ]);

        $equipo = Team::find($request->id);
        $equipo->name = $request->name;
        $equipo->save();
        return redirect('/admin/equipos')->with('status', 'Se editó correctamente el equipo');
    }
    public function editarCategoriaEquipo(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|numeric',
            'id' => 'required|numeric',
        ]);
        $equipo = Team::find($request->id);
        $equipo->category_id = $request->category_id;
        $equipo->save();
        return redirect()->back()->with('status', 'Se editó correctamente el equipo');
    }
    public function eliminarEquipo($id)
    {
        $team = Team::find($id);
        foreach ($team->players as $player) {
            $player->delete();
        }
        foreach ($team->matches as $match) {
            foreach ($match->goles as $goal) {
                $goal->delete();
            }
            $match->delete();
        }
        $team->delete();
        return redirect()->back()->with('status', 'Se eliminó correctamente el equipo');
    }
    // -------------------------JUGADORES------------------
    public function jugadores()
    {
        $jugadores = Player::all();
        $equipos = Team::all();
        return view('admin/jugadores', ['jugadores' => $jugadores, 'equipos' => $equipos]);
    }
    public function agregarJugador(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|max:255|string',
            'last_name' => 'required|max:255|string',
            'dni' => 'numeric|unique:players|required',
            'os' => 'nullable|string',
            'birthday' => 'nullable|date',
            'year' => 'numeric|nullable',
            'email' => 'string|nullable',
            'team_id' => 'numeric|required',
        ]);
        $player = new Player;
        $player->first_name = $request->first_name;
        $player->last_name = $request->last_name;
        $player->dni = $request->dni;
        $player->os = $request->os;
        $player->birthday = $request->birthday;
        $player->year = $request->year;
        $player->email = $request->email;
        $player->team_id = $request->team_id;
        $player->save();
        return redirect()->back()->with('status', 'Se agregó correctamente el jugador');
    }
    public function vistaEditarJugador($id)
    {
        $jugador = Player::find($id);
        $equipos = Team::all();
        return view('admin/editarJugador', ['jugador' => $jugador, 'equipos' => $equipos]);
    }
    public function editarJugador(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|max:255|string',
            'last_name' => 'required|max:255|string',
            'dni' => 'numeric|required',
            'os' => 'nullable|string',
            'birthday' => 'nullable|date',
            'year' => 'numeric|nullable',
            'email' => 'string|nullable',
            'team_id' => 'numeric|required',
        ]);
        $player = Player::find($id);
        $player->first_name = $request->first_name;
        $player->last_name = $request->last_name;
        $player->dni = $request->dni;
        $player->os = $request->os;
        $player->birthday = $request->birthday;
        $player->year = $request->year;
        $player->email = $request->email;
        $player->team_id = $request->team_id;
        $player->save();
        return redirect()->back()->with('status', 'Se editó correctamente el jugador');
    }
    public function eliminarJugador($id)
    {
        $player = Player::find($id);
        $player->delete();
        return redirect()->back()->with('status', 'Se eliminó correctamente el jugador');
    }
    // -------------------------PARTIDOS------------------
    public function partidos()
    {
        $partidos = Match::all();
        $equipos = Team::all();
        $torneos = Tournament::all();
        $categorias = Category::all();
        $fechas = Fecha::all();
        return view('admin.partidos', ['partidos' => $partidos, 'equipos' => $equipos, 'torneos' => $torneos, 'categorias' => $categorias, 'fechas' => $fechas]);
    }
    public function agregarPartido(Request $request)
    {
        $validate = $request->validate([
            'fecha_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'team_id_1' => 'numeric|required',
            'team_id_2' => 'numeric|required',
            'horario' => 'required',
            'cancha' => 'numeric|required',
        ]);
        Match::create($request->all());
        return redirect()->back()->with('status', 'Se agregó correctamente el partido');
    }
    public function vistaEditarPartido($id)
    {   
        $torneos = Tournament::all();
        $partido = Match::find($id);
        $categorias = Category::all();
        $fechas = Fecha::all();
        $equipos = Team::where('category_id', $partido->category->id)->get();
        return view('admin.editarPartido', ['partido' => $partido, 'torneos' => $torneos, 'categorias' => $categorias, 'fechas' => $fechas, 'equipos' => $equipos]);
    }
    public function editarPartdio(Match $partido)
    {
        $validate = $request->validate([
            'fecha_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'team_id_1' => 'numeric|required',
            'team_id_2' => 'numeric|required',
            'horario' => 'required',
            'cancha' => 'numeric|required',
        ]);
        $partido->update($request);
        return redirect('/admin/partidos')->with('status', 'Se editó correctamente el partido');
    }
    public function agregarGol($id, Request $request)
    {
        $validate = $request->validate([
            'team_id' => 'required|numeric'
        ]);
        $gol = new Goal;
        $gol->team_id = $request->team_id;
        if($request->player_id){
            $gol->player_id = $request->player_id;
        }
        $gol->match_id = $id;
        $gol->save();
        return redirect()->back()->with('status', 'Se agergó correctamente el gol al partido');

    }
    // -------------------------FECHAS------------------
    public function fechas(Tournament $torneo)
    {
        $torneos = Tournament::all();
        return view('admin.fechas', ['fechas' => $torneo->fechas, 'torneos' => $torneos, 'torneo' => $torneo]);
    }
    public function agregarFecha(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:fechas|max:255',
            'tournament_id' => 'required',
        ]);
        Fecha::create($request->all());
        return redirect()->back()->with('status', 'Se agregó correctamente la fecha');
    }
    public function vistaEditarFecha($id)
    {
        $fecha = Fecha::find($id);
        $torneos = Tournament::all();
        return view('admin.editarFecha', ['fecha' => $fecha, 'torneos' => $torneos]);
    }
    public function editarFecha($id, Request $request)
    {
        $fecha = Fecha::find($id);
        $fecha->update($request->all());
        return redirect('/admin/fechas/torneo/'.$fecha->tournament_id)->with('status', 'Se editó correctamente la fecha');
    }
    // -------------------------NOTICIAS------------------
    public function noticias()
    {
        $noticias = Noticia::all();
        return view('admin.noticias', ['noticias' => $noticias]);
    }
    public function agregarNoticia(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|unique:noticias|max:255|string',
            'texto' => 'required|string',
            'estado' => 'required|max:255|string',
            'photo' => 'image',
        ]);

        $noticia = new Noticia;
        if($request->file('photo')){
            $noticia->photo = $request->file('photo')->store('public/photos');
        }
        $noticia->titulo = $request->titulo;
        $noticia->texto = $request->texto;
        $noticia->estado = $request->estado;
        $noticia->user_id = Auth::id();
        $noticia->save();
        return redirect()->back()->with('status', 'Se agregó correctamente la noticia');
    }
    public function vistaEditarNoticia($id)
    {
        $noticia = Noticia::find($id);
        return view('admin.editarNoticia',compact('noticia'));
    }
    public function editarNoticia(Request $request, $id)
    {
        $validated = $request->validate([
            'titulo' => 'required|max:255|string',
            'texto' => 'required|string',
            'estado' => 'required|max:255|string',
            'photo' => 'image',
        ]);
        $noticia = Noticia::find($id);
        $noticia->titulo = $request->titulo;
        $noticia->texto = $request->texto;
        $noticia->estado = $request->estado;
        $noticia->user_id = Auth::id();
        if($request->file('photo')){
            Storage::delete($noticia->photo);
            $noticia->photo = $request->file('photo')->store('public/photos');
        }
        $noticia->save();
        return redirect('/admin/noticias')->with('status', 'Se editó correctamente la noticia');

    }
    // -------------------------ESTADO------------------
    public function vistaEditarEstado()
    {
        $estados = State::all();
        $estado = State::where('active', true)->get();
        return view('admin.editarEstado', compact('estados', 'estado'));
    }
    public function cambiarEstado(Request $request)
    {
        $estado = State::where('active', true)->first();
        $estado->active = false;
        $estado->save();

        $estado = State::find($request->id);
        $estado->active = true;
        $estado->save();
        return redirect()->back()->with('status', 'Se cambió correctamente el estado');
    }
    public function agregarEstado(Request $request)
    {
        $validate = $request->validate([
            'state' => 'required|string',
            'text' => 'required|string',
            'color' => 'required|string',
            'icon' => 'required|string',
        ]);
        State::create($request->all());
        return redirect()->back()->with('status', 'Se agregó correctamente el estado');
    }

}
