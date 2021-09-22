<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TorneoController;
use App\Http\Controllers\ContactoController;


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

Route::get('/', [TorneoController::class, "index"])->name('index');
Route::get('/home', [TorneoController::class, "index"])->name('index');
Route::get('/noticia/{noticia}', [TorneoController::class, "noticia"]);
Route::resource('/contacto', ContactoController::class)->only([
    'create', 'store'
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
        Route::get('/categoria/editar/{id}', [AdminController::class, "vistaEditarCategoria"]);
        Route::post('/categoria/editar/{id}', [AdminController::class, "editarCategoria"]);
        Route::get('/categoria/eliminar/{id}', [AdminController::class, "eliminarCategoria"]);
        // ---------------------------------EQUIPOS------------------------------------
        Route::get('/equipos', [AdminController::class, "equipos"]);
        Route::post('/equipos', [AdminController::class, "agregarEquipo"]);
        Route::get('/equipo/{id}', [AdminController::class, "verEquipo"]);
        Route::get('/equipo/editar/{id}', [AdminController::class, "vistaEditarEquipo"]);
        Route::post('/equipo/editar/{id}', [AdminController::class, "editarEquipo"]);
        Route::get('/equipo/eliminar/{id}', [AdminController::class, "eliminarEquipo"]);
        Route::post('/equipo/categoria/editar', [AdminController::class, "editarCategoriaEquipo"]);
        // ---------------------------------JUGADOR------------------------------------
        Route::get('/jugadores', [AdminController::class, "jugadores"]);
        Route::post('/jugador/agregar', [AdminController::class, "agregarJugador"]);
        Route::get('/jugador/editar/{id}', [AdminController::class, "vistaEditarJugador"]);
        Route::post('/jugador/editar/{id}', [AdminController::class, "editarJugador"]);
        Route::get('/jugador/eliminar/{id}', [AdminController::class, "eliminarJugador"]);
        // ---------------------------------PARTIDOS------------------------------------
        Route::get('/partidos', [AdminController::class, "partidos"]);
        Route::post('/partidos', [AdminController::class, "agregarPartido"]);
        Route::get('/partido/editar/{id}', [AdminController::class, "vistaEditarPartido"]);
        Route::post('/partido/editar/{id}', [AdminController::class, "editarPartido"]);
        Route::post('/partido/{id}/agregar/gol', [AdminController::class, "agregarGol"]);
        // ---------------------------------FECHAS------------------------------------
        Route::get('/fechas/torneo/{torneo}', [AdminController::class, "fechas"]);
        Route::post('/fechas/torneo/{torneo}', [AdminController::class, "agregarFecha"]);
        Route::get('/fecha/editar/{fecha}', [AdminController::class, "vistaEditarFecha"]);
        Route::post('/fecha/editar/{fecha}', [AdminController::class, "editarFecha"]);
        // ---------------------------------NOTICIAS------------------------------------
        Route::get('/noticias', [AdminController::class, "noticias"]);
        Route::post('/noticia/agregar', [AdminController::class, "agregarNoticia"]);
        Route::get('/noticia/editar/{id}', [AdminController::class, "vistaEditarNoticia"]);
        Route::post('/noticia/editar/{id}', [AdminController::class, "editarNoticia"]);
        // ---------------------------------ESTADO------------------------------------
        Route::get('/editar/estado', [AdminController::class, "vistaEditarEstado"]);
        Route::post('/cambiar/estado', [AdminController::class, 'cambiarEstado']);
        Route::post('/agregar/estado', [AdminController::class, 'agregarEstado']);
    });
});


require __DIR__.'/auth.php';
