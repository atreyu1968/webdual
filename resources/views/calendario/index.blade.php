@extends('layouts.app')

@section('title', 'Calendario de Tareas')

@section('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />
<style>
    #calendar {
        max-width: 1100px;
        margin: 0 auto;
    }
    .fc-event {
        cursor: pointer;
    }
    .priority-high { border-left: 5px solid #dc3545; }
    .priority-medium { border-left: 5px solid #ffc107; }
    .priority-low { border-left: 5px solid #28a745; }
</style>
@endsection

@section('content')
<div class="container">
    <h2 class="mb-4">Calendario de Tareas</h2>
    <div id="calendar"></div>
</div>

<!-- Modal para detalles de tarea -->
<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">Detalles de la Tarea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 id="taskTitle"></h4>
                <p id="taskDescription"></p>
                <p><strong>Estado:</strong> <span id="taskStatus"></span></p>
                <p><strong>Prioridad:</strong> <span id="taskPriority"></span></p>
                <p><strong>Asignado a:</strong> <span id="taskAssignee"></span></p>
                <p><strong>Fecha de vencimiento:</strong> <span id="taskDueDate"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <a href="#" id="editTaskLink" class="btn btn-primary">Editar Tarea</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/locales-all.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locale: 'es',
        events: '/api/tareas',
        editable: true,
        selectable: true,
        select: function(info) {
            // Aquí puedes abrir un modal para crear una nueva tarea
            window.location.href = '/tareas/create?fecha=' + info.startStr;
        },
        eventClick: function(info) {
            // Mostrar detalles de la tarea en el modal
            $('#taskTitle').text(info.event.title);
            $('#taskDescription').text(info.event.extendedProps.description);
            $('#taskStatus').text(info.event.extendedProps.status);
            $('#taskPriority').text(info.event.extendedProps.priority);
            $('#taskAssignee').text(info.event.extendedProps.assignee);
            $('#taskDueDate').text(info.event.start.toLocaleDateString());
            $('#editTaskLink').attr('href', '/tareas/' + info.event.id + '/edit');
            $('#taskModal').modal('show');
        },
        eventDrop: function(info) {
            // Actualizar la fecha de la tarea cuando se arrastra
            $.ajax({
                url: '/api/tareas/' + info.event.id,
                method: 'PATCH',
                data: {
                    fecha_vencimiento: info.event.start.toISOString().split('T')[0]
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        },
        eventClassNames: function(arg) {
            // Asignar clase según la prioridad
            return ['priority-' + arg.event.extendedProps.priority];
        }
    });
    calendar.render();
});
</script>
@endsection