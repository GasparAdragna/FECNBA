<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Fecha;
use App\Models\Player;
use App\Models\Match;
use App\Models\Sanction;
use App\Models\State;
use App\Models\Noticia;
use App\Models\ExpoToken;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;


class ApiController extends Controller
{
    public function equipos()
    {
        return Team::all();
    }

    public function equiposPorCategoriaPorTorneo(Tournament $torneo, Category $categoria)
    {
        if ($categoria->name == 'Promoción') {
            $sql = "SELECT
                        teams.id,
                        teams.name,
                        teams.created_at,
                        teams.updated_at
                    FROM teams
                    INNER JOIN teams_categories on teams.id = teams_categories.team_id
                    WHERE teams_categories.tournament_id = 2
                    ORDER BY teams.name ASC;";
            $table = DB::select(DB::raw($sql), array('tournament' => $torneo->id));     
            return $table;
        }
        return $categoria->equipos()->wherePivot('tournament_id', $torneo->id)->get();
    }
    public function equiposPorCategoria(Category $categoria)
    {
        $tournament = Tournament::where('active', true)->first();
        return $tournament->equipos($categoria->id);
    }
    public function zonasPorCategoria(Category $id)
    {
        $zones = $id->zonas()->get();
        return $zones;
    }
    public function tablaPorCategoria(Category $categoria)
    {
        $tournament = Tournament::where('active', true)->first();
        $zonas = Zone::where('tournament_id', $tournament->id)->where('category_id', $categoria->id)->get();
        $table = [];
        foreach ($zonas as $zona) {
            $sql = "SELECT
                    t.id, 
                    t.name as name,
                    SUM(CASE WHEN ( m.team_id_1 = t.id OR m.team_id_2= t.id ) THEN 1 ELSE 0 END) AS PJ,
                    SUM(CASE WHEN ( m.team_id_1 = t.id OR m.team_id_2= t.id ) and m.team_id_winner = t.id THEN 1 ELSE 0 END) AS G,
                    SUM(CASE WHEN ( m.team_id_1 = t.id OR m.team_id_2= t.id ) and m.team_id_winner = 0 THEN 1 ELSE 0 END) AS E,
                    SUM(CASE WHEN ( m.team_id_1 = t.id OR m.team_id_2= t.id ) and m.team_id_winner != t.id and m.team_id_winner != 0 THEN 1 ELSE 0 END) AS P,
                    COALESCE(SUM(CASE WHEN (m.team_id_1 = t.id) THEN m.team_1_goals WHEN (m.team_id_2 = t.id) THEN m.team_2_goals END),0) AS GF, 
                    COALESCE(SUM(CASE WHEN (m.team_id_1 = t.id) THEN m.team_2_goals WHEN (m.team_id_2 = t.id) THEN m.team_1_goals END),0) AS GC,
                    COALESCE((SUM(CASE WHEN (m.team_id_1 = t.id) THEN m.team_1_goals WHEN (m.team_id_2 = t.id) THEN m.team_2_goals END) - SUM(CASE WHEN (m.team_id_1 = t.id) THEN m.team_2_goals WHEN (m.team_id_2 = t.id) THEN m.team_1_goals END)),0) AS DIF,
                    (SUM(CASE WHEN ( m.team_id_1 = t.id OR m.team_id_2= t.id ) AND m.team_id_winner = t.id THEN 3 ELSE 0 END) + SUM(CASE WHEN ( m.team_id_1 = t.id OR m.team_id_2= t.id ) AND (m.team_id_winner = 0) THEN 1 ELSE 0 END)) AS PTS
                FROM teams t
                    LEFT OUTER JOIN matches m on t.id in (m.team_id_1, m.team_id_2) and m.finished = 1
                WHERE m.tournament_id = :tournament AND m.category_id = :category and m.zone_id = :zone
                GROUP BY t.id, t.name
                ORDER BY PTS DESC, DIF DESC, G DESC, GF DESC, PJ DESC, t.name ASC;";
            $table[] = DB::select(DB::raw($sql), array('tournament' => $tournament->id, 'category' => $categoria->id, 'zone' => $zona->id));     
        } 
        return $table;
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
        $fecha = Fecha::where('active', true)->first();
        $partidos = [];

        foreach ($fecha->matches->sortBy('horario') as $index => $partido){
            $partidos[] = [
                'id' => $partido->id,
                'local' => $partido->local->name,
                'visitante' => $partido->visita->name,
                'local_id' => $partido->local->id,
                'visitante_id' => $partido->visita->id,
                'horario' => $partido->horario,
                'finished' => $partido->finished,
                'goles_local' => $partido->goles->where('team_id', $partido->local->id)->count(),
                'goles_visita' => $partido->goles->where('team_id', $partido->visita->id)->count(),
                'categoria' => $partido->category->name,
                'zona' => $partido->zone->name,
                'torneo' => $partido->tournament->name,
                'cancha' => $partido->cancha,
            ];
        }

        return [
            'fecha' => $fecha,
            'partidos' => $partidos
        ];
    }

    public function fechasPorCategoria(Category $categoria)
    {
        $tournament = Tournament::where('active', true)->first();
        $partidos = [];
        $fechas = $tournament->fechas;

        foreach ($fechas as $index => $fecha) {
            foreach ($fecha->matches->where('category_id', $categoria->id)->sortBy('horario') as $partido) {
                $partidos[] = [
                    'id' => $partido->id,
                    'local' => $partido->local->name,
                    'visitante' => $partido->visita->name,
                    'local_id' => $partido->local->id,
                    'visitante_id' => $partido->visita->id,
                    'horario' => $partido->horario,
                    'finished' => $partido->finished,
                    'goles_local' => $partido->goles->where('team_id', $partido->local->id)->count(),
                    'goles_visita' => $partido->goles->where('team_id', $partido->visita->id)->count(),
                    'categoria' => $partido->category->name,
                    'zona' => $partido->zone->name,
                    'torneo' => $partido->tournament->name,
                    'cancha' => $partido->cancha,
                ];
            }
            $fecha->partidos = $partidos;
            $partidos = [];
        }

        return [
            'fechas' => $tournament->fechas,
        ];
    }
    public function saveToken(Request $request)
    {
        if ($request->api_token != env('API_TOKEN')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = ExpoToken::where('token', $request->token)->first();

        if ($token) {
            return response()->json(['success' => 'Token already exists'], 200);
        }
        
        return ExpoToken::create([
            'token' => $request->token
        ]);
    }
    public function app()
    {
        return response()->json(['version' => '1.0.15'], 200);
    }
    public function equipo(Team $equipo)
    {
        $tournament = Tournament::where('active', true)->first();
        $matches = Match::where('tournament_id', $tournament->id)->where('team_id_1', $equipo->id)->orWhere('team_id_2', $equipo->id)->orderBy('id', 'desc')->get();
        $sancionados = Sanction::where('tournament_id', $tournament->id)->where('active', true)->where('team_id', $equipo->id)->get();

        
        foreach ($matches as $index => $partido){
            $partidos[] = [
                'id' => $partido->id,
                'local' => $partido->local->name,
                'visitante' => $partido->visita->name,
                'local_id' => $partido->local->id,
                'visitante_id' => $partido->visita->id,
                'horario' => $partido->horario,
                'finished' => $partido->finished,
                'goles_local' => $partido->goles->where('team_id', $partido->local->id)->count(),
                'goles_visita' => $partido->goles->where('team_id', $partido->visita->id)->count(),
                'categoria' => $partido->category->name,
                'zona' => $partido->zone->name,
                'torneo' => $partido->tournament->name,
                'cancha' => $partido->cancha,
                'fecha' => $partido->fecha->name,
                'dia' => isset($partido->fecha->dia) ? date('d/m', strtotime($partido->fecha->dia)) : 'Día sin definir',
            ];
        }
        
        return [
            'equipo' => $equipo,
            'matches' => $partidos,
            'sancionados' => $sancionados,
        ];
    }
}
