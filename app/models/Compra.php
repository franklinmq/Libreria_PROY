<?php

require_once __DIR__ . '/../core/Database.php';

class Compra
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function obtenerTodas(): array
    {
        $sql = "SELECT c.*, p.nombre AS proveedor_nombre, 
                       (SELECT SUM(cantidad) FROM detalle_compras WHERE compra_id = c.id) AS total_articulos
                FROM compras c
                LEFT JOIN proveedores p ON c.proveedor_id = p.id
                ORDER BY c.fecha_compra DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, p.nombre AS proveedor_nombre, p.contacto, p.telefono
             FROM compras c
             LEFT JOIN proveedores p ON c.proveedor_id = p.id
             WHERE c.id = :id"
        );
        $stmt->execute([':id' => $id]);
        $compra = $stmt->fetch();

        if (!$compra) {
            return null;
        }

        // Obtener los detalles
        $stmtDetalle = $this->db->prepare(
            "SELECT d.*, a.nombre AS articulo_nombre, a.codigo AS articulo_codigo
             FROM detalle_compras d
             LEFT JOIN articulos a ON d.articulo_id = a.id
             WHERE d.compra_id = :compra_id"
        );
        $stmtDetalle->execute([':compra_id' => $id]);
        $compra['detalles'] = $stmtDetalle->fetchAll();

        return $compra;
    }

    public function crear(array $datos, array $detalles): bool
    {
        try {
            $this->db->beginTransaction();

            // 1. Insertar la compra
            $stmt = $this->db->prepare(
                "INSERT INTO compras (proveedor_id, total, fecha_compra)
                 VALUES (:proveedor_id, :total, :fecha_compra)"
            );
            $stmt->execute([
                ':proveedor_id' => $datos['proveedor_id'] ?: null,
                ':total'        => $datos['total'],
                ':fecha_compra' => date('Y-m-d H:i:s')
            ]);
            
            $compra_id = $this->db->lastInsertId();

            // 2. Insertar detalles y actualizar stock
            $stmtDetalle = $this->db->prepare(
                "INSERT INTO detalle_compras (compra_id, articulo_id, cantidad, precio_unitario, subtotal)
                 VALUES (:compra_id, :articulo_id, :cantidad, :precio_unitario, :subtotal)"
            );

            $stmtStock = $this->db->prepare(
                "UPDATE articulos SET stock = stock + :cantidad, precio_compra = :precio_unitario WHERE id = :articulo_id"
            );

            foreach ($detalles as $detalle) {
                $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
                
                $stmtDetalle->execute([
                    ':compra_id'       => $compra_id,
                    ':articulo_id'     => $detalle['articulo_id'],
                    ':cantidad'        => $detalle['cantidad'],
                    ':precio_unitario' => $detalle['precio_unitario'],
                    ':subtotal'        => $subtotal
                ]);

                // Actualizar el stock y el precio de compra del artículo
                $stmtStock->execute([
                    ':cantidad'        => $detalle['cantidad'],
                    ':precio_unitario' => $detalle['precio_unitario'],
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
