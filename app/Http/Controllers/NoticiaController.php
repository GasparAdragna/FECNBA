<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Category;
use App\Models\State;
use App\Models\Fecha;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\NotificationService;
use Auth;

class NoticiaController extends Controller
{
    private $notificationService;
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->middleware('auth')->only('index', 'destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $noticias = Noticia::all();
        return view('admin.noticias.index', ['noticias' => $noticias]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|unique:noticias|max:255|string',
            'resumen' => 'required|string',
            'texto' => 'required|string',
            'estado' => 'required|max:255|string',
            'photo' => 'image',
        ]);

        $noticia = new Noticia;
        if($request->file('photo')){
            $noticia->photo = $request->file('photo')->store('public/photos');
        }
        $noticia->resumen = $request->resumen;
        $noticia->titulo = $request->titulo;
        $noticia->texto = $request->texto;
        $noticia->estado = $request->estado;
        $noticia->user_id = Auth::id();
        $noticia->save();

        if ($request->notification) {
            $response = $this->notificationService->send($noticia->titulo, $noticia->resumen);
            if (count($response["errors"])){
                return redirect()->back()->with('status', 'Se agregó correctamente la noticia, pero no se pudo notificar a todos los usuarios');
            }
        }
        return redirect()->back()->with('status', 'Se agregó correctamente la noticia y se notificó a todos los usuarios');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Noticia  $noticia
     * @return \Illuminate\Http\Response
     */
    public function show(Noticia $noticia)
    {
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $fecha = Fecha::latest('dia')->first();
        return view('torneo.noticias.show', compact('noticia', 'estado', 'categorias', 'fecha'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Noticia  $noticia
     * @return \Illuminate\Http\Response
     */
    public function edit(Noticia $noticia)
    {
        return view('admin.noticias.edit',compact('noticia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Noticia  $noticia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Noticia $noticia)
    {
        $validated = $request->validate([
            'titulo' => 'required|max:255|string',
            'texto' => 'required|string',
            'resumen' => 'required|string',
            'estado' => 'required|max:255|string',
            'photo' => 'image',
        ]);
        $noticia->resumen = $request->resumen;
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Noticia  $noticia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Noticia $noticia)
    {
        $noticia->photo ?? Storage::delete($noticia->photo);
        $noticia->delete();
        return redirect('/admin/noticias')->with('status', 'Se eliminó correctamente la noticia');
    }
}
