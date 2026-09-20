<?php

require_once __DIR__ . '/../core/Database.php';

class Venta
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function obtenerTodas(): array
    {
        $sql = "SELECT v.*, 
                       (SELECT SUM(cantidad) FROM detalle_ventas WHERE venta_id = v.id) AS total_articulos,
                       (SELECT GROUP_CONCAT(CONCAT(a.nombre, ' (', d.cantidad, ')') SEPARATOR ', ') 
                        FROM detalle_ventas d 
                        JOIN articulos a ON d.articulo_id = a.id 
                        WHERE d.venta_id = v.id) AS productos_nombres
                FROM ventas v
                ORDER BY v.fecha_venta DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function obtenerPorFecha(string $fecha): array
    {
        $sql = "SELECT v.*, 
                       (SELECT SUM(cantidad) FROM detalle_ventas WHERE venta_id = v.id) AS total_articulos,
                       (SELECT GROUP_CONCAT(CONCAT(a.nombre, ' (', d.cantidad, ')') SEPARATOR ', ') 
                        FROM detalle_ventas d 
                        JOIN articulos a ON d.articulo_id = a.id 
                        WHERE d.venta_id = v.id) AS productos_nombres
                FROM ventas v
                WHERE DATE(v.fecha_venta) = :fecha
                ORDER BY v.fecha_venta DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);
        return $stmt->fetchAll();
    }

    public function obtenerDetalleVentasPorFecha(string $fecha): array
    {
        $sql = "SELECT 
                    v.id AS venta_id,
                    v.fecha_venta,
                    v.cliente_nombre,
                    d.id AS detalle_id,
                    d.cantidad,
                    d.precio_unitario,
                    (d.cantidad * d.precio_unitario) AS subtotal,
                    a.nombre AS articulo_nombre,
                    c.nombre AS categoria_nombre,
                    m.nombre AS marca_nombre
                FROM detalle_ventas d
                JOIN ventas v ON d.venta_id = v.id
                JOIN articulos a ON d.articulo_id = a.id
                LEFT JOIN categorias c ON a.categoria_id = c.id
                LEFT JOIN marcas m ON a.marca_id = m.id
                WHERE DATE(v.fecha_venta) = :fecha
                ORDER BY v.fecha_venta DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);
        return $stmt->fetchAll();
    }

    public function obtenerDetalleVentasTodas(): array
    {
        $sql = "SELECT 
                    v.id AS venta_id,
                    v.fecha_venta,
                    v.cliente_nombre,
                    d.id AS detalle_id,
                    d.cantidad,
                    d.precio_unitario,
                    (d.cantidad * d.precio_unitario) AS subtotal,
                    a.nombre AS articulo_nombre,
                    c.nombre AS categoria_nombre,
                    m.nombre AS marca_nombre
                FROM detalle_ventas d
                JOIN ventas v ON d.venta_id = v.id
                JOIN articulos a ON d.articulo_id = a.id
                LEFT JOIN categorias c ON a.categoria_id = c.id
                LEFT JOIN marcas m ON a.marca_id = m.id
                ORDER BY v.fecha_venta DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT v.* FROM ventas v WHERE v.id = :id"
        );
        $stmt->execute([':id' => $id]);
        $venta = $stmt->fetch();

        if (!$venta) {
            return null;
        }

        // Obtener los detalles
        $stmtDetalle = $this->db->prepare(
            "SELECT d.*, a.nombre AS articulo_nombre
             FROM detalle_ventas d
             LEFT JOIN articulos a ON d.articulo_id = a.id
             WHERE d.venta_id = :venta_id"
        );
        $stmtDetalle->execute([':venta_id' => $id]);
        $venta['detalles'] = $stmtDetalle->fetchAll();

        return $venta;
    }

    public function crear(array $datos, array $detalles): bool
    {
        try {
            $this->db->beginTransaction();

            // 1. Insertar la venta
            $stmt = $this->db->prepare(
                "INSERT INTO ventas (cliente_nombre, total, descuento, fecha_venta)
                 VALUES (:cliente_nombre, :total, :descuento, :fecha_venta)"
            );
            $stmt->execute([
                ':cliente_nombre' => !empty($datos['cliente_nombre']) ? $datos['cliente_nombre'] : 'Consumidor Final',
                ':total'          => $datos['total'],
                ':descuento'      => $datos['descuento'],
                ':fecha_venta'    => date('Y-m-d H:i:s')
            ]);
            
            $venta_id = $this->db->lastInsertId();

            // 2. Insertar detalles y actualizar stock
            $stmtDetalle = $this->db->prepare(
                "INSERT INTO detalle_ventas (venta_id, articulo_id, cantidad, precio_unitario, subtotal)
                 VALUES (:venta_id, :articulo_id, :cantidad, :precio_unitario, :subtotal)"
            );

            // Importante: restamos la cantidad del stock actual
            $stmtStock = $this->db->prepare(
                "UPDATE articulos SET stock = stock - :cantidad WHERE id = :articulo_id"
            );

            foreach ($detalles as $detalle) {
                $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
                
                $stmtDetalle->execute([
                    ':venta_id'        => $venta_id,
                    ':articulo_id'     => $detalle['articulo_id'],
                    ':cantidad'        => $detalle['cantidad'],
                    ':precio_unitario' => $detalle['precio_unitario'],
                    ':subtotal'        => $subtotal
                ]);

                // Actualizar el stock del artículo restando la cantidad vendida
                $stmtStock->execute([
                    ':cantidad'        => $detalle['cantidad'],
                    ':articulo_id'     => $detalle['articulo_id']
                ]);
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
