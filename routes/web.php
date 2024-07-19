<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas
Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tareas
    Route::resource('tareas', TareaController::class);
    Route::post('/tareas/{tarea}/asignar', [TareaController::class, 'asignar'])->name('tareas.asignar');
    Route::post('/tareas/reordenar', [TareaController::class, 'reordenar'])->name('tareas.reordenar');

    // Notificaciones
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::post('/notificaciones/{notificacion}/marcar-como-leida', [NotificacionController::class, 'marcarComoLeida'])->name('notificaciones.marcar-leida');
    Route::delete('/notificaciones/{notificacion}', [NotificacionController::class, 'destroy'])->name('notificaciones.destroy');
    Route::post('/notificaciones/marcar-todas-como-leidas', [NotificacionController::class, 'marcarTodasComoLeidas'])->name('notificaciones.marcar-todas-leidas');

    // Calendario
    Route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario.index');

    // Usuarios (solo para administradores)
    Route::middleware(['admin'])->group(function () {
        Route::resource('usuarios', UserController::class);
    });

    // Perfil de usuario
    Route::get('/perfil', [UserController::class, 'perfil'])->name('perfil');
    Route::put('/perfil', [UserController::class, 'actualizarPerfil'])->name('perfil.actualizar');
});

// Ruta de fallback para manejar 404
Route::fallback(function () {
    return view('errors.404');
});