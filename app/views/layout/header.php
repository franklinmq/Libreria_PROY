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
<body>

<nav class="navbar navbar-expand-lg navbar-dark app-navbar sticky-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php?action=libros">
            <i class="bi bi-book-half fs-4"></i>
            <span>Librería <strong>Inventario</strong></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto gap-2">
                <li class="nav-item">
                    <a class="nav-link <?= ($_GET['action'] ?? '') === 'libros' ? 'active' : '' ?>" href="index.php?action=libros">
                        <i class="bi bi-grid me-1"></i> Inventario
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($_GET['action'] ?? '') === 'categorias' ? 'active' : '' ?>" href="index.php?action=categorias">
                        <i class="bi bi-tags me-1"></i> Categorías
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-add ms-lg-2" href="index.php?action=libro-nuevo">
                        <i class="bi bi-plus-circle me-1"></i> Nuevo libro
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container-fluid px-4 py-4">
