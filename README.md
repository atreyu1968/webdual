
WebDual es una aplicación web diseñada para la gestión de Formación Profesional Dual en España. Permite a los usuarios gestionar tareas, asignaciones, y seguimiento de progreso tanto para estudiantes como para tutores.
Características principales

Gestión de tareas con asignación a usuarios
Calendario interactivo para visualización de tareas
Sistema de notificaciones para tareas próximas a vencer
Interfaz responsive para acceso desde cualquier dispositivo

Requisitos

PHP 8.0 o superior
Composer
MySQL 5.7 o superior
Node.js y npm

Instalación

Clonar el repositorio:
Copygit clone https://github.com/tu-usuario/webdual.git
cd webdual

Instalar dependencias de PHP:
Copycomposer install

Instalar dependencias de JavaScript:
Copynpm install

Copiar el archivo de entorno y configurarlo:
Copycp .env.example .env
Edita el archivo .env con tus configuraciones de base de datos y otras configuraciones necesarias.
Generar la clave de la aplicación:
Copyphp artisan key:generate

Ejecutar las migraciones:
Copyphp artisan migrate

Compilar los assets:
Copynpm run dev

Iniciar el servidor de desarrollo:
Copyphp artisan serve


Visita http://localhost:8000 en tu navegador para ver la aplicación.


