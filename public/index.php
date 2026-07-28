<?php

/**
 * Front Controller
 * Todas las peticiones pasan por aquí y se enrutan según ?action=
 */

require_once __DIR__ . '/../app/controllers/ArticuloController.php';
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
        case 'articulos':
            (new ArticuloController())->index();
            break;

        case 'articulo-nuevo':
            (new ArticuloController())->crear();
            break;

        case 'articulo-guardar':
            (new ArticuloController())->guardar();
            break;

        case 'articulo-editar':
            (new ArticuloController())->editar();
            break;

        case 'articulo-actualizar':
            (new ArticuloController())->actualizar();
            break;

        case 'articulo-ver':
            (new ArticuloController())->ver();
            break;

        case 'articulo-eliminar':
            (new ArticuloController())->eliminar();
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

        case 'categoria-actualizar':
            (new CategoriaController())->actualizar();
            break;

        case 'categoria-eliminar':
            (new CategoriaController())->eliminar();
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
