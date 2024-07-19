<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $fillable = [
        'user_id',
        'tarea_id',
        'mensaje',
        'leida'
    ];

    protected $casts = [
        'leida' => 'boolean',
    ];

    /**
     * Obtiene el usuario al que pertenece esta notificación.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene la tarea asociada a esta notificación.
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    /**
     * Scope para obtener solo las notificaciones no leídas.
     */
    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    /**
     * Marca la notificación como leída.
     */
    public function marcarComoLeida()
    {
        $this->leida = true;
        $this->save();
    }

    /**
     * Obtiene el tiempo transcurrido desde que se creó la notificación en formato legible.
     */
    public function getTiempoTranscurridoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}