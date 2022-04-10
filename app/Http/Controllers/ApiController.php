<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Fecha;
use App\Models\Player;
use App\Models\State;

class ApiController extends Controller
{
    public function equipos()
    {
        return Team::all();
    }

    public function equiposPorCategoria(Tournament $torneo, Category $categoria)
    {
        return $categoria->equipos()->wherePivot('tournament_id', $torneo->id)->get();
    }
    public function fechasPorTorneo($torneo)
    {
        return Fecha::where('tournament_id', $torneo)->get();
    }
    public function jugadoresPorEquipo($id)
    {
        return Team::find($id)->players;
    }
    public function estadoPorId($id)
    {
        return State::find($id);
    }


}
