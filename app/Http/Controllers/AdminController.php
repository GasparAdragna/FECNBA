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
use App\Services\NotificationService;


class AdminController extends Controller
{
    private $notificationService;
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function dashboard()
    {
        $categorias = Category::all()->count();
        $equipos = Team::all()->count();
        $partidos = Match::all()->count();
        $torneos = Tournament::all()->count();
        $estado = State::where('active', true)->first();
        $fecha = Fecha::where('active', true)->first();

        return view('dashboard', ['categorias' => $categorias, 'equipos' => $equipos, 'partidos' => $partidos,'torneos' => $torneos, "fecha" => $fecha, "estado" => $estado]);
    }

    // -------------------------NOTICIAS------------------
    public function agregarNoticia(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|unique:noticias|max:255|string',
            'texto' => 'required|string',
            'resumen' => 'required|string',
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
        $estado = State::where('active', true)->first();
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

        if($request->notification) {
            $response = $this->notificationService->send($estado->state, $estado->text);
        }

        if (count($response["errors"])) return redirect()->back()->with('warning', 'Se cambió el estado pero no se pudo notificar a todos los usuarios');

        return redirect()->back()->with('status', 'Se cambió correctamente el estado y se notificó a todos los usuarios');
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
