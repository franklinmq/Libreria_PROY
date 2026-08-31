<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../models/Articulo.php';

class VentaController extends Controller
{
    private Venta $ventaModel;
    private Articulo $articuloModel;

    public function __construct()
    {
        $this->ventaModel = new Venta();
        $this->articuloModel = new Articulo();
    }

    public function index(): void
    {
        $ventas = $this->ventaModel->obtenerTodas();

        $this->render('ventas/index', [
            'ventas' => $ventas
        ]);
    }

    public function crear(): void
    {
        $articulos = $this->articuloModel->obtenerTodos();

        $this->render('ventas/create', [
            'articulos' => $articulos,
            'errores' => [],
            'old' => []
        ]);
    }

    public function guardar(): void
    {
        // Validación básica
        $cliente_nombre = trim($_POST['cliente_nombre'] ?? '');
        $articulos_ids = $_POST['articulos'] ?? [];
        $cantidades = $_POST['cantidades'] ?? [];
        $precios = $_POST['precios'] ?? [];

        $errores = [];

        if (empty($articulos_ids)) {
            $errores['general'] = 'Debe agregar al menos un artículo a la venta.';
        }

        $detalles = [];
        $total_final = 0;
        $descuento = 0;

        // Procesar los detalles
        if (empty($errores)) {
            foreach ($articulos_ids as $index => $articulo_id) {
                $cantidad = (int)($cantidades[$index] ?? 0);
                $precio_vendido = (float)($precios[$index] ?? 0);

                if ($cantidad <= 0 || $precio_vendido < 0) {
                    $errores['detalles'] = 'Las cantidades deben ser mayores a 0 y los precios no pueden ser negativos.';
                    break;
                }
                
                $articulo_info = $this->articuloModel->obtenerPorId($articulo_id);
                if ($articulo_info) {
                    if ($articulo_info['stock'] < $cantidad) {
                        $errores['detalles'] = "No hay stock suficiente para el artículo: {$articulo_info['nombre']}. Stock actual: {$articulo_info['stock']}.";
                        break;
                    }
                    
                    $precio_original = (float)$articulo_info['precio_venta'];
                    if ($precio_original > $precio_vendido) {
                        $descuento += ($precio_original - $precio_vendido) * $cantidad;
                    }
                }

                $detalles[] = [
                    'articulo_id' => $articulo_id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio_vendido
                ];

                $total_final += ($cantidad * $precio_vendido);
            }
        }



        if (!empty($errores)) {
            $this->render('ventas/create', [
                'articulos' => $this->articuloModel->obtenerTodos(),
                'errores' => $errores,
                'old' => $_POST
            ]);
            return;
        }

        $datosVenta = [
            'cliente_nombre' => $cliente_nombre,
            'total' => $total_final,
            'descuento' => $descuento
        ];

        try {
            $this->ventaModel->crear($datosVenta, $detalles);
            $this->redirect('index.php?action=ventas&msg=creado');
        } catch (Exception $e) {
            $errores['general'] = 'Error al registrar la venta: ' . $e->getMessage();
            $this->render('ventas/create', [
                'articulos' => $this->articuloModel->obtenerTodos(),
                'errores' => $errores,
                'old' => $_POST
            ]);
        }
    }

    public function ver(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $venta = $this->ventaModel->obtenerPorId($id);

        if (!$venta) {
            $this->redirect('index.php?action=ventas');
        }

        $this->render('ventas/show', [
            'venta' => $venta
        ]);
    }
}
