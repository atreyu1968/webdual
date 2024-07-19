FROM php:8.1-fpm

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Obtener Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar el código existente de la aplicación
COPY . /var/www

# Instalar dependencias
RUN composer install

# Cambiar propietario de los archivos
RUN chown -R www-data:www-data /var/www

# Exponer puerto 9000 y iniciar php-fpm
EXPOSE 9000
CMD ["php-fpm"]