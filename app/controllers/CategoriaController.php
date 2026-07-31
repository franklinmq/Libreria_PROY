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
        $id = !empty($_POST['id']) ? (int) $_POST['id'] : 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $subcategorias = isset($_POST['subcategorias']) && is_array($_POST['subcategorias']) ? $_POST['subcategorias'] : [];

        if ($nombre !== '') {
            if ($id > 0) {
                // Modo Edición
                $this->categoriaModel->actualizar($id, $nombre, null);
                
                // Sincronizar subcategorías
                $todas = $this->categoriaModel->obtenerTodas();
                $actuales = array_filter($todas, fn($c) => $c['parent_id'] == $id);
                $nombresActuales = array_column($actuales, 'nombre', 'id');
                
                // Insertar nuevas
                foreach ($subcategorias as $sub_nombre) {
                    $sub_nombre = trim($sub_nombre);
                    if ($sub_nombre !== '' && !in_array($sub_nombre, $nombresActuales)) {
                        $this->categoriaModel->crear($sub_nombre, $id);
                    }
                }
                
                // Eliminar las que ya no están
                foreach ($nombresActuales as $sub_id => $sub_nombre) {
                    if (!in_array($sub_nombre, $subcategorias)) {
                        $this->categoriaModel->eliminar($sub_id);
                    }
                }
            } else {
                // Modo Creación
                $nuevoId = $this->categoriaModel->crear($nombre, null);
                if ($nuevoId && !empty($subcategorias)) {
                    foreach ($subcategorias as $sub_nombre) {
                        $sub_nombre = trim($sub_nombre);
                        if ($sub_nombre !== '') {
                            $this->categoriaModel->crear($sub_nombre, $nuevoId);
                        }
                    }
                }
            }
        }

        $this->redirect('index.php?action=categorias&msg=guardado');
    }

    public function guardarAjax(): void
    {
        header('Content-Type: application/json');
        
        $nombre = trim($_POST['nombre'] ?? '');
        $parent_id = !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;
        $subcategorias = isset($_POST['subcategorias']) && is_array($_POST['subcategorias']) ? $_POST['subcategorias'] : [];

        if ($nombre === '') {
            echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio']);
            return;
        }

        try {
            $id = $this->categoriaModel->crear($nombre, $parent_id);
            if ($id) {
                $creadas = [];
                // Solo crear subcategorías si estamos creando una categoría principal
                if (empty($parent_id) && !empty($subcategorias)) {
                    foreach ($subcategorias as $sub_nombre) {
                        $sub_nombre = trim($sub_nombre);
                        if ($sub_nombre !== '') {
                            $sub_id = $this->categoriaModel->crear($sub_nombre, $id);
                            if ($sub_id) {
                                $creadas[] = ['id' => $sub_id, 'nombre' => $sub_nombre];
                            }
                        }
                    }
                }
                echo json_encode(['success' => true, 'id' => $id, 'nombre' => $nombre, 'parent_id' => $parent_id, 'subcategorias' => $creadas]);
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
