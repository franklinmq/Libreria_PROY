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
}
