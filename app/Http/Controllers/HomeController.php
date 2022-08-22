<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\Category;
Use App\Models\State;
Use App\Models\Noticia;
Use App\Models\Fecha;
Use App\Models\Team;
Use App\Models\Match;
Use App\Models\Tournament;
Use App\Models\Sanction;
Use App\Models\Zone;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $noticias = Noticia::orderBy('created_at', 'desc')->paginate(4);
        $fecha = Fecha::where('active', true)->first();
        $tournament = Tournament::where('active', true)->first();
        $sql = "SELECT 
                    goals.player_id,
                    players.first_name,
                    players.last_name,
                    teams.name as team_name,
                    categories.name as category_name,
                    categories.id as category_id,
                    count(goals.id) as amount 
                FROM goals 
                INNER JOIN matches on goals.match_id = matches.id
                INNER JOIN players on goals.player_id = players.id
                INNER JOIN teams on goals.team_id = teams.id
                INNER JOIN categories on matches.category_id = categories.id
                WHERE matches.tournament_id = :tournament and goals.player_id IS NOT NULL
                GROUP BY goals.player_id;";

        $goleadores = DB::select(DB::raw($sql), array('tournament' => $tournament->id));
        return view('torneo.index', compact('categorias', 'estado', 'noticias', 'fecha', 'goleadores'));
    }
    public function noticias()
    {
        $noticias = Noticia::orderBy('created_at', 'desc')->paginate(10);
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $fecha = Fecha::where('active', true)->first();
        return view('torneo.noticias.index', compact('noticias', 'categorias', 'estado', 'fecha'));
    }

    public function categoria(Category $categoria)
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
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $fecha = Fecha::where('active', true)->first();
        $equipos = $tournament->equipos($categoria->id);
        $sancionados = Sanction::where('tournament_id', $tournament->id)->where('category_id', $categoria->id)->where('active', true)->get();
        return view('torneo.categorias.index', compact('categoria', 'categorias', 'estado', 'fecha', 'table', 'tournament', 'equipos', 'sancionados', 'zonas'));
    }

    public function programacion()
    {
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $fecha = Fecha::where('active', true)->first();
        $tournament = Tournament::where('active', true)->first();
        return view('torneo.programacion.index', compact('categorias', 'estado', 'fecha', 'tournament'));
    }
    
    public function politicasDePrivacidad()
    {
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $fecha = Fecha::where('active', true)->first();
        $tournament = Tournament::where('active', true)->first();
        return view('torneo.politicas', compact('categorias', 'estado', 'fecha', 'tournament'));
    }
    public function app()
    {
        return view('torneo.app');
    }
    public function equipo(Team $equipo)
    {
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $fecha = Fecha::where('active', true)->first();
        $tournament = Tournament::where('active', true)->first();
        $sancionados = Sanction::where('tournament_id', $tournament->id)->where('active', true)->where('team_id', $equipo->id)->get();
        $matches = Match::where('tournament_id', $tournament->id)->where('team_id_1', $equipo->id)->orWhere('team_id_2', $equipo->id)->orderBy('id', 'desc')->get();
        return view('torneo.equipos.index', compact('equipo', 'categorias', 'estado', 'fecha', 'tournament', 'sancionados', 'matches'));
    }
}
