<?php

require_once __DIR__ . '/../core/Database.php';

class Libro
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /** Obtiene todos los libros, opcionalmente filtrados por texto de búsqueda */
    public function obtenerTodos(string $busqueda = ''): array
    {
        $sql = "SELECT l.*, c.nombre AS categoria_nombre
                FROM libros l
                LEFT JOIN categorias c ON c.id = l.categoria_id";

        $params = [];

        if ($busqueda !== '') {
            $sql .= " WHERE l.titulo LIKE :busqueda OR l.autor LIKE :busqueda OR l.isbn LIKE :busqueda";
            $params[':busqueda'] = "%{$busqueda}%";
        }

        $sql .= " ORDER BY l.titulo ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT l.*, c.nombre AS categoria_nombre
             FROM libros l
             LEFT JOIN categorias c ON c.id = l.categoria_id
             WHERE l.id = :id"
        );
        $stmt->execute([':id' => $id]);
        $libro = $stmt->fetch();

        return $libro ?: null;
    }

    public function crear(array $datos): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO libros (titulo, autor, isbn, categoria_id, precio, stock, editorial, imagen)
             VALUES (:titulo, :autor, :isbn, :categoria_id, :precio, :stock, :editorial, :imagen)"
        );

        return $stmt->execute([
            ':titulo'       => $datos['titulo'],
            ':autor'        => $datos['autor'],
            ':isbn'         => $datos['isbn'],
            ':categoria_id' => $datos['categoria_id'],
            ':precio'       => $datos['precio'],
            ':stock'        => $datos['stock'],
            ':editorial'    => $datos['editorial'],
            ':imagen'       => $datos['imagen'] ?? null,
        ]);
    }

    public function actualizar(int $id, array $datos): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE libros SET
                titulo = :titulo,
                autor = :autor,
                isbn = :isbn,
                categoria_id = :categoria_id,
                precio = :precio,
                stock = :stock,
                editorial = :editorial
             WHERE id = :id"
        );

        return $stmt->execute([
            ':titulo'       => $datos['titulo'],
            ':autor'        => $datos['autor'],
            ':isbn'         => $datos['isbn'],
            ':categoria_id' => $datos['categoria_id'],
            ':precio'       => $datos['precio'],
            ':stock'        => $datos['stock'],
            ':editorial'    => $datos['editorial'],
            ':id'           => $id,
        ]);
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM libros WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /** Libros con stock bajo (para alertas en el dashboard) */
    public function stockBajo(int $limite = 5): array
    {
        $stmt = $this->db->prepare("SELECT * FROM libros WHERE stock <= :limite ORDER BY stock ASC");
        $stmt->execute([':limite' => $limite]);
        return $stmt->fetchAll();
    }

    public function totalLibros(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM libros")->fetchColumn();
    }

    public function valorInventario(): float
    {
        return (float) $this->db->query("SELECT COALESCE(SUM(precio * stock), 0) FROM libros")->fetchColumn();
    }
}
