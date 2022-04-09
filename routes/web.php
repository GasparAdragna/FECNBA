<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\FechaController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, "index"])->name('index');
Route::get('/home', [HomeController::class, "index"])->name('index');
Route::get('/noticia/{noticia}', [NoticiaController::class, "show"]);
Route::get('/noticias', [HomeController::class, "noticias"]);
Route::get('/categoria/{categoria:slug}', [HomeController::class, "categoria"]);
Route::get('/programacion', [HomeController::class, "programacion"]);
Route::resource('/contacto', ContactoController::class)->only([
    'create', 'store', 'index'
]);





Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, "dashboard"])->name('dashboard');
        // ---------------------------------TORNEOS------------------------------------
        Route::get('/torneos', [TournamentController::class, "index"]);
        Route::post('/torneos', [TournamentController::class, "store"]);
        Route::get('/torneo/editar/{tournament}', [TournamentController::class, "edit"]);
        Route::post('/torneo/editar/{tournament}', [TournamentController::class, "update"]);
        // ---------------------------------CATEGORIAS------------------------------------
        Route::get('/categorias', [AdminController::class, "categorias"]);
        Route::post('/categorias', [AdminController::class, "agregarCategoria"]);
        Route::get('/categoria/editar/{categoria}', [AdminController::class, "vistaEditarCategoria"]);
        Route::post('/categoria/editar/{categoria}', [AdminController::class, "editarCategoria"]);
        Route::get('/categoria/eliminar/{categoria}', [AdminController::class, "eliminarCategoria"]);
        // ---------------------------------EQUIPOS------------------------------------
        Route::get('/equipos', [TeamController::class, "index"]);
        Route::post('/equipos', [TeamController::class, "store"]);
        Route::get('/equipos/{equipo}', [TeamController::class, "show"]);
        Route::get('/equipos/editar/{equipo}', [TeamController::class, "edit"]);
        Route::post('/equipos/editar/{equipo}', [TeamController::class, "update"]);
        Route::get('/equipo/eliminar/{equipo}/torneo/{torneo}', [TeamController::class, "eliminarEquipoDeTorneo"]);
        Route::post('/equipos/categoria/editar/{equipo}', [TeamController::class, "editarCategoriaEquipo"]);
        Route::post('/equipos/agregar/torneo', [TeamController::class, "agregarEquipoATorneo"]);
        // ---------------------------------JUGADOR------------------------------------
        Route::get('/jugadores', [PlayerController::class, "index"]);
        Route::post('/jugadores/agregar', [PlayerController::class, "store"]);
        Route::get('/jugadores/editar/{jugador}', [PlayerController::class, "edit"]);
        Route::post('/jugadores/editar/{jugador}', [PlayerController::class, "update"]);
        Route::post('/jugadores/editar/equipo/{jugador}', [PlayerController::class, "editarEquipo"]);
        Route::get('/jugadores/eliminar/{id}', [PlayerController::class, "destroy"]);
        Route::get('/jugadores/equipo/eliminar/{id}', [PlayerController::class, "eliminarEquipo"]);
        // ---------------------------------PARTIDOS------------------------------------
        Route::get('/partidos', [MatchController::class, "index"]);
        Route::post('/partidos', [MatchController::class, "store"]);
        Route::get('/partido/editar/{partido}', [MatchController::class, "edit"]);
        Route::post('/partido/editar/{id}', [MatchController::class, "update"]);
        Route::post('/partido/{partido}/agregar/gol', [MatchController::class, "agregarGol"]);
        Route::post('/gol/eliminar/{gol}', [MatchController::class, "eliminarGol"]);
        Route::get('/partido/{partido}/terminado', [MatchController::class, 'terminado']);
        // ---------------------------------FECHAS------------------------------------
        Route::get('/fechas', [FechaController::class, "index"]);
        Route::post('/fechas', [FechaController::class, "store"]);
        Route::get('/fecha/editar/{fecha}', [FechaController::class, "edit"]);
        Route::post('/fecha/editar/{fecha}', [FechaController::class, "update"]);
        // ---------------------------------NOTICIAS------------------------------------
        Route::get('/noticias', [NoticiaController::class, "index"]);
        Route::post('/noticia/agregar', [NoticiaController::class, "store"]);
        Route::get('/noticia/editar/{noticia}', [NoticiaController::class, "edit"]);
        Route::post('/noticia/editar/{noticia}', [NoticiaController::class, "update"]);
        Route::post('/noticia/eliminar/{noticia}', [NoticiaController::class, "destroy"]);
        // ---------------------------------ESTADO------------------------------------
        Route::get('/editar/estado', [AdminController::class, "vistaEditarEstado"]);
        Route::post('/cambiar/estado', [AdminController::class, 'cambiarEstado']);
        Route::post('/agregar/estado', [AdminController::class, 'agregarEstado']);
    });
});


require __DIR__.'/auth.php';
