<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Fecha;
use App\Models\Player;
use App\Models\State;
use App\Models\Noticia;

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
    public function estado()
    {
        return State::where('active', true)->get();
    }
    public function noticias()
    {
        $noticias = Noticia::orderBy('created_at', 'desc')->paginate(4);
        return $noticias;
    }
    public function fecha()
    {
        $fecha = Fecha::latest('dia')->first();
        $partidos = [];

        foreach ($fecha->matches->sortBy('horario') as $index => $partido){
            $partidos[] = [
                'id' => $partido->id,
                'local' => $partido->local->name,
                'visitante' => $partido->visita->name,
                'horario' => $partido->horario,
                'finished' => $partido->finished,
                'goles_local' => $partido->goles->where('team_id', $partido->local->id)->count(),
                'goles_visita' => $partido->goles->where('team_id', $partido->visita->id)->count(),
                'categoria' => $partido->category->name,
                'zona' => $partido->zona,
                'torneo' => $partido->tournament->name,
                'cancha' => $partido->cancha,
            ];
        }

        return [
            'fecha' => $fecha,
            'partidos' => $partidos
        ];
    }

}
