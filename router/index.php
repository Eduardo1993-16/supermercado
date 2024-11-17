<?php

// CORS: Intercambio de Recursos de Origen Cruzado (CORS)
header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { // Manejo de preflight requests
    http_response_code(200); // Código de éxito para OPTIONS
    exit();
}

// Importación de controladores
require_once '../controllers/productosController.php';
require_once '../controllers/usuariosController.php';

// Obtener la URL solicitada
$url = str_replace('/api/router/index.php', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$id = $_GET['id'] ?? null; // Obtener el ID si está presente en la URL

// Crear instancias de los controladores
$productos = new productosController();
$usuarios = new usuariosController();

// Obtener el método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Enrutamiento
switch ($url) {
    // Rutas de productos
    case '/api/productos/CreateProducto':
        if ($method === 'POST') {
            $productos->CreateProducto();
        }
        break;

    case '/api/productos/getProductos':
        if ($method === 'GET') {
            $productos->getProductos();
        }
        break;

    case '/api/productos/updateProducto':
        if ($method === 'PUT') {
            $productos->updateProducto();
        }
        break;

    case '/api/productos/DeleteProducto':
        if ($method === 'DELETE') {
            if ($id) {
                $productos->deleteProducto($id);
            } else {
                http_response_code(400); // Error 400 si no se proporciona ID
                echo json_encode(['message' => 'ID requerido para eliminar el producto']);
            }
        }
        break;

    // Rutas de usuarios
    case '/api/usuarios/login':
        if ($method === 'POST') {
            $usuarios->login();
        }
        break;

    default:
        // Manejo de rutas no encontradas
        http_response_code(404);
        echo json_encode(['message' => 'Ruta no encontrada']);
        break;
}
