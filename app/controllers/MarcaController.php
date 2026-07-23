<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Marca.php';

class MarcaController extends Controller
{
    private Marca $marcaModel;

    public function __construct()
    {
        $this->marcaModel = new Marca();
    }

    public function guardarAjax(): void
    {
        header('Content-Type: application/json');
        
        $nombre = trim($_POST['nombre'] ?? '');
        
        if ($nombre === '') {
            echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio']);
            return;
        }

        try {
            $id = $this->marcaModel->crear($nombre);
            if ($id) {
                echo json_encode(['success' => true, 'id' => $id, 'nombre' => $nombre]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al guardar la marca (posible duplicado)']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
