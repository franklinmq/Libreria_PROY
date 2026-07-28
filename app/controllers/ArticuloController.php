<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Articulo.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Marca.php';

class ArticuloController extends Controller
{
    private Articulo $articuloModel;
    private Categoria $categoriaModel;
    private Marca $marcaModel;

    public function __construct()
    {
        $this->articuloModel  = new Articulo();
        $this->categoriaModel = new Categoria();
        $this->marcaModel     = new Marca();
    }

    public function index(): void
    {
        $busqueda  = $_GET['q'] ?? '';
        $articulos = $this->articuloModel->obtenerTodos($busqueda);

        $this->render('articulos/index', [
            'articulos'  => $articulos,
            'busqueda'   => $busqueda,
            'total'      => $this->articuloModel->totalArticulos(),
            'valorInv'   => $this->articuloModel->valorInventario(),
            'stockBajo'  => $this->articuloModel->stockBajo(),
            'categorias' => $this->categoriaModel->obtenerTodas(),
            'marcas'     => $this->marcaModel->obtenerTodas(),
            'errores'    => [],
            'old'        => [],
            'show_modal' => false,
        ]);
    }

    /** GET /?action=articulo-nuevo */
    public function crear(): void
    {
        $this->render('articulos/create', [
            'categorias' => $this->categoriaModel->obtenerTodas(),
            'marcas'     => $this->marcaModel->obtenerTodas(),
            'errores'    => [],
            'old'        => [],
        ]);
    }

    public function guardar(): void
    {
        $datos = $this->validar($_POST);

        if (!empty($datos['errores'])) {
            $busqueda  = '';
            $articulos = $this->articuloModel->obtenerTodos($busqueda);

            $this->render('articulos/index', [
                'articulos'  => $articulos,
                'busqueda'   => $busqueda,
                'total'      => $this->articuloModel->totalArticulos(),
                'valorInv'   => $this->articuloModel->valorInventario(),
                'stockBajo'  => $this->articuloModel->stockBajo(),
                'categorias' => $this->categoriaModel->obtenerTodas(),
                'marcas'     => $this->marcaModel->obtenerTodas(),
                'errores'    => $datos['errores'],
                'old'        => $_POST,
                'show_modal' => true,
            ]);
            return;
        }

        $this->articuloModel->crear($datos);
        $this->redirect('index.php?action=articulos&msg=creado');
    }

    /** GET /?action=articulo-editar&id=1 */
    public function editar(): void
    {
        $id       = (int) ($_GET['id'] ?? 0);
        $articulo = $this->articuloModel->obtenerPorId($id);

        if (!$articulo) {
            $this->redirect('index.php?action=articulos');
        }

        $this->render('articulos/edit', [
            'articulo'   => $articulo,
            'categorias' => $this->categoriaModel->obtenerTodas(),
            'marcas'     => $this->marcaModel->obtenerTodas(),
            'errores'    => [],
        ]);
    }

    /** POST /?action=articulo-actualizar&id=1 */
    public function actualizar(): void
    {
        $id    = (int) ($_GET['id'] ?? 0);
        $datos = $this->validar($_POST);

        if (!empty($datos['errores'])) {
            $this->render('articulos/edit', [
                'articulo'   => array_merge(['id' => $id], $_POST),
                'categorias' => $this->categoriaModel->obtenerTodas(),
                'marcas'     => $this->marcaModel->obtenerTodas(),
                'errores'    => $datos['errores'],
            ]);
            return;
        }

        $this->articuloModel->actualizar($id, $datos);
        $this->redirect('index.php?action=articulos&msg=actualizado');
    }

    /** GET /?action=articulo-ver&id=1 */
    public function ver(): void
    {
        $id       = (int) ($_GET['id'] ?? 0);
        $articulo = $this->articuloModel->obtenerPorId($id);

        if (!$articulo) {
            $this->redirect('index.php?action=articulos');
        }

        $this->render('articulos/show', ['articulo' => $articulo]);
    }

    /** POST /?action=articulo-eliminar&id=1 */
    public function eliminar(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $this->articuloModel->eliminar($id);
        $this->redirect('index.php?action=articulos&msg=eliminado');
    }

    private function validar(array $datos): array
    {
        $errores = [];

        if (trim($datos['nombre'] ?? '') === '') {
            $errores['nombre'] = 'El nombre es obligatorio.';
        }
        if (!is_numeric($datos['precio_compra'] ?? '') || (float) $datos['precio_compra'] < 0) {
            $errores['precio_compra'] = 'El precio de compra debe ser un número válido.';
        }
        if (!is_numeric($datos['precio_venta'] ?? '') || (float) $datos['precio_venta'] < 0) {
            $errores['precio_venta'] = 'El precio de venta debe ser un número válido.';
        }
        if (!is_numeric($datos['stock'] ?? '') || (int) $datos['stock'] < 0) {
            $errores['stock'] = 'El stock debe ser un número entero válido.';
        }

        return [
            'nombre'        => trim($datos['nombre'] ?? ''),
            'descripcion'   => trim($datos['descripcion'] ?? ''),
            'categoria_id'  => $datos['categoria_id'] ?? null,
            'precio_compra' => $datos['precio_compra'] ?? 0,
            'precio_venta'  => $datos['precio_venta'] ?? 0,
            'stock'         => $datos['stock'] ?? 0,
            'marca_id'      => $datos['marca_id'] ?? null,
            'errores'       => $errores,
        ];
    }
}
