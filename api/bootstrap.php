<?php

// Configuración básica para el entorno Vercel
$basePath = dirname(__DIR__);

// Crear directorios temporales necesarios
$dirs = [
    '/tmp/storage/app',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Configurar variables de entorno
$_ENV['APP_ENV'] = 'production';
$_ENV['APP_DEBUG'] = false;
$_ENV['APP_URL'] = 'https://sanberapp.vercel.app';
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = '/tmp/database.sqlite';
$_ENV['LOG_CHANNEL'] = 'stderr';
$_ENV['CACHE_DRIVER'] = 'array';
$_ENV['SESSION_DRIVER'] = 'cookie';
$_ENV['SESSION_SECURE_COOKIE'] = true;
$_ENV['FILESYSTEM_DISK'] = 'local';
$_ENV['APP_KEY'] = 'base64:GmauFzNGB+2tmGbI3cnZdp7YrXSumofNogctszwa378=';

// Para solicitudes de archivos estáticos
$uri = $_SERVER['REQUEST_URI'] ?? '';
$staticExtensions = ['css', 'js', 'svg', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'json', 'txt', 'xml'];

foreach ($staticExtensions as $ext) {
    if (preg_match('/\.' . $ext . '$/i', $uri)) {
        $file = $basePath . '/public' . $uri;
        if (file_exists($file)) {
            // Establecer el tipo MIME adecuado
            $mimeTypes = [
                'css' => 'text/css',
                'js' => 'application/javascript',
                'svg' => 'image/svg+xml',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'ico' => 'image/x-icon',
                'json' => 'application/json',
                'txt' => 'text/plain',
                'xml' => 'application/xml',
            ];
            
            $contentType = $mimeTypes[$ext] ?? 'text/plain';
            header('Content-Type: ' . $contentType);
            readfile($file);
            exit;
        }
        
        http_response_code(404);
        echo 'File not found';
        exit;
    }
}
