<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\Category;
Use App\Models\State;
Use App\Models\Noticia;
Use App\Models\Fecha;
Use App\Models\Tournament;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $noticias = Noticia::orderBy('created_at', 'desc')->paginate(4);
        $fecha = Fecha::latest('dia')->first();
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
        $noticias = Noticia::paginate(10);
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $fecha = Fecha::latest('dia')->first();
        return view('torneo.noticias.index', compact('noticias', 'categorias', 'estado', 'fecha'));
    }

    public function categoria(Category $categoria)
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
                    INNER JOIN teams_categories as tc on t.id = tc.team_id and tc.tournament_id = :tournament 
                    LEFT OUTER JOIN matches m on t.id in (m.team_id_1, m.team_id_2)
                WHERE m.tournament_id = :tournament AND m.category_id = :category and tc.zone = :zone
                GROUP BY t.id, t.name
                ORDER BY PTS DESC, DIF DESC, G DESC, GF DESC, PJ DESC, t.name ASC;";
                $table[] = DB::select(DB::raw($sql), array('tournament' => $tournament->id, 'category' => $categoria->id, 'zone' => $zona->zone));     
        } 
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $fecha = Fecha::latest('dia')->first();
        $equipos = $tournament->equipos($categoria->id);
        return view('torneo.categorias.index', compact('categoria', 'categorias', 'estado', 'fecha', 'table', 'tournament', 'equipos'));
    }

    public function programacion()
    {
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $fecha = Fecha::latest('dia')->first();
        $tournament = Tournament::where('active', true)->first();
        return view('torneo.programacion.index', compact('categorias', 'estado', 'fecha', 'tournament'));
        
    }
    public function politicasDePrivacidad()
    {
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $fecha = Fecha::latest('dia')->first();
        $tournament = Tournament::where('active', true)->first();
        return view('torneo.politicas', compact('categorias', 'estado', 'fecha', 'tournament'));
    }
}
