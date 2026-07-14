<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';

class ProductoController extends Controller
{
    private Producto $productoModel;
    private Categoria $categoriaModel;

    public function __construct()
    {
        $this->productoModel  = new Producto();
        $this->categoriaModel = new Categoria();
    }

    /** GET /?action=productos  -> Listado + buscador + dashboard */
    public function index(): void
    {
        $busqueda  = $_GET['q'] ?? '';
        $productos = $this->productoModel->obtenerTodos($busqueda);

        $this->render('productos/index', [
            'productos' => $productos,
            'busqueda'  => $busqueda,
            'total'     => $this->productoModel->totalProductos(),
            'valorInv'  => $this->productoModel->valorInventario(),
            'stockBajo' => $this->productoModel->stockBajo(),
        ]);
    }

    /** GET /?action=producto-nuevo */
    public function crear(): void
    {
        $this->render('productos/create', [
            'categorias' => $this->categoriaModel->obtenerTodas(),
            'errores'    => [],
            'old'        => [],
        ]);
    }

    /** POST /?action=producto-guardar */
    public function guardar(): void
    {
        $datos = $this->validar($_POST);

        if (!empty($datos['errores'])) {
            $this->render('productos/create', [
                'categorias' => $this->categoriaModel->obtenerTodas(),
                'errores'    => $datos['errores'],
                'old'        => $_POST,
            ]);
            return;
        }

        $this->productoModel->crear($datos);
        $this->redirect('index.php?action=productos&msg=creado');
    }

    /** GET /?action=producto-editar&id=1 */
    public function editar(): void
    {
        $id       = (int) ($_GET['id'] ?? 0);
        $producto = $this->productoModel->obtenerPorId($id);

        if (!$producto) {
            $this->redirect('index.php?action=productos');
        }

        $this->render('productos/edit', [
            'producto'   => $producto,
            'categorias' => $this->categoriaModel->obtenerTodas(),
            'errores'    => [],
        ]);
    }

    /** POST /?action=producto-actualizar&id=1 */
    public function actualizar(): void
    {
        $id    = (int) ($_GET['id'] ?? 0);
        $datos = $this->validar($_POST);

        if (!empty($datos['errores'])) {
            $this->render('productos/edit', [
                'producto'   => array_merge(['id' => $id], $_POST),
                'categorias' => $this->categoriaModel->obtenerTodas(),
                'errores'    => $datos['errores'],
            ]);
            return;
        }

        $this->productoModel->actualizar($id, $datos);
        $this->redirect('index.php?action=productos&msg=actualizado');
    }

    /** GET /?action=producto-ver&id=1 */
    public function ver(): void
    {
        $id       = (int) ($_GET['id'] ?? 0);
        $producto = $this->productoModel->obtenerPorId($id);

        if (!$producto) {
            $this->redirect('index.php?action=productos');
        }

        $this->render('productos/show', ['producto' => $producto]);
    }

    /** POST /?action=producto-eliminar&id=1 */
    public function eliminar(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $this->productoModel->eliminar($id);
        $this->redirect('index.php?action=productos&msg=eliminado');
    }

    private function validar(array $datos): array
    {
        $errores = [];

        if (trim($datos['nombre'] ?? '') === '') {
            $errores['nombre'] = 'El nombre es obligatorio.';
        }
        if (trim($datos['codigo_barras'] ?? '') === '') {
            $errores['codigo_barras'] = 'El código de barras es obligatorio.';
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
            'codigo_barras' => trim($datos['codigo_barras'] ?? ''),
            'descripcion'   => trim($datos['descripcion'] ?? ''),
            'categoria_id'  => $datos['categoria_id'] ?? null,
            'precio_compra' => $datos['precio_compra'] ?? 0,
            'precio_venta'  => $datos['precio_venta'] ?? 0,
            'stock'         => $datos['stock'] ?? 0,
            'marca'         => trim($datos['marca'] ?? ''),
            'errores'       => $errores,
        ];
    }
}
