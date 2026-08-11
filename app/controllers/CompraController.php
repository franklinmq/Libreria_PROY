<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Compra.php';
require_once __DIR__ . '/../models/Proveedor.php';
require_once __DIR__ . '/../models/Articulo.php';

class CompraController extends Controller
{
    private Compra $compraModel;
    private Proveedor $proveedorModel;
    private Articulo $articuloModel;

    public function __construct()
    {
        $this->compraModel = new Compra();
        $this->proveedorModel = new Proveedor();
        $this->articuloModel = new Articulo();
    }

    public function index(): void
    {
        $compras = $this->compraModel->obtenerTodas();

        $this->render('compras/index', [
            'compras' => $compras
        ]);
    }

    public function crear(): void
    {
        $proveedores = $this->proveedorModel->obtenerTodos();
        $articulos = $this->articuloModel->obtenerTodos();

        $this->render('compras/create', [
            'proveedores' => $proveedores,
            'articulos' => $articulos,
            'errores' => [],
            'old' => []
        ]);
    }

    public function guardar(): void
    {
        // Validación básica
        $proveedor_id = $_POST['proveedor_id'] ?? '';
        $articulos_ids = $_POST['articulos'] ?? [];
        $cantidades = $_POST['cantidades'] ?? [];
        $precios = $_POST['precios'] ?? [];

        $errores = [];

        if (empty($articulos_ids)) {
            $errores['general'] = 'Debe agregar al menos un artículo a la compra.';
        }

        $detalles = [];
        $total = 0;

        // Procesar los detalles
        if (empty($errores)) {
            foreach ($articulos_ids as $index => $articulo_id) {
                $cantidad = (int)($cantidades[$index] ?? 0);
                $precio = (float)($precios[$index] ?? 0);

                if ($cantidad <= 0 || $precio < 0) {
                    $errores['detalles'] = 'Las cantidades deben ser mayores a 0 y los precios no pueden ser negativos.';
                    break;
                }

                $detalles[] = [
                    'articulo_id' => $articulo_id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio
                ];

                $total += ($cantidad * $precio);
            }
        }

        if (!empty($errores)) {
            $this->render('compras/create', [
                'proveedores' => $this->proveedorModel->obtenerTodos(),
                'articulos' => $this->articuloModel->obtenerTodos(),
                'errores' => $errores,
                'old' => $_POST
            ]);
            return;
        }

        $datosCompra = [
            'proveedor_id' => $proveedor_id,
            'total' => $total
        ];

        try {
            $this->compraModel->crear($datosCompra, $detalles);
            $this->redirect('index.php?action=compras&msg=creado');
        } catch (Exception $e) {
            $errores['general'] = 'Error al registrar la compra: ' . $e->getMessage();
            $this->render('compras/create', [
                'proveedores' => $this->proveedorModel->obtenerTodos(),
                'articulos' => $this->articuloModel->obtenerTodos(),
                'errores' => $errores,
                'old' => $_POST
            ]);
        }
    }

    public function ver(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $compra = $this->compraModel->obtenerPorId($id);

        if (!$compra) {
            $this->redirect('index.php?action=compras');
        }

        $this->render('compras/show', [
            'compra' => $compra
        ]);
    }
}
