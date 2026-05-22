<?php

class ModeloTrabajador
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function obtenerTodos(): array
    {
        $stmt = $this->pdo->query(
            "SELECT id_usuario, nombre, email, rol, activo, especialidad, logo
             FROM usuarios
             WHERE rol = 'empleado'
             ORDER BY nombre"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id_usuario, nombre, email, rol, activo, especialidad, logo
             FROM usuarios
             WHERE id_usuario = :id
             LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function crear(string $nombre, string $email, string $password, string $especialidad): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO usuarios (nombre, email, password, rol, activo, especialidad)
             VALUES (:nombre, :email, :password, 'empleado', 1, :especialidad)"
        );
        $stmt->execute([
            ':nombre'       => $nombre,
            ':email'        => $email,
            ':password'     => password_hash($password, PASSWORD_BCRYPT),
            ':especialidad' => $especialidad,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function actualizar(int $id, string $nombre, string $email, string $especialidad, bool $activo): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE usuarios
             SET nombre = :nombre, email = :email, especialidad = :especialidad, activo = :activo
             WHERE id_usuario = :id"
        );
        $stmt->execute([
            ':nombre'       => $nombre,
            ':email'        => $email,
            ':especialidad' => $especialidad,
            ':activo'       => (int) $activo,
            ':id'           => $id,
        ]);
    }

    public function actualizarPassword(int $id, string $password): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE usuarios SET password = :password WHERE id_usuario = :id"
        );
        $stmt->execute([
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':id'       => $id,
        ]);
    }

    public function activar(int $id): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE usuarios SET activo = 1 WHERE id_usuario = :id AND rol = 'empleado'"
        );
        $stmt->execute([':id' => $id]);
    }

    public function desactivar(int $id): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE usuarios SET activo = 0 WHERE id_usuario = :id AND rol = 'empleado'"
        );
        $stmt->execute([':id' => $id]);
    }

    /**
     * Comprueba si el empleado tiene ventas registradas.
     * Si tiene ventas no se puede eliminar para conservar el historial.
     */
    public function tieneVentas(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM ventas WHERE id_empleado = :id"
        );
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Elimina definitivamente el empleado de la BD.
     * Solo se debe llamar si tieneVentas() devuelve false.
     */
    public function eliminarDefinitivamente(int $id): void
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM usuarios WHERE id_usuario = :id AND rol = 'empleado'"
        );
        $stmt->execute([':id' => $id]);
    }

    public function actualizarLogo(int $id, ?string $logo): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE usuarios SET logo = :logo WHERE id_usuario = :id AND rol = 'empleado'"
        );
        $stmt->execute([':logo' => $logo, ':id' => $id]);
    }
}