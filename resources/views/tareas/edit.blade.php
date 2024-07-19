@extends('layouts.app')

@section('title', 'Editar Tarea')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
    .select2-container .select2-selection--single {
        height: 38px;
        line-height: 38px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h2>Editar Tarea</h2>
    <form action="{{ route('tareas.update', $tarea->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" required value="{{ old('titulo', $tarea->titulo) }}">
            @error('titulo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $tarea->descripcion) }}</textarea>
            @error('descripcion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="fecha_vencimiento">Fecha de Vencimiento</label>
            <input type="text" class="form-control datepicker @error('fecha_vencimiento') is-invalid @enderror" id="fecha_vencimiento" name="fecha_vencimiento" required value="{{ old('fecha_vencimiento', $tarea->fecha_vencimiento->format('Y-m-d')) }}">
            @error('fecha_vencimiento')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="estado">Estado</label>
            <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                <option value="pendiente" {{ old('estado', $tarea->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="en_progreso" {{ old('estado', $tarea->estado) == 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
                <option value="completada" {{ old('estado', $tarea->estado) == 'completada' ? 'selected' : '' }}>Completada</option>
            </select>
            @error('estado')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="prioridad">Prioridad</label>
            <select class="form-control @error('prioridad') is-invalid @enderror" id="prioridad" name="prioridad" required>
                <option value="baja" {{ old('prioridad', $tarea->prioridad) == 'baja' ? 'selected' : '' }}>Baja</option>
                <option value="media" {{ old('prioridad', $tarea->prioridad) == 'media' ? 'selected' : '' }}>Media</option>
                <option value="alta" {{ old('prioridad', $tarea->prioridad) == 'alta' ? 'selected' : '' }}>Alta</option>
            </select>
            @error('prioridad')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="asignado_id">Asignar a</label>
            <select class="form-control select2 @error('asignado_id') is-invalid @enderror" id="asignado_id" name="asignado_id">
                <option value="">Sin asignar</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}" {{ old('asignado_id', $tarea->asignado_id) == $usuario->id ? 'selected' : '' }}>{{ $usuario->name }}</option>
                @endforeach
            </select>
            @error('asignado_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="comentarios">Comentarios</label>
            <textarea class="form-control @error('comentarios') is-invalid @enderror" id="comentarios" name="comentarios" rows="3">{{ old('comentarios', $tarea->comentarios) }}</textarea>
            @error('comentarios')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Tarea</button>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.datepicker').flatpickr({
            dateFormat: "Y-m-d",
            minDate: "today"
        });

        $('.select2').select2({
            placeholder: "Seleccionar usuario",
            allowClear: true
        });
    });
</script>
@endsection