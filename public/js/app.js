// Asegurarse de que el documento esté completamente cargado antes de ejecutar el script
document.addEventListener('DOMContentLoaded', function() {
    // Inicialización de componentes y funcionalidades

    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Manejar el envío de formularios de manera asíncrona
    document.querySelectorAll('form[data-remote]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            fetch(this.action, {
                method: this.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Éxito', data.message, 'success');
                } else {
                    showNotification('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error', 'Ha ocurrido un error inesperado', 'error');
            });
        });
    });

    // Función para mostrar notificaciones
    function showNotification(title, message, type) {
        // Aquí puedes usar una librería de notificaciones o crear tu propia implementación
        alert(`${title}: ${message}`);
    }

    // Manejar clicks en elementos con la clase 'confirm-action'
    document.querySelectorAll('.confirm-action').forEach(element => {
        element.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de que quieres realizar esta acción?')) {
                e.preventDefault();
            }
        });
    });

    // Inicializar DatePicker en campos de fecha
    document.querySelectorAll('.datepicker').forEach(element => {
        new Pikaday({
            field: element,
            format: 'DD/MM/YYYY'
        });
    });

    // Función para actualizar la barra de progreso de tareas
    function updateTaskProgress() {
        let completedTasks = document.querySelectorAll('.task-item.completed').length;
        let totalTasks = document.querySelectorAll('.task-item').length;
        let progressPercentage = (completedTasks / totalTasks) * 100;
        let progressBar = document.querySelector('#taskProgressBar');
        if (progressBar) {
            progressBar.style.width = progressPercentage + '%';
            progressBar.setAttribute('aria-valuenow', progressPercentage);
            progressBar.textContent = progressPercentage.toFixed(1) + '%';
        }
    }

    // Llamar a updateTaskProgress inicialmente y después de cualquier cambio en las tareas
    updateTaskProgress();

    // Manejar la carga dinámica de contenido
    function loadContent(url, targetId) {
        fetch(url)
            .then(response => response.text())
            .then(html => {
                document.getElementById(targetId).innerHTML = html;
            })
            .catch(error => {
                console.error('Error cargando contenido:', error);
            });
    }

    // Ejemplo de uso: loadContent('/tareas/lista', 'taskList');

    // Inicializar notificaciones en tiempo real (ejemplo con Pusher)
    // Asegúrate de tener Pusher configurado en tu backend
    /*
    const pusher = new Pusher('YOUR_PUSHER_KEY', {
        cluster: 'eu',
        encrypted: true
    });

    const channel = pusher.subscribe('notifications');
    channel.bind('new-notification', function(data) {
        showNotification('Nueva notificación', data.message, 'info');
    });
    */
});