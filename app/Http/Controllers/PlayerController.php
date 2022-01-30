<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jugadores = Player::all();
        $equipos = Team::all();
        $torneos = Tournament::all();
        return view('admin.players.index', compact('jugadores', 'torneos', 'equipos'));
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
            'first_name' => 'required|max:255|string',
            'last_name' => 'required|max:255|string',
            'dni' => 'numeric|unique:players|required',
            'os' => 'nullable|string',
            'birthday' => 'nullable|date',
            'year' => 'numeric|nullable',
            'email' => 'string|nullable',
            'team_id' => 'numeric|required',
        ]);
        Player::create($request->all());
        return redirect()->back()->with('status', 'Se agregó correctamente el jugador');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function show(Player $player)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function edit(Player $jugador)
    {
        $equipos = Team::all();
        $tournaments = Tournament::all();
        return view('admin.players.edit', compact('equipos', 'jugador', 'tournaments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Player $jugador)
    {
        $validated = $request->validate([
            'first_name' => 'required|max:255|string',
            'last_name' => 'required|max:255|string',
            'dni' => 'numeric|required',
            'os' => 'nullable|string',
            'birthday' => 'nullable|date',
            'year' => 'numeric|nullable',
            'email' => 'string|nullable',
        ]);
        $jugador->update($request->all());
        return redirect()->back()->with('status', 'Se editó correctamente el jugador');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->back()->with('status', 'Se eliminó correctamente el jugador');
    }

    public function editarEquipo(Request $request, Player $jugador)
    {
        $query = DB::table('teams_players')
                    ->where('tournament_id', $request->tournament_id)
                    ->where('player_id', $jugador->id)
                    ->updateOrInsert(['tournament_id' => $request->tournament_id, 'team_id' => $request->team_id, 'player_id' => $jugador->id]);
        return redirect()->back()->with('status', 'Se editó correctamente el jugador');
           
    }
    public function eliminarEquipo($id)
    {
        $query = DB::table('teams_players')
            ->where('id', $id)
            ->delete();
     
        return redirect()->back()->with('status', 'Se editó correctamente el jugador');
    }
}
