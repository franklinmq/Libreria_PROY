<?php

require_once __DIR__ . '/../core/Database.php';

class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function obtenerPorEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public function crear(string $nombre, string $email, string $password): bool
    {
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)");
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':password' => $hash
        ]);
    }
}
