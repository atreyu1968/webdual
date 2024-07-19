<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarea extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_vencimiento',
        'estado',
        'creador_id',
        'asignado_id',
        'orden'
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
    ];

    /**
     * Obtiene el usuario que creó la tarea.
     */
    public function creador()
    {
        return $this->belongsTo(User::class, 'creador_id');
    }

    /**
     * Obtiene el usuario asignado a la tarea.
     */
    public function asignado()
    {
        return $this->belongsTo(User::class, 'asignado_id');
    }

    /**
     * Obtiene las notificaciones asociadas a esta tarea.
     */
    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class);
    }

    /**
     * Scope para obtener tareas pendientes.
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para obtener tareas en progreso.
     */
    public function scopeEnProgreso($query)
    {
        return $query->where('estado', 'en_progreso');
    }

    /**
     * Scope para obtener tareas completadas.
     */
    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    /**
     * Verifica si la tarea está vencida.
     */
    public function getEstaVencidaAttribute()
    {
        return $this->fecha_vencimiento->isPast() && $this->estado !== 'completada';
    }

    /**
     * Obtiene el color asociado al estado de la tarea.
     */
    public function getColorEstadoAttribute()
    {
        switch ($this->estado) {
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

    /**
     * Cambia el estado de la tarea.
     */
    public function cambiarEstado($nuevoEstado)
    {
        if (in_array($nuevoEstado, ['pendiente', 'en_progreso', 'completada'])) {
            $this->estado = $nuevoEstado;
            $this->save();

            // Crear una notificación si la tarea está asignada
            if ($this->asignado_id) {
                Notificacion::create([
                    'user_id' => $this->asignado_id,
                    'tarea_id' => $this->id,
                    'mensaje' => "La tarea '{$this->titulo}' ha cambiado a estado {$nuevoEstado}."
                ]);
            }

            return true;
        }
        return false;
    }
}