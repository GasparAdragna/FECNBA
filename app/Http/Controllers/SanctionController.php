<?php

namespace App\Http\Controllers;

use App\Models\Sanction;
use App\Models\Tournament;
use App\Models\Fecha;
use App\Models\Category;
use Illuminate\Http\Request;

class SanctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tournament = Tournament::active();
        $torneos = Tournament::all();
        $sancionados = Sanction::where('tournament_id', $tournament->id)->orderBy('active', 'desc')->paginate(10);
        $fechas = Fecha::where('tournament_id', $tournament->id)->get();
        $categorias = Category::all();
        return view('admin.sanction.index', compact('sancionados', 'fechas', 'categorias', 'torneos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'fecha_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'team_id' => 'numeric|required',
            'tournament_id' => 'numeric|required',
            'name' => 'required|string',
            'sanction' => 'required|string',
            'motive' => 'required|string'
        ]);
        Sanction::create($request->all());
        return redirect()->back()->with('status', 'Se agregó correctamente la sanción');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sanction  $sanction
     * @return \Illuminate\Http\Response
     */
    public function edit(Sanction $sancionado)
    {
        $torneos = Tournament::all();
        $fechas = Fecha::where('tournament_id', $sancionado->tournament_id)->get();
        $categorias = Category::all();
        $equipos = $sancionado->category->equipos()->wherePivot('tournament_id', $sancionado->tournament_id)->get();
        return view('admin.sanction.edit', compact('sancionado', 'fechas', 'categorias', 'torneos', 'equipos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sanction  $sanction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sanction $sancionado)
    {
        $validate = $request->validate([
            'fecha_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'team_id' => 'numeric|required',
            'tournament_id' => 'numeric|required',
            'name' => 'required|string',
            'sanction' => 'required|string',
            'motive' => 'required|string'
        ]);
        $sancionado->update($request->all());
        return redirect('admin/sancionados')->with('status', 'Se actualizó correctamente la sanción');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sanction  $sanction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sanction $sancionado)
    {
        $sancionado->delete();
        return redirect()->back()->with('status', 'Se eliminó correctamente la sanción');
    }

    public function terminar(Sanction $sancionado)
    {
        $sancionado->active = !$sancionado->active;
        $sancionado->save();
        return redirect()->back()->with('status', 'Se cambió el estado de la sanción');
    }
}
