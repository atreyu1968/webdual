<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index()
    {
        $notificaciones = auth()->user()->notificaciones()->orderBy('created_at', 'desc')->get();
        return view('notificaciones.index', compact('notificaciones'));
    }

    public function marcarComoLeida(Notificacion $notificacion)
    {
        // Verificar si el usuario actual es el propietario de la notificación
        if ($notificacion->user_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $notificacion->update(['leida' => true]);
        return response()->json(['success' => true]);
    }

    public function obtenerNoLeidas()
    {
        $notificaciones = auth()->user()->notificaciones()
                                ->where('leida', false)
                                ->orderBy('created_at', 'desc')
                                ->get();
        
        return response()->json($notificaciones);
    }

    public function marcarTodasComoLeidas()
    {
        auth()->user()->notificaciones()
                      ->where('leida', false)
                      ->update(['leida' => true]);

        return response()->json(['success' => true]);
    }

    public function eliminar(Notificacion $notificacion)
    {
        // Verificar si el usuario actual es el propietario de la notificación
        if ($notificacion->user_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $notificacion->delete();
        return response()->json(['success' => true]);
    }
}