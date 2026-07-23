<?php

/**
 * Front Controller
 * Todas las peticiones pasan por aquí y se enrutan según ?action=
 */

require_once __DIR__ . '/../app/controllers/ProductoController.php';
require_once __DIR__ . '/../app/controllers/CategoriaController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/MarcaController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';

$action = $_GET['action'] ?? 'login'; // Defaults to login if no action is provided

try {
    switch ($action) {
        // ---- Auth ----
        case 'login':
            (new AuthController())->login();
            break;
            
        case 'process-login':
            (new AuthController())->processLogin();
            break;

        case 'register':
            (new AuthController())->register();
            break;

        case 'process-register':
            (new AuthController())->processRegister();
            break;

        // ---- Dashboard ----
        case 'dashboard':
            (new DashboardController())->index();
            break;

        // ---- Productos ----
        case 'productos':
            (new ProductoController())->index();
            break;

        case 'producto-nuevo':
            (new ProductoController())->crear();
            break;

        case 'producto-guardar':
            (new ProductoController())->guardar();
            break;

        case 'producto-editar':
            (new ProductoController())->editar();
            break;

        case 'producto-actualizar':
            (new ProductoController())->actualizar();
            break;

        case 'producto-ver':
            (new ProductoController())->ver();
            break;

        case 'producto-eliminar':
            (new ProductoController())->eliminar();
            break;

        // ---- Categorías ----
        case 'categorias':
            (new CategoriaController())->index();
            break;

        case 'categoria-guardar':
            (new CategoriaController())->guardar();
            break;

        case 'categoria-guardar-ajax':
            (new CategoriaController())->guardarAjax();
            break;

        case 'marca-guardar-ajax':
            (new MarcaController())->guardarAjax();
            break;

        default:
            http_response_code(404);
            echo '404 - Página no encontrada';
            break;
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Ocurrió un error: ' . htmlspecialchars($e->getMessage());
}
