<?php

// Cargar configuración específica para Vercel
require_once __DIR__ . '/vercel-php-runtime.php';

// Ajustar rutas para Vercel
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';

// Incluir el archivo index.php original
require __DIR__ . '/../public/index.php';
