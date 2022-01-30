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
        $torneo->active = false;
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
    public function vistaEditarCategoria(Category $categoria)
    {
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
    public function eliminarCategoria(Category $categoria){

        $categoria->delete();
        return redirect('/admin/categorias')->with('status', 'Se eliminó correctamente la categoría');
    }

    // -------------------------FECHAS------------------
    public function fechas(Tournament $torneo)
    {
        $torneos = Tournament::all();
        return view('admin.fechas', ['torneos' => $torneos, 'torneo' => $torneo]);
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
