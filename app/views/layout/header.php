<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario Librería</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="d-flex flex-row min-vh-100">
    <!-- Sidebar -->
    <aside class="app-sidebar d-flex flex-column flex-shrink-0 p-3 text-white">
        <a href="index.php?action=libros" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none px-2 gap-2">
            <i class="bi bi-book-half fs-3" style="color: var(--brand-accent);"></i>
            <span class="fs-5 fw-bold">Sistema <strong>Librería</strong></span>
        </a>
        <hr style="border-color: rgba(255,255,255,0.2);">
        <ul class="nav nav-pills flex-column mb-auto gap-1">
            <li class="nav-item">
                <a href="index.php?action=libros" class="nav-link <?= ($_GET['action'] ?? '') === 'libros' ? 'active' : '' ?>" aria-current="page">
                    <i class="bi bi-grid me-2"></i> Inventario
                </a>
            </li>
            <li>
                <a href="index.php?action=categorias" class="nav-link <?= ($_GET['action'] ?? '') === 'categorias' ? 'active' : '' ?>">
                    <i class="bi bi-tags me-2"></i> Categorías
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <i class="bi bi-cart-plus me-2"></i> Compras
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <i class="bi bi-cash-stack me-2"></i> Ventas
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <i class="bi bi-bar-chart me-2"></i> Reportes
                </a>
            </li>
        </ul>
        <hr style="border-color: rgba(255,255,255,0.2);">
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-4 me-2"></i>
                <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser">
                <li><a class="dropdown-item" href="#">Configuración</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="index.php?action=login">Cerrar Sesión</a></li>
            </ul>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="d-flex flex-column flex-grow-1" style="min-width: 0; background-color: var(--bg-soft);">
        <header class="app-topbar py-3 px-4 d-flex justify-content-between align-items-center border-bottom bg-white sticky-top">
            <h5 class="mb-0 text-dark fw-bold" style="color: var(--brand-primary) !important;">Panel de Control</h5>
            <!-- Botón de acción principal visible en todas partes -->
            <a class="btn btn-add" href="index.php?action=libro-nuevo">
                <i class="bi bi-plus-circle me-1"></i> Añadir Material
            </a>
        </header>

        <main class="container-fluid px-4 py-4 flex-grow-1">
