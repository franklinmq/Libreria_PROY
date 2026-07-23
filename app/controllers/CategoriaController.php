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
        $parent_id = !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;

        if ($nombre !== '') {
            $this->categoriaModel->crear($nombre, $parent_id);
        }

        $this->redirect('index.php?action=categorias&msg=creado');
    }

    public function guardarAjax(): void
    {
        header('Content-Type: application/json');
        
        $nombre = trim($_POST['nombre'] ?? '');
        $parent_id = !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;

        if ($nombre === '') {
            echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio']);
            return;
        }

        try {
            $id = $this->categoriaModel->crear($nombre, $parent_id);
            if ($id) {
                echo json_encode(['success' => true, 'id' => $id, 'nombre' => $nombre, 'parent_id' => $parent_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al guardar la categoría (posible duplicado)']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function actualizar(): void
    {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $parent_id = !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;

        if ($id > 0 && $nombre !== '') {
            $this->categoriaModel->actualizar($id, $nombre, $parent_id);
            $this->redirect('index.php?action=categorias&msg=actualizado');
        } else {
            $this->redirect('index.php?action=categorias&msg=error');
        }
    }

    public function eliminar(): void
    {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

        if ($id > 0) {
            $eliminado = $this->categoriaModel->eliminar($id);
            if ($eliminado) {
                $this->redirect('index.php?action=categorias&msg=eliminado');
            } else {
                $this->redirect('index.php?action=categorias&msg=error_eliminar');
            }
        } else {
            $this->redirect('index.php?action=categorias&msg=error');
        }
    }
}
