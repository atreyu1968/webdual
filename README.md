
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
Copygit clone https://github.com/atrey1968/webdual.git
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



Para instalar tu aplicación WebDual desde GitHub en un contenedor Docker Compose, seguiremos estos pasos:

Crear un archivo Dockerfile
Crear un archivo docker-compose.yml
Configurar los servicios necesarios (PHP, MySQL, Nginx)
Ajustar la configuración de la aplicación para trabajar con Docker
Crear scripts de inicialización

Vamos a crear estos archivos:
Dockerfile para WebDualClick to open text
Ahora, creemos el archivo docker-compose.yml:
docker-compose.yml para WebDualClick to open text
Ahora, necesitamos crear una configuración de Nginx para nuestro servidor web:
Configuración de Nginx para WebDualClick to open text
Guarda este archivo en nginx/conf.d/app.conf.
Ahora, vamos a crear un script de inicialización para configurar la aplicación cuando se inicie el contenedor:
Script de entrada para WebDualClick to open text
Guarda este archivo como docker-entrypoint.sh en la raíz de tu proyecto y asegúrate de que sea ejecutable:
bashCopychmod +x docker-entrypoint.sh
Finalmente, actualiza tu archivo .env para que use la configuración de Docker:
Archivo .env para WebDual en DockerClick to open text
Para ejecutar tu aplicación:

Asegúrate de que Docker y Docker Compose estén instalados en tu sistema.
Navega hasta el directorio de tu proyecto.
Ejecuta:
Copydocker-compose up -d


Esto construirá las imágenes necesarias y iniciará los contenedores.
Para acceder a tu aplicación, abre un navegador y ve a http://localhost.
Recuerda que la primera vez que ejecutes los contenedores, puede llevar algún tiempo mientras se construyen las imágenes y se instalan las dependencias.



