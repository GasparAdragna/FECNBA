<?php

namespace App\Http\Controllers;

Use App\Models\Tournament;
Use App\Models\Fecha;

use Illuminate\Http\Request;

class FechaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $torneos = Tournament::all();
        return view('admin.fechas.index', compact('torneos'));
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
            'name' => 'required|unique:fechas|max:255',
            'tournament_id' => 'required',
            'active' => 'required',
        ]);
        if ($request->active) {
            $fechaActiva = Fecha::where('active', true)->first();
            if (isset($fechaActiva)) {
                $fechaActiva->update(['active' => false]);
            }
        }
        Fecha::create($request->all());
        return redirect()->back()->with('status', 'Se agregó correctamente la fecha');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Fecha $fecha)
    {
        $torneos = Tournament::all();
        return view('admin.fechas.edit', compact('fecha', 'torneos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fecha $fecha)
    {
        if ($request->active) {
            $fechaActiva = Fecha::where('active', true)->first();
            if (isset($fechaActiva)) {
                $fechaActiva->update(['active' => false]);
            }
        }
        $fecha->update($request->all());
        return redirect()->back()->with('status', 'Se editó correctamente la fecha');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fecha $fecha)
    {
        if ($fecha->active) {
            return redirect()->back()->with('error', 'No se puede eliminar la fecha activa');
        }

        foreach ($fecha->matches as $partido) {
            foreach ($partido->goles as $gol) {
                $gol->delete();
            }
            $partido->delete();
        }
        $fecha->delete();
        return redirect()->back()->with('status', 'Se eliminó correctamente la fecha');
    }
}
