<?php

class ModeloUsuario
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Busca un usuario por email. Devuelve el registro completo o false.
     */
    public function buscarPorEmail(string $email)
    {
        $stmt = $this->pdo->prepare(
            "SELECT id_usuario, nombre, email, password, rol, especialidad, activo
             FROM usuarios
             WHERE email = :email
             LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
