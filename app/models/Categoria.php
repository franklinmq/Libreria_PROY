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

    public function crear(string $nombre): bool
    {
        $stmt = $this->db->prepare("INSERT INTO categorias (nombre) VALUES (:nombre)");
        return $stmt->execute([':nombre' => $nombre]);
    }
}
