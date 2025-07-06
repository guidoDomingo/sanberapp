<?php
// Configuración específica para el runtime de PHP en Vercel

// Configurar directorio raíz
$_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/public';

// Establecer los directorios de escritura temporales
$_ENV['APP_STORAGE'] = '/tmp/app';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_ENV['CACHE_PATH'] = '/tmp/storage/framework/cache';
$_ENV['LOG_PATH'] = '/tmp/storage/logs';
$_ENV['SESSION_PATH'] = '/tmp/storage/framework/sessions';

// Crear directorios necesarios
$directories = [
    $_ENV['APP_STORAGE'],
    $_ENV['VIEW_COMPILED_PATH'],
    $_ENV['CACHE_PATH'],
    $_ENV['LOG_PATH'],
    $_ENV['SESSION_PATH']
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
}
