<?php

require_once __DIR__ . '/../core/Database.php';

class Proveedor
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function obtenerTodos(): array
    {
        $stmt = $this->db->query("SELECT * FROM proveedores ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM proveedores WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $proveedor = $stmt->fetch();
        return $proveedor ?: null;
    }
}
