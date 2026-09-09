<?php

require_once __DIR__ . '/../core/Database.php';

class Reporte
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Reporte de Ventas con filtros de fecha y cliente
     */
    public function obtenerReporteVentas(array $filtros = []): array
    {
        $sql = "SELECT v.*, 
                       COALESCE((SELECT SUM(cantidad) FROM detalle_ventas WHERE venta_id = v.id), 0) AS total_articulos
                FROM ventas v
                WHERE 1=1";
        $params = [];

        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND DATE(v.fecha_venta) >= :fecha_desde";
            $params[':fecha_desde'] = $filtros['fecha_desde'];
        }

        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND DATE(v.fecha_venta) <= :fecha_hasta";
            $params[':fecha_hasta'] = $filtros['fecha_hasta'];
        }

        if (!empty($filtros['cliente'])) {
            $sql .= " AND v.cliente_nombre LIKE :cliente";
            $params[':cliente'] = "%" . $filtros['cliente'] . "%";
        }

        $sql .= " ORDER BY v.fecha_venta DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Resumen / KPIs de Ventas
     */
    public function obtenerResumenVentas(array $filtros = []): array
    {
        $sql = "SELECT 
                    COUNT(DISTINCT v.id) AS total_operaciones,
                    COALESCE(SUM(v.total), 0) AS total_ingresos,
                    COALESCE(SUM(v.descuento), 0) AS total_descuentos,
                    COALESCE((
                        SELECT SUM(dv.cantidad) 
                        FROM detalle_ventas dv 
                        INNER JOIN ventas v2 ON dv.venta_id = v2.id 
                        WHERE 1=1 " . 
                        (!empty($filtros['fecha_desde']) ? " AND DATE(v2.fecha_venta) >= '{$filtros['fecha_desde']}'" : "") .
                        (!empty($filtros['fecha_hasta']) ? " AND DATE(v2.fecha_venta) <= '{$filtros['fecha_hasta']}'" : "") .
                        (!empty($filtros['cliente']) ? " AND v2.cliente_nombre LIKE '%" . addslashes($filtros['cliente']) . "%'" : "") .
                    "), 0) AS total_unidades
                FROM ventas v
                WHERE 1=1";
        $params = [];

        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND DATE(v.fecha_venta) >= :fecha_desde";
            $params[':fecha_desde'] = $filtros['fecha_desde'];
        }

        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND DATE(v.fecha_venta) <= :fecha_hasta";
            $params[':fecha_hasta'] = $filtros['fecha_hasta'];
        }

        if (!empty($filtros['cliente'])) {
            $sql .= " AND v.cliente_nombre LIKE :cliente";
            $params[':cliente'] = "%" . $filtros['cliente'] . "%";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $resumen = $stmt->fetch() ?: [];

        $totalOperaciones = (int)($resumen['total_operaciones'] ?? 0);
        $totalIngresos = (float)($resumen['total_ingresos'] ?? 0);
        $resumen['ticket_promedio'] = $totalOperaciones > 0 ? ($totalIngresos / $totalOperaciones) : 0;

        return $resumen;
    }

    /**
     * Reporte de Compras con filtros
     */
    public function obtenerReporteCompras(array $filtros = []): array
    {
        $sql = "SELECT c.*, p.nombre AS proveedor_nombre,
                       COALESCE((SELECT SUM(cantidad) FROM detalle_compras WHERE compra_id = c.id), 0) AS total_articulos
                FROM compras c
                LEFT JOIN proveedores p ON c.proveedor_id = p.id
                WHERE 1=1";
        $params = [];

        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND DATE(c.fecha_compra) >= :fecha_desde";
            $params[':fecha_desde'] = $filtros['fecha_desde'];
        }

        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND DATE(c.fecha_compra) <= :fecha_hasta";
            $params[':fecha_hasta'] = $filtros['fecha_hasta'];
        }

        if (!empty($filtros['proveedor_id'])) {
            $sql .= " AND c.proveedor_id = :proveedor_id";
            $params[':proveedor_id'] = $filtros['proveedor_id'];
        }

        $sql .= " ORDER BY c.fecha_compra DESC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Resumen / KPIs de Compras
     */
    public function obtenerResumenCompras(array $filtros = []): array
    {
        $sql = "SELECT 
                    COUNT(DISTINCT c.id) AS total_operaciones,
                    COALESCE(SUM(c.total), 0) AS total_egresos,
                    COALESCE((
                        SELECT SUM(dc.cantidad) 
                        FROM detalle_compras dc 
                        INNER JOIN compras c2 ON dc.compra_id = c2.id 
                        WHERE 1=1 " . 
                        (!empty($filtros['fecha_desde']) ? " AND DATE(c2.fecha_compra) >= '{$filtros['fecha_desde']}'" : "") .
                        (!empty($filtros['fecha_hasta']) ? " AND DATE(c2.fecha_compra) <= '{$filtros['fecha_hasta']}'" : "") .
                        (!empty($filtros['proveedor_id']) ? " AND c2.proveedor_id = '" . (int)$filtros['proveedor_id'] . "'" : "") .
                    "), 0) AS total_unidades
                FROM compras c
                WHERE 1=1";
        $params = [];

        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND DATE(c.fecha_compra) >= :fecha_desde";
            $params[':fecha_desde'] = $filtros['fecha_desde'];
        }

        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND DATE(c.fecha_compra) <= :fecha_hasta";
            $params[':fecha_hasta'] = $filtros['fecha_hasta'];
        }

        if (!empty($filtros['proveedor_id'])) {
            $sql .= " AND c.proveedor_id = :proveedor_id";
            $params[':proveedor_id'] = $filtros['proveedor_id'];
        }

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch() ?: ['total_operaciones' => 0, 'total_egresos' => 0, 'total_unidades' => 0];
        } catch (PDOException $e) {
            return ['total_operaciones' => 0, 'total_egresos' => 0, 'total_unidades' => 0];
        }
    }

    /**
     * Reporte de Inventario / Stock con filtros
     */
    public function obtenerReporteInventario(array $filtros = []): array
    {
        $sql = "SELECT a.*, 
                       c.nombre AS categoria_nombre, 
                       m.nombre AS marca_nombre,
                       (a.stock * a.precio_compra) AS valor_total_costo,
                       (a.stock * a.precio_venta) AS valor_total_venta,
                       ((a.precio_venta - a.precio_compra) * a.stock) AS ganancia_potencial
                FROM articulos a
                LEFT JOIN categorias c ON a.categoria_id = c.id
                LEFT JOIN marcas m ON a.marca_id = m.id
                WHERE 1=1";
        $params = [];

        if (!empty($filtros['categoria_id'])) {
            $sql .= " AND a.categoria_id = :categoria_id";
            $params[':categoria_id'] = $filtros['categoria_id'];
        }

        if (!empty($filtros['marca_id'])) {
            $sql .= " AND a.marca_id = :marca_id";
            $params[':marca_id'] = $filtros['marca_id'];
        }

        if (!empty($filtros['estado_stock'])) {
            if ($filtros['estado_stock'] === 'bajo') {
                $sql .= " AND a.stock > 0 AND a.stock <= 10";
            } elseif ($filtros['estado_stock'] === 'agotado') {
                $sql .= " AND a.stock <= 0";
            } elseif ($filtros['estado_stock'] === 'disponible') {
                $sql .= " AND a.stock > 10";
            }
        }

        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (a.nombre LIKE :busqueda OR a.descripcion LIKE :busqueda)";
            $params[':busqueda'] = "%" . $filtros['busqueda'] . "%";
        }

        $sql .= " ORDER BY a.nombre ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Resumen / KPIs de Inventario
     */
    public function obtenerResumenInventario(array $filtros = []): array
    {
        $articulos = $this->obtenerReporteInventario($filtros);

        $totalArticulos = count($articulos);
        $totalStock = 0;
        $totalCosto = 0;
        $totalVenta = 0;
        $totalBajoStock = 0;
        $totalAgotados = 0;

        foreach ($articulos as $art) {
            $stock = (int)$art['stock'];
            $totalStock += $stock;
            $totalCosto += ((float)$art['precio_compra'] * $stock);
            $totalVenta += ((float)$art['precio_venta'] * $stock);
            if ($stock <= 0) {
                $totalAgotados++;
            } elseif ($stock <= 10) {
                $totalBajoStock++;
            }
        }

        return [
            'total_articulos'     => $totalArticulos,
            'total_stock'         => $totalStock,
            'valor_costo_total'   => $totalCosto,
            'valor_venta_total'   => $totalVenta,
            'ganancia_potencial'  => ($totalVenta - $totalCosto),
            'articulos_bajo_stock'=> $totalBajoStock,
            'articulos_agotados'  => $totalAgotados,
        ];
    }

    /**
     * Reporte de Productos Más Vendidos
     */
    public function obtenerTopVendidos(array $filtros = []): array
    {
        $limite = (int)($filtros['limite'] ?? 10);
        if ($limite <= 0) $limite = 10;

        $sql = "SELECT a.id, a.nombre, a.precio_venta, a.precio_compra,
                       c.nombre AS categoria_nombre,
                       m.nombre AS marca_nombre,
                       SUM(dv.cantidad) AS total_unidades_vendidas,
                       SUM(dv.subtotal) AS total_recaudado,
                       SUM(dv.cantidad * (dv.precio_unitario - a.precio_compra)) AS ganancia_estimada
                FROM detalle_ventas dv
                INNER JOIN ventas v ON dv.venta_id = v.id
                INNER JOIN articulos a ON dv.articulo_id = a.id
                LEFT JOIN categorias c ON a.categoria_id = c.id
                LEFT JOIN marcas m ON a.marca_id = m.id
                WHERE 1=1";
        $params = [];

        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND DATE(v.fecha_venta) >= :fecha_desde";
            $params[':fecha_desde'] = $filtros['fecha_desde'];
        }

        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND DATE(v.fecha_venta) <= :fecha_hasta";
            $params[':fecha_hasta'] = $filtros['fecha_hasta'];
        }

        $sql .= " GROUP BY a.id, a.nombre, a.precio_venta, a.precio_compra, c.nombre, m.nombre
                  ORDER BY total_unidades_vendidas DESC
                  LIMIT " . $limite;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Resumen Balance Financiero
     */
    public function obtenerBalance(array $filtros = []): array
    {
        $resVentas = $this->obtenerResumenVentas($filtros);
        $resCompras = $this->obtenerResumenCompras($filtros);

        $totalIngresos = (float)($resVentas['total_ingresos'] ?? 0);
        $totalEgresos = (float)($resCompras['total_egresos'] ?? 0);
        $utilidadBruta = $totalIngresos - $totalEgresos;

        return [
            'total_ingresos'    => $totalIngresos,
            'total_egresos'     => $totalEgresos,
            'utilidad_bruta'    => $utilidadBruta,
            'total_ventas'      => (int)($resVentas['total_operaciones'] ?? 0),
            'total_compras'     => (int)($resCompras['total_operaciones'] ?? 0),
            'unidades_vendidas' => (int)($resVentas['total_unidades'] ?? 0),
            'unidades_compradas'=> (int)($resCompras['total_unidades'] ?? 0)
        ];
    }
}
