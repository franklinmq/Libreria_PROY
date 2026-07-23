<?php

require_once __DIR__ . '/../core/Database.php';

class Producto
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function obtenerTodos(string $busqueda = ''): array
    {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre, m.nombre AS marca_nombre
                FROM productos p
                LEFT JOIN categorias c ON c.id = p.categoria_id
                LEFT JOIN marcas m ON m.id = p.marca_id";

        $params = [];

        if ($busqueda !== '') {
            $sql .= " WHERE p.nombre LIKE :busqueda OR m.nombre LIKE :busqueda";
            $params[':busqueda'] = "%{$busqueda}%";
        }

        $sql .= " ORDER BY p.nombre ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.nombre AS categoria_nombre, m.nombre AS marca_nombre
             FROM productos p
             LEFT JOIN categorias c ON c.id = p.categoria_id
             LEFT JOIN marcas m ON m.id = p.marca_id
             WHERE p.id = :id"
        );
        $stmt->execute([':id' => $id]);
        $producto = $stmt->fetch();

        return $producto ?: null;
    }

    public function crear(array $datos): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO productos (nombre, descripcion, categoria_id, precio_compra, precio_venta, stock, marca_id, imagen)
             VALUES (:nombre, :descripcion, :categoria_id, :precio_compra, :precio_venta, :stock, :marca_id, :imagen)"
        );

        return $stmt->execute([
            ':nombre'        => $datos['nombre'],
            ':descripcion'   => $datos['descripcion'],
            ':categoria_id'  => $datos['categoria_id'],
            ':precio_compra' => $datos['precio_compra'],
            ':precio_venta'  => $datos['precio_venta'],
            ':stock'         => $datos['stock'],
            ':marca_id'      => $datos['marca_id'],
            ':imagen'        => $datos['imagen'] ?? null,
        ]);
    }

    public function actualizar(int $id, array $datos): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE productos SET
                nombre = :nombre,
                descripcion = :descripcion,
                categoria_id = :categoria_id,
                precio_compra = :precio_compra,
                precio_venta = :precio_venta,
                stock = :stock,
                marca_id = :marca_id
             WHERE id = :id"
        );

        return $stmt->execute([
            ':nombre'        => $datos['nombre'],
            ':descripcion'   => $datos['descripcion'],
            ':categoria_id'  => $datos['categoria_id'],
            ':precio_compra' => $datos['precio_compra'],
            ':precio_venta'  => $datos['precio_venta'],
            ':stock'         => $datos['stock'],
            ':marca_id'      => $datos['marca_id'],
            ':id'            => $id,
        ]);
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM productos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /** Productos con stock bajo (para alertas en el dashboard) */
    public function stockBajo(int $limite = 10): array
    {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE stock <= :limite ORDER BY stock ASC");
        $stmt->execute([':limite' => $limite]);
        return $stmt->fetchAll();
    }

    public function totalProductos(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM productos")->fetchColumn();
    }

    public function valorInventario(): float
    {
        return (float) $this->db->query("SELECT COALESCE(SUM(precio_compra * stock), 0) FROM productos")->fetchColumn();
    }
}
