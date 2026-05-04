<?php
 
class ModeloTrabajador
{
    private PDO $pdo;
 
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
 
    /**
     * Devuelve los datos del empleado por su id desde la BD.
     */
    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id_usuario, nombre, email, rol, especialidad
             FROM usuarios
             WHERE id_usuario = :id AND activo = 1
             LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
 