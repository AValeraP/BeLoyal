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
            "SELECT id_usuario, nombre, email, rol, activo, especialidad
             FROM usuarios
             WHERE rol = 'empleado'
             ORDER BY nombre"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id_usuario, nombre, email, rol, activo, especialidad
             FROM usuarios
             WHERE id_usuario = :id
             LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function crear(string $nombre, string $email, string $password, string $especialidad): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO usuarios (nombre, email, password, rol, activo, especialidad)
             VALUES (:nombre, :email, :password, 'empleado', 1, :especialidad)"
        );
        $stmt->execute([
            ':nombre'       => $nombre,
            ':email'        => $email,
            ':password'     => $password,
            ':especialidad' => $especialidad,
        ]);
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
            "UPDATE usuarios
             SET password = :password
             WHERE id_usuario = :id"
        );
        $stmt->execute([
            ':password' => $password,
            ':id'       => $id,
        ]);
    }

    public function desactivar(int $id): void
{
    $stmt = $this->pdo->prepare(
        "UPDATE usuarios SET activo = 0 WHERE id_usuario = :id AND rol = 'empleado'"
    );
    $stmt->execute([':id' => $id]);
}
}