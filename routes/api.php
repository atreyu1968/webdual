<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TareaController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\NotificacionController;
use App\Http\Controllers\API\AuthController;

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

// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Tareas
    Route::apiResource('tareas', TareaController::class);
    Route::post('/tareas/{tarea}/asignar', [TareaController::class, 'asignar']);
    Route::get('/tareas/calendario', [TareaController::class, 'calendario']);

    // Usuarios
    Route::apiResource('usuarios', UserController::class);

    // Notificaciones
    Route::get('/notificaciones', [NotificacionController::class, 'index']);
    Route::post('/notificaciones/{notificacion}/marcar-como-leida', [NotificacionController::class, 'marcarComoLeida']);
    Route::delete('/notificaciones/{notificacion}', [NotificacionController::class, 'destroy']);
    Route::post('/notificaciones/marcar-todas-como-leidas', [NotificacionController::class, 'marcarTodasComoLeidas']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Ruta de fallback para manejar 404 en la API
Route::fallback(function(){
    return response()->json([
        'message' => 'Ruta no encontrada. Si el error persiste, contacta a info@webdual.com'
    ], 404);
});