<?php

class ModeloServicio
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function obtenerTodos(): array
    {
        return $this->pdo->query("SELECT * FROM servicios ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM servicios WHERE id_servicio = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function crear(string $nombre, float $precio, int $duracion): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO servicios (nombre, precio, duracion, activo) VALUES (:nombre, :precio, :duracion, 1)"
        );
        $stmt->execute([':nombre' => $nombre, ':precio' => $precio, ':duracion' => $duracion]);
    }

    public function actualizar(int $id, string $nombre, float $precio, int $duracion): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE servicios SET nombre = :nombre, precio = :precio, duracion = :duracion WHERE id_servicio = :id"
        );
        $stmt->execute([':nombre' => $nombre, ':precio' => $precio, ':duracion' => $duracion, ':id' => $id]);
    }

    public function eliminar(int $id): void
    {
        $stmt = $this->pdo->prepare("UPDATE servicios SET activo = 0 WHERE id_servicio = :id");
        $stmt->execute([':id' => $id]);
    }
}