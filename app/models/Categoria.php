<?php

require_once __DIR__ . '/../core/Database.php';

class Categoria
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function obtenerTodas(): array
    {
        return $this->db->query("SELECT * FROM categorias ORDER BY nombre ASC")->fetchAll();
    }

    public function crear(string $nombre, ?int $parent_id = null): int|false
    {
        $stmt = $this->db->prepare("INSERT INTO categorias (nombre, parent_id) VALUES (:nombre, :parent_id)");
        if ($stmt->execute([
            ':nombre' => $nombre,
            ':parent_id' => $parent_id
        ])) {
            return (int) $this->db->lastInsertId();
        }
        return false;
    }

    public function obtenerPorId(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function actualizar(int $id, string $nombre, ?int $parent_id = null): bool
    {
        $stmt = $this->db->prepare("UPDATE categorias SET nombre = :nombre, parent_id = :parent_id WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':parent_id' => $parent_id
        ]);
    }

    public function eliminar(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM categorias WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
