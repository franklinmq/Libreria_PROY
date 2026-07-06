<?php

/**
 * Front Controller
 * Todas las peticiones pasan por aquí y se enrutan según ?action=
 */

require_once __DIR__ . '/../app/controllers/LibroController.php';
require_once __DIR__ . '/../app/controllers/CategoriaController.php';

$action = $_GET['action'] ?? 'libros';

try {
    switch ($action) {
        // ---- Libros ----
        case 'libros':
            (new LibroController())->index();
            break;

        case 'libro-nuevo':
            (new LibroController())->crear();
            break;

        case 'libro-guardar':
            (new LibroController())->guardar();
            break;

        case 'libro-editar':
            (new LibroController())->editar();
            break;

        case 'libro-actualizar':
            (new LibroController())->actualizar();
            break;

        case 'libro-ver':
            (new LibroController())->ver();
            break;

        case 'libro-eliminar':
            (new LibroController())->eliminar();
            break;

        // ---- Categorías ----
        case 'categorias':
            (new CategoriaController())->index();
            break;

        case 'categoria-guardar':
            (new CategoriaController())->guardar();
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
