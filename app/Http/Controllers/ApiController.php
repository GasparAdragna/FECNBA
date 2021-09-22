<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Fecha;
use App\Models\Player;
use App\Models\State;

class ApiController extends Controller
{
    public function equipos()
    {
        return Team::all();
    }

    public function equiposPorCategoria($categoria)
    {
        return Team::where('category_id', $categoria)->get();
    }
    public function fechasPorTorneo($torneo)
    {
        return Fecha::where('tournament_id', $torneo)->get();
    }
    public function jugadoresPorEquipo($id)
    {
        return Player::where('team_id', $id)->get();
    }
    public function estadoPorId($id)
    {
        return State::find($id);
    }


}
