#!/bin/bash

# Asegurar que los permisos sean correctos
chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache

# Instalar dependencias de Composer
composer install --no-interaction --optimize-autoloader

# Esperar por la base de datos
echo "Esperando a que la base de datos esté lista..."
until php -r "try { new PDO('mysql:host=db;dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); echo 'OK'; } catch (PDOException \$e) { echo \$e->getMessage(); sleep(1); }" | grep -q "OK"; do
    echo "Esperando a que la base de datos esté lista..."
    sleep 1
done

# Ejecutar migraciones y seeders
php artisan migrate --force
php artisan db:seed --force

# Limpiar y optimizar
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlace simbólico para storage
php artisan storage:link

exec "$@"
