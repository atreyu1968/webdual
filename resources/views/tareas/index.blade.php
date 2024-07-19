@extends('layouts.app')

@section('title', 'Tareas')

@section('styles')
<style>
    .task-card {
        transition: all 0.3s ease;
    }
    .task-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .priority-high { border-left: 5px solid #dc3545; }
    .priority-medium { border-left: 5px solid #ffc107; }
    .priority-low { border-left: 5px solid #28a745; }
</style>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Tareas</h2>
        <a href="{{ route('tareas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Tarea
        </a>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" id="searchTask" class="form-control" placeholder="Buscar tarea...">
        </div>
        <div class="col-md-3">
            <select id="filterStatus" class="form-control">
                <option value="">Todos los estados</option>
                <option value="pendiente">Pendiente</option>
                <option value="en_progreso">En Progreso</option>
                <option value="completada">Completada</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="filterPriority" class="form-control">
                <option value="">Todas las prioridades</option>
                <option value="alta">Alta</option>
                <option value="media">Media</option>
                <option value="baja">Baja</option>
            </select>
        </div>
    </div>

    <div class="row" id="taskList">
        @foreach($tareas as $tarea)
        <div class="col-md-4 mb-4 task-item" data-estado="{{ $tarea->estado }}" data-prioridad="{{ $tarea->prioridad }}">
            <div class="card task-card priority-{{ $tarea->prioridad }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $tarea->titulo }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Vencimiento: {{ $tarea->fecha_vencimiento->format('d/m/Y') }}</h6>
                    <p class="card-text">{{ Str::limit($tarea->descripcion, 100) }}</p>
                    <p>
                        <span class="badge badge-{{ $tarea->estado == 'completada' ? 'success' : ($tarea->estado == 'en_progreso' ? 'warning' : 'danger') }}">
                            {{ ucfirst($tarea->estado) }}
                        </span>
                        <span class="badge badge-info">{{ ucfirst($tarea->prioridad) }}</span>
                    </p>
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
    </div>

    <div class="d-flex justify-content-center">
        {{ $tareas->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    function filterTasks() {
        var searchText = $('#searchTask').val().toLowerCase();
        var statusFilter = $('#filterStatus').val();
        var priorityFilter = $('#filterPriority').val();

        $('.task-item').each(function() {
            var $task = $(this);
            var taskText = $task.text().toLowerCase();
            var taskStatus = $task.data('estado');
            var taskPriority = $task.data('prioridad');

            var matchesSearch = taskText.indexOf(searchText) > -1;
            var matchesStatus = statusFilter === '' || taskStatus === statusFilter;
            var matchesPriority = priorityFilter === '' || taskPriority === priorityFilter;

            if (matchesSearch && matchesStatus && matchesPriority) {
                $task.show();
            } else {
                $task.hide();
            }
        });
    }

    $('#searchTask, #filterStatus, #filterPriority').on('keyup change', filterTasks);
});
</script>
@endsection