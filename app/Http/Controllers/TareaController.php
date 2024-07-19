<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\User;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TareaController extends Controller
{
    public function index()
    {
        $tareas = Auth::user()->tareas()->orderBy('fecha_vencimiento')->get();
        $tareasAgrupadas = [
            'pendiente' => $tareas->where('estado', 'pendiente'),
            'en_progreso' => $tareas->where('estado', 'en_progreso'),
            'completada' => $tareas->where('estado', 'completada'),
        ];
        return view('tareas.index', compact('tareasAgrupadas'));
    }

    public function create()
    {
        $usuarios = User::all();
        return view('tareas.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_vencimiento' => 'required|date',
            'estado' => 'required|in:pendiente,en_progreso,completada',
            'asignado_id' => 'nullable|exists:users,id'
        ]);

        $tarea = Tarea::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'estado' => $request->estado,
            'creador_id' => Auth::id(),
            'asignado_id' => $request->asignado_id
        ]);

        if ($request->asignado_id) {
            Notificacion::create([
                'user_id' => $request->asignado_id,
                'tarea_id' => $tarea->id,
                'mensaje' => "Se te ha asignado una nueva tarea: {$tarea->titulo}"
            ]);
        }

        return redirect()->route('tareas.index')->with('success', 'Tarea creada correctamente.');
    }

    public function edit(Tarea $tarea)
    {
        $usuarios = User::all();
        return view('tareas.edit', compact('tarea', 'usuarios'));
    }

    public function update(Request $request, Tarea $tarea)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_vencimiento' => 'required|date',
            'estado' => 'required|in:pendiente,en_progreso,completada',
            'asignado_id' => 'nullable|exists:users,id'
        ]);

        $tarea->update($request->all());

        if ($request->asignado_id && $request->asignado_id != $tarea->asignado_id) {
            Notificacion::create([
                'user_id' => $request->asignado_id,
                'tarea_id' => $tarea->id,
                'mensaje' => "Se te ha asignado la tarea: {$tarea->titulo}"
            ]);
        }

        return redirect()->route('tareas.index')->with('success', 'Tarea actualizada correctamente.');
    }

    public function destroy(Tarea $tarea)
    {
        $tarea->delete();
        return redirect()->route('tareas.index')->with('success', 'Tarea eliminada correctamente.');
    }

    public function asignar(Request $request, Tarea $tarea)
    {
        $request->validate([
            'asignado_id' => 'required|exists:users,id'
        ]);

        $tarea->update(['asignado_id' => $request->asignado_id]);

        Notificacion::create([
            'user_id' => $request->asignado_id,
            'tarea_id' => $tarea->id,
            'mensaje' => "Se te ha asignado una tarea: {$tarea->titulo}"
        ]);

        return redirect()->route('tareas.index')->with('success', 'Tarea asignada correctamente.');
    }

    public function calendario()
    {
        $tareas = Auth::user()->tareas;
        $eventos = $tareas->map(function ($tarea) {
            return [
                'id' => $tarea->id,
                'title' => $tarea->titulo,
                'start' => $tarea->fecha_vencimiento,
                'color' => $this->getColorForStatus($tarea->estado),
            ];
        });

        return response()->json($eventos);
    }

    public function calendarView()
    {
        $usuarios = User::all();
        return view('calendario.index', compact('usuarios'));
    }

    private function getColorForStatus($estado)
    {
        switch ($estado) {
            case 'pendiente':
                return '#ffc107'; // amarillo
            case 'en_progreso':
                return '#17a2b8'; // azul
            case 'completada':
                return '#28a745'; // verde
            default:
                return '#6c757d'; // gris
        }
    }

    public function reordenar(Request $request)
    {
        $tarea = Tarea::findOrFail($request->tarea_id);
        $nuevoIndice = $request->new_index;
        $nuevoEstado = $request->nuevo_estado;

        if ($tarea->estado !== $nuevoEstado) {
            $tarea->estado = $nuevoEstado;
        }

        $tareasDelMismoEstado = Tarea::where('creador_id', Auth::id())
                                     ->where('estado', $nuevoEstado)
                                     ->orderBy('orden')
                                     ->get();

        if ($nuevoIndice < count($tareasDelMismoEstado)) {
            $ordenObjetivo = $tareasDelMismoEstado[$nuevoIndice]->orden;
            Tarea::where('creador_id', Auth::id())
                 ->where('estado', $nuevoEstado)
                 ->where('orden', '>=', $ordenObjetivo)
                 ->increment('orden');
            $tarea->orden = $ordenObjetivo;
        } else {
            $maxOrden = $tareasDelMismoEstado->max('orden');
            $tarea->orden = $maxOrden + 1;
        }

        $tarea->save();

        return response()->json(['message' => 'Tarea actualizada con éxito']);
    }
}