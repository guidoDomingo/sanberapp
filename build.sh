#!/bin/bash

# Script de compilación para Vercel

# Instalar dependencias de Composer
composer install --no-dev --optimize-autoloader

# Instalar dependencias de Node.js
npm ci

# Compilar assets
npm run build

# Copiar la base de datos SQLite
if [ -f "database/database.sqlite" ]; then
  cp database/database.sqlite /tmp/database.sqlite
fi

# Generar clave de la aplicación si no existe
php artisan key:generate --force

# Caché de configuración para mejor rendimiento
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Construcción completada exitosamente"
