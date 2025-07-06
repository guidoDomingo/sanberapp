<?php

// Cargar configuración específica para Vercel
require_once __DIR__ . '/bootstrap.php';

// Verificar si existe el archivo de la base de datos
$dbFile = '/tmp/database.sqlite';
if (!file_exists($dbFile)) {
    // Si no existe, intentamos copiarlo del directorio del proyecto
    if (file_exists(__DIR__ . '/../database/database.sqlite')) {
        copy(__DIR__ . '/../database/database.sqlite', $dbFile);
        chmod($dbFile, 0777);
    } else {
        // Si no podemos encontrar la base de datos, mostramos un error
        http_response_code(500);
        echo "Error: No se pudo encontrar la base de datos SQLite";
        exit;
    }
}

// Manejador de aplicación simplificado para Vercel
try {
    // Si se solicita la página principal, mostramos la interfaz de búsqueda
    if ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '') {
        include __DIR__ . '/views/index.php';
        exit;
    }
    
    // Manejo de la ruta /beneficiarios
    if (preg_match('/^\/beneficiarios(\/.*)?$/', $_SERVER['REQUEST_URI'])) {
        include __DIR__ . '/views/index.php';
        exit;
    }
    
    // Manejo de la búsqueda por cédula
    if (isset($_GET['cedula'])) {
        $cedula = $_GET['cedula'];
        
        // Abrir conexión a SQLite
        $db = new PDO('sqlite:' . $dbFile);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Buscar beneficiario por cédula
        $stmt = $db->prepare("SELECT * FROM beneficiarios WHERE cedula = ?");
        $stmt->execute([$cedula]);
        $beneficiario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Devolver resultado en formato JSON
        header('Content-Type: application/json');
        echo json_encode(['encontrado' => !empty($beneficiario), 'data' => $beneficiario]);
        exit;
    }
    
    // Si no coincide con ninguna ruta conocida, mostrar error 404
    http_response_code(404);
    echo "Página no encontrada";
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
