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
use App\Models\Column;
use App\Models\ExpoToken;
use Illuminate\Support\Facades\DB;


class ApiController extends Controller
{
    public function equipos()
    {
        return Team::all();
    }

    public function equiposPorCategoriaPorTorneo(Tournament $torneo, Category $categoria)
    {
        return $categoria->equipos()->wherePivot('tournament_id', $torneo->id)->get();
    }
    public function equiposPorCategoria(Category $categoria)
    {
        $tournament = Tournament::where('active', true)->first();
        return $tournament->equipos($categoria->id);

    }
    public function tablaPorCategoria(Category $categoria)
    {
        $tournament = Tournament::where('active', true)->first();
        $zonas = DB::table('teams_categories')->select('zone')->where('category_id', $categoria->id)->where('tournament_id', $tournament->id)->groupBy('zone')->get();
        $table = [];
        foreach ($zonas as $zona) {
            $sql = "SELECT
                t.id, 
                t.name as name,
                SUM(CASE WHEN ( m.team_id_1 = t.id OR m.team_id_2= t.id ) and m.finished = 1 THEN 1 ELSE 0 END) AS PJ,
                SUM(CASE WHEN ( m.team_id_1 = t.id OR m.team_id_2= t.id ) and m.team_id_winner = t.id and m.finished = 1 THEN 1 ELSE 0 END) AS G,
                SUM(CASE WHEN ( m.team_id_1 = t.id OR m.team_id_2= t.id ) and m.team_id_winner = 0 and m.finished = 1 THEN 1 ELSE 0 END) AS E,
                SUM(CASE WHEN ( m.team_id_1 = t.id OR m.team_id_2= t.id ) and m.team_id_winner != t.id and m.team_id_winner != 0 and m.finished = 1 THEN 1 ELSE 0 END) AS P,
                COALESCE(SUM(CASE WHEN (m.team_id_1 = t.id and m.finished) THEN m.team_1_goals WHEN (m.team_id_2 = t.id) THEN m.team_2_goals END),0) AS GF, 
                COALESCE(SUM(CASE WHEN (m.team_id_1 = t.id and m.finished) THEN m.team_2_goals WHEN (m.team_id_2 = t.id) THEN m.team_1_goals END),0) AS GC,
                COALESCE((SUM(CASE WHEN (m.team_id_1 = t.id and m.finished) THEN m.team_1_goals WHEN (m.team_id_2 = t.id) THEN m.team_2_goals END) - SUM(CASE WHEN (m.team_id_1 = t.id) THEN m.team_2_goals WHEN (m.team_id_2 = t.id) THEN m.team_1_goals END)),0) AS DIF,
                (SUM(CASE WHEN ( m.team_id_1 = t.id OR m.team_id_2= t.id ) AND m.team_id_winner = t.id and m.finished = 1 THEN 3 ELSE 0 END) + SUM(CASE WHEN ( m.team_id_1 = t.id OR m.team_id_2= t.id ) AND (m.team_id_winner = 0) and m.finished = 1 THEN 1 ELSE 0 END)) AS PTS
            FROM teams t
                INNER JOIN teams_categories as tc on t.id = tc.team_id  
                LEFT OUTER JOIN matches m on t.id in (m.team_id_1, m.team_id_2)
            WHERE m.tournament_id = :tournament AND m.category_id = :category and tc.zone = :zone
            GROUP BY t.id, t.name
            ORDER BY PTS DESC, DIF DESC, G DESC, GF DESC, PJ DESC, t.name ASC;";
            $table[] = DB::select(DB::raw($sql), array('tournament' => $tournament->id, 'category' => $categoria->id, 'zone' => $zona->zone));     
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
    public function columnas()
    {
        $columnas = Column::orderBy('created_at', 'desc')->paginate(4);
        return $columnas;
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
}
