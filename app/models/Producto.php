<?php

require_once __DIR__ . '/../core/Database.php';

class Producto
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /** Obtiene todos los productos, opcionalmente filtrados por texto de búsqueda */
    public function obtenerTodos(string $busqueda = ''): array
    {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre
                FROM productos p
                LEFT JOIN categorias c ON c.id = p.categoria_id";

        $params = [];

        if ($busqueda !== '') {
            $sql .= " WHERE p.nombre LIKE :busqueda OR p.codigo_barras LIKE :busqueda OR p.marca LIKE :busqueda";
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
            "SELECT p.*, c.nombre AS categoria_nombre
             FROM productos p
             LEFT JOIN categorias c ON c.id = p.categoria_id
             WHERE p.id = :id"
        );
        $stmt->execute([':id' => $id]);
        $producto = $stmt->fetch();

        return $producto ?: null;
    }

    public function crear(array $datos): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO productos (codigo_barras, nombre, descripcion, categoria_id, precio_compra, precio_venta, stock, marca, imagen)
             VALUES (:codigo_barras, :nombre, :descripcion, :categoria_id, :precio_compra, :precio_venta, :stock, :marca, :imagen)"
        );

        return $stmt->execute([
            ':codigo_barras' => $datos['codigo_barras'],
            ':nombre'        => $datos['nombre'],
            ':descripcion'   => $datos['descripcion'],
            ':categoria_id'  => $datos['categoria_id'],
            ':precio_compra' => $datos['precio_compra'],
            ':precio_venta'  => $datos['precio_venta'],
            ':stock'         => $datos['stock'],
            ':marca'         => $datos['marca'],
            ':imagen'        => $datos['imagen'] ?? null,
        ]);
    }

    public function actualizar(int $id, array $datos): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE productos SET
                codigo_barras = :codigo_barras,
                nombre = :nombre,
                descripcion = :descripcion,
                categoria_id = :categoria_id,
                precio_compra = :precio_compra,
                precio_venta = :precio_venta,
                stock = :stock,
                marca = :marca
             WHERE id = :id"
        );

        return $stmt->execute([
            ':codigo_barras' => $datos['codigo_barras'],
            ':nombre'        => $datos['nombre'],
            ':descripcion'   => $datos['descripcion'],
            ':categoria_id'  => $datos['categoria_id'],
            ':precio_compra' => $datos['precio_compra'],
            ':precio_venta'  => $datos['precio_venta'],
            ':stock'         => $datos['stock'],
            ':marca'         => $datos['marca'],
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
