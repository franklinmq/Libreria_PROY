<?php

require_once __DIR__ . '/../core/Database.php';

class Marca
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function obtenerTodas(): array
    {
        return $this->db->query("SELECT * FROM marcas ORDER BY nombre ASC")->fetchAll();
    }

    public function crear(string $nombre): int|false
    {
        $stmt = $this->db->prepare("INSERT INTO marcas (nombre) VALUES (:nombre)");
        if ($stmt->execute([':nombre' => $nombre])) {
            return (int) $this->db->lastInsertId();
        }
        return false;
    }
}
