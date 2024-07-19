<?php
// app/Console/Commands/VerificarTareasPorVencer.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tarea;
use App\Models\Notificacion;
use Carbon\Carbon;

class VerificarTareasPorVencer extends Command
{
    protected $signature = 'tareas:verificar-vencimiento';
    protected $description = 'Verifica las tareas próximas a vencer y crea notificaciones';

    public function handle()
    {
        $fechaLimite = Carbon::now()->addDays(3);

        $tareasPorVencer = Tarea::where('estado', '!=', 'completada')
            ->where('fecha_vencimiento', '<=', $fechaLimite)
            ->where('fecha_vencimiento', '>=', Carbon::now())
            ->get();

        foreach ($tareasPorVencer as $tarea) {
            $diasRestantes = Carbon::now()->diffInDays($tarea->fecha_vencimiento, false);
            
            $mensaje = "La tarea '{$tarea->titulo}' vence en $diasRestantes días.";

            Notificacion::updateOrCreate(
                ['user_id' => $tarea->user_id, 'tarea_id' => $tarea->id],
                ['mensaje' => $mensaje, 'leida' => false]
            );
        }

        $this->info('Verificación de tareas completada. Se crearon ' . $tareasPorVencer->count() . ' notificaciones.');
    }
}