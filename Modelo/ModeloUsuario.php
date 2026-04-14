<?php

class UsuarioModel
{
    private $pdo;

    public function __construct()
    {
        // Incluimos la conexión; $pdo queda disponible en este scope
        require_once __DIR__ . '/../Conn/conexion.php';
        $this->pdo = $pdo;
    }

    /**
     * Busca un usuario por email y devuelve el registro completo,
     * o false si no existe.
     */
    public function buscarPorEmail(string $email)
    {
        $stmt = $this->pdo->prepare(
            "SELECT id_usuario, nombre, email, password, rol, activo
             FROM usuarios
             WHERE email = :email
             LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}