<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Categoria.php';

class CategoriaController extends Controller
{
    private Categoria $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new Categoria();
    }

    public function index(): void
    {
        $this->render('categorias/index', [
            'categorias' => $this->categoriaModel->obtenerTodas(),
        ]);
    }

    public function guardar(): void
    {
        $nombre = trim($_POST['nombre'] ?? '');

        if ($nombre !== '') {
            $this->categoriaModel->crear($nombre);
        }

        $this->redirect('index.php?action=categorias&msg=creado');
    }
}
