<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Libro.php';
require_once __DIR__ . '/../models/Categoria.php';

class LibroController extends Controller
{
    private Libro $libroModel;
    private Categoria $categoriaModel;

    public function __construct()
    {
        $this->libroModel     = new Libro();
        $this->categoriaModel = new Categoria();
    }

    /** GET /?action=libros  -> Listado + buscador + dashboard */
    public function index(): void
    {
        $busqueda = $_GET['q'] ?? '';
        $libros   = $this->libroModel->obtenerTodos($busqueda);

        $this->render('libros/index', [
            'libros'    => $libros,
            'busqueda'  => $busqueda,
            'total'     => $this->libroModel->totalLibros(),
            'valorInv'  => $this->libroModel->valorInventario(),
            'stockBajo' => $this->libroModel->stockBajo(),
        ]);
    }

    /** GET /?action=libro-nuevo */
    public function crear(): void
    {
        $this->render('libros/create', [
            'categorias' => $this->categoriaModel->obtenerTodas(),
            'errores'    => [],
            'old'        => [],
        ]);
    }

    /** POST /?action=libro-guardar */
    public function guardar(): void
    {
        $datos = $this->validar($_POST);

        if (!empty($datos['errores'])) {
            $this->render('libros/create', [
                'categorias' => $this->categoriaModel->obtenerTodas(),
                'errores'    => $datos['errores'],
                'old'        => $_POST,
            ]);
            return;
        }

        $this->libroModel->crear($datos);
        $this->redirect('index.php?action=libros&msg=creado');
    }

    /** GET /?action=libro-editar&id=1 */
    public function editar(): void
    {
        $id    = (int) ($_GET['id'] ?? 0);
        $libro = $this->libroModel->obtenerPorId($id);

        if (!$libro) {
            $this->redirect('index.php?action=libros');
        }

        $this->render('libros/edit', [
            'libro'      => $libro,
            'categorias' => $this->categoriaModel->obtenerTodas(),
            'errores'    => [],
        ]);
    }

    /** POST /?action=libro-actualizar&id=1 */
    public function actualizar(): void
    {
        $id    = (int) ($_GET['id'] ?? 0);
        $datos = $this->validar($_POST);

        if (!empty($datos['errores'])) {
            $this->render('libros/edit', [
                'libro'      => array_merge(['id' => $id], $_POST),
                'categorias' => $this->categoriaModel->obtenerTodas(),
                'errores'    => $datos['errores'],
            ]);
            return;
        }

        $this->libroModel->actualizar($id, $datos);
        $this->redirect('index.php?action=libros&msg=actualizado');
    }

    /** GET /?action=libro-ver&id=1 */
    public function ver(): void
    {
        $id    = (int) ($_GET['id'] ?? 0);
        $libro = $this->libroModel->obtenerPorId($id);

        if (!$libro) {
            $this->redirect('index.php?action=libros');
        }

        $this->render('libros/show', ['libro' => $libro]);
    }

    /** POST /?action=libro-eliminar&id=1 */
    public function eliminar(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $this->libroModel->eliminar($id);
        $this->redirect('index.php?action=libros&msg=eliminado');
    }

    private function validar(array $datos): array
    {
        $errores = [];

        if (trim($datos['titulo'] ?? '') === '') {
            $errores['titulo'] = 'El título es obligatorio.';
        }
        if (trim($datos['autor'] ?? '') === '') {
            $errores['autor'] = 'El autor es obligatorio.';
        }
        if (trim($datos['isbn'] ?? '') === '') {
            $errores['isbn'] = 'El ISBN es obligatorio.';
        }
        if (!is_numeric($datos['precio'] ?? '') || (float) $datos['precio'] < 0) {
            $errores['precio'] = 'El precio debe ser un número válido.';
        }
        if (!is_numeric($datos['stock'] ?? '') || (int) $datos['stock'] < 0) {
            $errores['stock'] = 'El stock debe ser un número entero válido.';
        }

        return [
            'titulo'       => trim($datos['titulo'] ?? ''),
            'autor'        => trim($datos['autor'] ?? ''),
            'isbn'         => trim($datos['isbn'] ?? ''),
            'categoria_id' => $datos['categoria_id'] ?? null,
            'precio'       => $datos['precio'] ?? 0,
            'stock'        => $datos['stock'] ?? 0,
            'editorial'    => trim($datos['editorial'] ?? ''),
            'errores'      => $errores,
        ];
    }
}
