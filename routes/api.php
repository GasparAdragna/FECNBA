<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ---------------------------------JUGADORES------------------------------------
// ---------------------------------CATEGORIA------------------------------------
Route::get('/tabla/{categoria:slug}', [ApiController::class, "tablaPorCategoria"]);

// ---------------------------------EQUIPOS------------------------------------
Route::get('/equipo/{id}/jugadores', [ApiController::class, "jugadoresPorEquipo"]);
Route::get('/equipos', [ApiController::class, "equipos"]);
Route::get('/equipos/torneo/{torneo}/categoria/{categoria}', [ApiController::class, "equiposPorCategoriaPorTorneo"]);
Route::get('/equipos/{categoria:slug}', [ApiController::class, "equiposPorCategoria"]);

// ---------------------------------FECHAS------------------------------------
Route::get('/fechas/torneo/{id}', [ApiController::class, "fechasPorTorneo"]);
Route::get('/fecha', [ApiController::class, "fecha"]);
// ---------------------------------ESTADO------------------------------------
Route::get('/estado/{id}', [ApiController::class, "estadoPorId"]);
Route::get('/estado', [ApiController::class, "estado"]);
// ---------------------------------NOTICIAS------------------------------------
Route::get('/noticias', [ApiController::class, "noticias"]);



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
