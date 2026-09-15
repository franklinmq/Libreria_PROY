<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Articulo.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Reporte.php';

class DashboardController extends Controller
{
    public function index(): void
    {
        $db = Database::getConnection();
        $reporteModel = new Reporte();

        // 1. Totales generales del sistema
        $totalArticulos = (int) $db->query("SELECT COUNT(*) FROM articulos")->fetchColumn();
        $totalCategorias = (int) $db->query("SELECT COUNT(*) FROM categorias")->fetchColumn();
        $totalMarcas = (int) $db->query("SELECT COUNT(*) FROM marcas")->fetchColumn();
        $totalProveedores = (int) $db->query("SELECT COUNT(*) FROM proveedores")->fetchColumn();

        // 2. Resumen Ventas
        $resVentas = $db->query("
            SELECT COUNT(*) AS total_ventas,
                   COALESCE(SUM(total), 0) AS total_ingresos,
                   COALESCE(SUM(descuento), 0) AS total_descuentos
            FROM ventas
        ")->fetch(PDO::FETCH_ASSOC) ?: ['total_ventas' => 0, 'total_ingresos' => 0, 'total_descuentos' => 0];

        // Ventas de hoy
        $ventasHoy = $db->query("
            SELECT COUNT(*) AS total_ventas_hoy,
                   COALESCE(SUM(total), 0) AS total_ingresos_hoy
            FROM ventas
            WHERE DATE(fecha_venta) = CURDATE()
        ")->fetch(PDO::FETCH_ASSOC) ?: ['total_ventas_hoy' => 0, 'total_ingresos_hoy' => 0];

        // 3. Resumen Compras
        $resCompras = $db->query("
            SELECT COUNT(*) AS total_compras,
                   COALESCE(SUM(total), 0) AS total_egresos
            FROM compras
        ")->fetch(PDO::FETCH_ASSOC) ?: ['total_compras' => 0, 'total_egresos' => 0];

        // 4. Inventario y valoración económica
        $inv = $db->query("
            SELECT COALESCE(SUM(stock), 0) AS total_stock_unidades,
                   COALESCE(SUM(stock * precio_compra), 0) AS valor_costo,
                   COALESCE(SUM(stock * precio_venta), 0) AS valor_venta
            FROM articulos
        ")->fetch(PDO::FETCH_ASSOC) ?: ['total_stock_unidades' => 0, 'valor_costo' => 0, 'valor_venta' => 0];

        $utilidadBruta = (float) $resVentas['total_ingresos'] - (float) $resCompras['total_egresos'];

        // 5. Artículos con stock bajo (crítico <= 10)
        $stockBajoStmt = $db->query("
            SELECT a.*, c.nombre AS categoria_nombre, m.nombre AS marca_nombre
            FROM articulos a
            LEFT JOIN categorias c ON a.categoria_id = c.id
            LEFT JOIN marcas m ON a.marca_id = m.id
            WHERE a.stock <= 10
            ORDER BY a.stock ASC
            LIMIT 5
        ");
        $articulosStockBajo = $stockBajoStmt->fetchAll(PDO::FETCH_ASSOC);
        $totalStockBajo = (int) $db->query("SELECT COUNT(*) FROM articulos WHERE stock <= 10")->fetchColumn();

        // 6. Últimas 5 ventas realizadas
        $ultimasVentasStmt = $db->query("
            SELECT v.*, 
                   COALESCE((SELECT SUM(cantidad) FROM detalle_ventas WHERE venta_id = v.id), 0) AS total_unidades
            FROM ventas v
            ORDER BY v.fecha_venta DESC
            LIMIT 5
        ");
        $ultimasVentas = $ultimasVentasStmt->fetchAll(PDO::FETCH_ASSOC);

        // 7. Productos más vendidos
        $topVendidos = $reporteModel->obtenerTopVendidos(['limite' => 5]);

        // 8. Gráfica: Ventas y Compras de los últimos 7 días
        $labelsDias = [];
        $datosVentasDias = [];
        $datosComprasDias = [];

        for ($i = 6; $i >= 0; $i--) {
            $fecha = date('Y-m-d', strtotime("-$i days"));
            $labelsDias[] = date('d/m', strtotime($fecha));

            $stmtV = $db->prepare("SELECT COALESCE(SUM(total), 0) FROM ventas WHERE DATE(fecha_venta) = :fecha");
            $stmtV->execute([':fecha' => $fecha]);
            $datosVentasDias[] = (float) $stmtV->fetchColumn();

            $stmtC = $db->prepare("SELECT COALESCE(SUM(total), 0) FROM compras WHERE DATE(fecha_compra) = :fecha");
            $stmtC->execute([':fecha' => $fecha]);
            $datosComprasDias[] = (float) $stmtC->fetchColumn();
        }

        // 9. Distribución de categorías
        $catStmt = $db->query("
            SELECT c.nombre, COUNT(a.id) AS total_articulos
            FROM categorias c
            INNER JOIN articulos a ON a.categoria_id = c.id
            GROUP BY c.id, c.nombre
            ORDER BY total_articulos DESC
            LIMIT 6
        ");
        $categoriasDistribucion = $catStmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('dashboard/index', [
            'totalArticulos'         => $totalArticulos,
            'totalCategorias'        => $totalCategorias,
            'totalMarcas'            => $totalMarcas,
            'totalProveedores'       => $totalProveedores,
            'resVentas'              => $resVentas,
            'ventasHoy'              => $ventasHoy,
            'resCompras'             => $resCompras,
            'utilidadBruta'          => $utilidadBruta,
            'inv'                    => $inv,
            'articulosStockBajo'     => $articulosStockBajo,
            'totalStockBajo'         => $totalStockBajo,
            'ultimasVentas'          => $ultimasVentas,
            'topVendidos'            => $topVendidos,
            'labelsDias'             => $labelsDias,
            'datosVentasDias'        => $datosVentasDias,
            'datosComprasDias'       => $datosComprasDias,
            'categoriasDistribucion' => $categoriasDistribucion,
        ]);
    }
}
