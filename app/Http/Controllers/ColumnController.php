<?php

namespace App\Http\Controllers;

use App\Models\Column;
use App\Models\Category;
use App\Models\State;
use App\Models\Fecha;
use App\Models\Tournament;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'category_id' => 'max:255|string',
            'autor' => 'required|string',
            'photo' => 'image',
            'user_id' => 'required|string',
            'tournament_id' => 'required|string',
        ]);
        $columna = Column::create($request->all());
        if($request->file('photo')){
            $columna->photo = $request->file('photo')->store('public/photos/cdj');
        }
        $columna->save();
        return redirect()->back()->with('status', 'Se agregó correctamente la columna');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Column  $column
     * @return \Illuminate\Http\Response
     */
    public function show(Column $columna)
    {
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $fecha = Fecha::latest('dia')->first();
        return view('torneo.columnas.show', compact('columna', 'estado', 'categorias', 'fecha'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Column  $column
     * @return \Illuminate\Http\Response
     */
    public function edit(Column $columna)
    {
        $torneos = Tournament::all();
        $categorias = Category::all();
        return view('cdj.columnas.edit',compact('columna', 'categorias', 'torneos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Column  $column
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Column $columna)
    {
        $validated = $request->validate([
            'titulo' => 'required|unique:noticias|max:255|string',
            'resumen' => 'required|string',
            'texto' => 'required|string',
            'category_id' => 'max:255|string',
            'autor' => 'required|string',
            'photo' => 'image',
            'user_id' => 'required|string',
            'tournament_id' => 'required|string',
        ]);

        $columna->update($request->all());
        if($request->file('photo')){
            Storage::delete($columna->photo);
            $columna->photo = $request->file('photo')->store('public/photos/cdj');
        }
        $columna->save();
        return redirect('/cdj/columnas')->with('status', 'Se editó correctamente la columna');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Column  $column
     * @return \Illuminate\Http\Response
     */
    public function destroy(Column $columna)
    {
        $columna->photo ?? Storage::delete($columna->photo);
        $columna->delete();
        return redirect('/cdj/columnas')->with('status', 'Se eliminó correctamente la columna');
    }
}
