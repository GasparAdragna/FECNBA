<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\Category;
Use App\Models\State;
Use App\Models\Noticia;
Use App\Models\Fecha;


class TorneoController extends Controller
{
    public function index()
    {
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $noticias = Noticia::orderBy('created_at', 'desc')->paginate(4);
        $fecha = Fecha::latest('dia')->first();
        return view('torneo.index', compact('categorias', 'estado', 'noticias', 'fecha'));
    }
    public function noticia(Noticia $noticia)
    {
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $fecha = Fecha::latest('dia')->first();
        return view('torneo.noticia', compact('noticia', 'estado', 'categorias', 'fecha'));
    }
}
