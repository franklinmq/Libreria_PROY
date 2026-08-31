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
                       (SELECT SUM(cantidad) FROM detalle_ventas WHERE venta_id = v.id) AS total_articulos
                FROM ventas v
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
