<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TorneoController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PartidoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\PlayerController;


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
        Route::get('/torneos', [AdminController::class, "torneos"]);
        Route::post('/torneos', [AdminController::class, "agregarTorneo"]);
        // ---------------------------------CATEGORIAS------------------------------------
        Route::get('/categorias', [AdminController::class, "categorias"]);
        Route::post('/categorias', [AdminController::class, "agregarCategoria"]);
        Route::get('/categoria/editar/{categoria}', [AdminController::class, "vistaEditarCategoria"]);
        Route::post('/categoria/editar/{categoria}', [AdminController::class, "editarCategoria"]);
        Route::get('/categoria/eliminar/{categoria}', [AdminController::class, "eliminarCategoria"]);
        // ---------------------------------EQUIPOS------------------------------------
        Route::get('/equipos', [EquipoController::class, "index"]);
        Route::post('/equipos', [EquipoController::class, "store"]);
        Route::get('/equipos/{equipo}', [EquipoController::class, "show"]);
        Route::get('/equipos/editar/{equipo}', [EquipoController::class, "edit"]);
        Route::post('/equipos/editar/{equipo}', [EquipoController::class, "update"]);
        Route::get('/equipo/eliminar/{equipo}', [AdminController::class, "eliminarEquipo"]);
        Route::post('/equipos/categoria/editar/{equipo}', [EquipoController::class, "editarCategoriaEquipo"]);
        Route::post('/equipos/agregar/torneo', [EquipoController::class, "agregarEquipoATorneo"]);
        // ---------------------------------JUGADOR------------------------------------
        Route::get('/jugadores', [PlayerController::class, "index"]);
        Route::post('/jugadores/agregar', [PlayerController::class, "store"]);
        Route::get('/jugadores/editar/{jugador}', [PlayerController::class, "edit"]);
        Route::post('/jugadores/editar/{jugador}', [PlayerController::class, "update"]);
        Route::post('/jugadores/editar/equipo/{jugador}', [PlayerController::class, "editarEquipo"]);
        Route::get('/jugadores/eliminar/{id}', [PlayerController::class, "destroy"]);
        Route::get('/jugadores/equipo/eliminar/{id}', [PlayerController::class, "eliminarEquipo"]);
        // ---------------------------------PARTIDOS------------------------------------
        Route::get('/partidos', [PartidoController::class, "index"]);
        Route::post('/partidos', [PartidoController::class, "store"]);
        Route::get('/partido/editar/{partido}', [PartidoController::class, "edit"]);
        Route::post('/partido/editar/{id}', [PartidoController::class, "update"]);
        Route::post('/partido/{partido}/agregar/gol', [PartidoController::class, "agregarGol"]);
        Route::post('/gol/eliminar/{gol}', [PartidoController::class, "eliminarGol"]);
        Route::get('/partido/{partido}/terminado', [PartidoController::class, 'terminado']);
        // ---------------------------------FECHAS------------------------------------
        Route::get('/fechas/torneo/{torneo}', [AdminController::class, "fechas"]);
        Route::post('/fechas/torneo/{torneo}', [AdminController::class, "agregarFecha"]);
        Route::get('/fecha/editar/{fecha}', [AdminController::class, "vistaEditarFecha"]);
        Route::post('/fecha/editar/{fecha}', [AdminController::class, "editarFecha"]);
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
