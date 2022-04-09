<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Match;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Fecha;
use App\Models\Goal;

class MatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tournament = Tournament::active();
        $partidos = Match::where('tournament_id', $tournament->id)->orderBy('fecha_id', 'desc')->paginate(20);
        $torneos = Tournament::all();
        $categorias = Category::all();
        $fechas = Fecha::where('tournament_id', $tournament->id)->get();
        return view('admin.matches.index', compact('partidos', 'torneos', 'categorias', 'fechas'));
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
            'team_id_1' => 'numeric|required',
            'team_id_2' => 'numeric|required',
            'horario' => 'required',
            'cancha' => 'numeric|required',
            'tournament_id' => 'numeric|required'
        ]);
        Match::create($request->all());
        return redirect()->back()->with('status', 'Se agregó correctamente el partido');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Match $partido)
    {
        $torneos = Tournament::all();
        $categorias = Category::all();
        $fechas = Fecha::all();
        $equipos = $partido->category->equipos()->wherePivot('tournament_id', $partido->tournament_id)->get();
        return view('admin.matches.edit', compact('partido', 'torneos', 'categorias', 'fechas', 'equipos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Match $partido)
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function agregarGol(Match $partido, Request $request)
    {
        $validate = $request->validate([
            'team_id' => 'required|numeric',
            'match_id' => 'required|numeric'
        ]);

        Goal::create($request->all());

        if ($partido->team_id_1 == $request->team_id) {
            $partido->team_1_goals = $partido->team_1_goals + 1;
        } else {
            $partido->team_2_goals = $partido->team_2_goals + 1;
        }
        $partido->save();

        return redirect()->back()->with('status', 'Se agregó correctamente el gol al partido');
    }

    public function eliminarGol(Goal $gol)
    {
        $match = $gol->match;

        if ($match->team_id_1 == $gol->team_id) {
            $match->team_1_goals = $match->team_1_goals - 1;
        } else {
            $match->team_2_goals = $match->team_2_goals - 1;
        }

        $gol->delete();

        return redirect()->back()->with('status', 'Se eliminó correctamente el gol');
    }

    public function terminado(Match $partido)
    {
        $partido->finished = !$partido->finished;

        if ($partido->team_1_goals > $partido->team_2_goals){
            $partido->team_id_winner = $partido->team_id_1;
        }

        if ($partido->team_2_goals > $partido->team_1_goals){
            $partido->team_id_winner = $partido->team_id_2;
        }

        $partido->save();

        return redirect('/admin/partidos')->with('status', 'Se actualizó correctamente el partido');

    }
}
