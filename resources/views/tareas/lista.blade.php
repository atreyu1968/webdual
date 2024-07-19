@foreach($tareas as $tarea)
<div class="col-md-4 mb-4 task-item" data-estado="{{ $tarea->estado }}" data-prioridad="{{ $tarea->prioridad }}">
    <div class="card task-card priority-{{ $tarea->prioridad }}">
        <div class="card-body">
            <h5 class="card-title">{{ $tarea->titulo }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">Vencimiento: {{ $tarea->fecha_vencimiento->format('d/m/Y') }}</h6>
            <p class="card-text">{{ Str::limit($tarea->descripcion, 100) }}</p>
            <div class="mb-2">
                <span class="badge badge-{{ $tarea->estado == 'completada' ? 'success' : ($tarea->estado == 'en_progreso' ? 'warning' : 'danger') }}">
                    {{ ucfirst($tarea->estado) }}
                </span>
                <span class="badge badge-info">{{ ucfirst($tarea->prioridad) }}</span>
            </div>
            <p class="card-text"><small class="text-muted">Asignado a: {{ $tarea->asignado ? $tarea->asignado->name : 'Sin asignar' }}</small></p>
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('tareas.edit', $tarea->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de querer eliminar esta tarea?')">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@if($tareas->isEmpty())
<div class="col-12">
    <div class="alert alert-info" role="alert">
        No se encontraron tareas.
    </div>
</div>
@endif

@if(method_exists($tareas, 'links'))
<div class="col-12 d-flex justify-content-center">
    {{ $tareas->links() }}
</div>
@endif