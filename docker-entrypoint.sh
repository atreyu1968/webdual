#!/bin/bash

# Esperar a que MySQL esté listo
until nc -z -v -w30 db 3306
do
  echo "Esperando a que la base de datos esté lista..."
  sleep 5
done

echo "La base de datos está lista!"

# Correr migraciones
php artisan migrate

# Generar clave de aplicación si no existe
php artisan key:generate

# Iniciar PHP-FPM
php-fpm