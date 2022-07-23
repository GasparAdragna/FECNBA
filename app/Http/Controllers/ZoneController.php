<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;

Use App\Models\Tournament;
Use App\Models\Category;

class ZoneController extends Controller
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
        $categorias = Category::all();
        $zonas = Zone::where('tournament_id', $tournament->id)->get();
        return view('admin.zones.index', compact('categorias', 'torneos', 'zonas'));
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
            'category_id' => 'required|numeric',
            'tournament_id' => 'numeric|required',
            'name' => 'required|string',
        ]);
        Zone::create($request->all());
        return redirect()->back()->with('status', 'Se agregó correctamente la zona');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function edit(Zone $zona)
    {
        $torneos = Tournament::all();
        $categorias = Category::all();
        return view('admin.zones.edit', compact('categorias', 'torneos', 'zona'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zone $zona)
    {
        $validate = $request->validate([
            'category_id' => 'required|numeric',
            'tournament_id' => 'numeric|required',
            'name' => 'required|string',
        ]);
        $zona->update($request->all());
        return redirect('admin/zonas')->with('status', 'Se editó correctamente la zona');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Zone $zona)
    {
        //
    }
}
