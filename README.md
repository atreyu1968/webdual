WebDual
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


# webdual/
├── app/
│   ├── Console/
│   │   ├── Commands/
│   │   │   └── VerificarTareasPorVencer.php
│   │   └── Kernel.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── NotificacionController.php
│   │   │   └── TareaController.php
│   │   └── Middleware/
│   │       └── AuthMiddleware.php
│   ├── Models/
│   │   ├── Notificacion.php
│   │   ├── Tarea.php
│   │   └── User.php
│   └── Providers/
├── config/
│   ├── app.php
│   └── database.php
├── database/
│   └── migrations/
│       ├── xxxx_xx_xx_create_users_table.php
│       ├── xxxx_xx_xx_create_tareas_table.php
│       ├── xxxx_xx_xx_create_notificaciones_table.php
│       └── xxxx_xx_xx_update_tareas_table_add_assignment.php
├── public/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   └── app.js
│   └── index.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   ├── tareas/
│   │   │   ├── create.blade.php
│   │   │   ├── edit.blade.php
│   │   │   ├── index.blade.php
│   │   │   └── lista.blade.php
│   │   ├── notificaciones/
│   │   │   └── index.blade.php
│   │   └── calendario/
│   │       └── index.blade.php
│   └── lang/
│       └── es/
│           └── validation.php
├── routes/
│   ├── api.php
│   └── web.php
├── tests/
├── vendor/
├── .env.example
├── .gitignore
├── composer.json
├── package.json
└── README.md


 
