<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Tournament;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $equipos = Team::orderBy('name')->get();
        $categorias = Category::all();
        $torneos = Tournament::all();

        return view('admin.equipos.index', compact('equipos', 'categorias', 'torneos'));
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
            'name' => 'required|unique:teams|max:255',
            'category_id' => 'required|integer',
            'tournament_id' => 'required|integer',
        ]);

        $equipo = Team::create(['name' => $request->name]);
        $equipo->categories()->attach($request->category_id, ['tournament_id' => $request->tournament_id]);

        return redirect()->back()->with('status', 'Se agregó correctamente el equipo');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $equipo)
    {
        return view('admin.equipos.show', compact('equipo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $equipo)
    {
        $torneos = Tournament::all();
        $categorias = Category::all();
        return view('admin.equipos.edit', compact('equipo', 'categorias', 'torneos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $equipo)
    {
        $validated = $request->validate([
            'name' => 'required|unique:teams|max:255',
        ]);

        $equipo->name = $request->name;
        $equipo->save();
        return redirect()->back()->with('status', 'Se editó correctamente el equipo');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        foreach ($team->matches as $match) {
            foreach ($match->goles as $goal) {
                $goal->delete();
            }
            $match->delete();
        }
        $team->delete();
        return redirect()->back()->with('status', 'Se eliminó correctamente el equipo');
    }

    public function editarCategoriaEquipo(Request $request, Team $equipo)
    {
        $validated = $request->validate([
            'category_id' => 'required|numeric',
            'id' => 'required|numeric',
            'zone' => 'numeric|nullable'
        ]);

        if(isset($_COOKIE['tournament'])) {
            $tournament = Tournament::find($_COOKIE['tournament']);
        } else {
            $tournament = Tournament::active();
        }

        $query = DB::table('teams_categories')
                    ->where('team_id', $equipo->id)
                    ->where('category_id', $request->id)
                    ->where('tournament_id', $tournament->id)
                    ->update(['category_id' => $request->category_id, 'zone' => $request->zone, 'updated_at' => DB::raw('NOW()')]);

        return redirect()->back()->with('status', 'Se editó correctamente el equipo');
    }
    public function agregarEquipoATorneo(Request $request)
    {
        $query = DB::table('teams_categories')
            ->where('tournament_id', $request->tournament_id)
            ->where('team_id', $request->team_id)->get();

        if (!count($query)){
            DB::table('teams_categories')->insert(['tournament_id' => $request->tournament_id, 'team_id' => $request->team_id, 'category_id' => $request->category_id]);
            return redirect()->back()->with('status', 'Se agregó correctamente el equipo');
        }

        return redirect()->back()->withErrors(['msg' => 'El equipo ya está en el torneo']);

    }
}
