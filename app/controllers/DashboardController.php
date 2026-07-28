<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Articulo.php';
require_once __DIR__ . '/../models/Categoria.php';

class DashboardController extends Controller
{
    public function index(): void
    {
        $articuloModel = new Articulo();
        $categoriaModel = new Categoria();

        $totalArticulos = count($articuloModel->obtenerTodos());
        $totalCategorias = count($categoriaModel->obtenerTodas());
        
        // Mock data para ventas de los últimos 7 días
        $ventasDias = [
            'Lunes' => 1200,
            'Martes' => 1500,
            'Miércoles' => 900,
            'Jueves' => 2100,
            'Viernes' => 1800,
            'Sábado' => 2500,
            'Domingo' => 3000
        ];

        $this->render('dashboard/index', [
            'totalArticulos' => $totalArticulos,
            'totalCategorias' => $totalCategorias,
            'ventasDias' => $ventasDias
        ]);
    }
}
