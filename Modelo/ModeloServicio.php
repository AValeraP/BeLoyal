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
        return $this->pdo->query("SELECT * FROM servicios ORDER BY especialidad, nombre")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM servicios WHERE id_servicio = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function crear(string $nombre, float $precio, int $duracion, string $especialidad = 'peluqueria'): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO servicios (nombre, precio, duracion, activo, especialidad) VALUES (:nombre, :precio, :duracion, 1, :especialidad)"
        );
        $stmt->execute([':nombre' => $nombre, ':precio' => $precio, ':duracion' => $duracion, ':especialidad' => $especialidad]);
    }

    public function actualizar(int $id, string $nombre, float $precio, int $duracion, string $especialidad = 'peluqueria'): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE servicios SET nombre = :nombre, precio = :precio, duracion = :duracion, especialidad = :especialidad WHERE id_servicio = :id"
        );
        $stmt->execute([':nombre' => $nombre, ':precio' => $precio, ':duracion' => $duracion, ':especialidad' => $especialidad, ':id' => $id]);
    }

    public function eliminar(int $id): void
    {
        $stmt = $this->pdo->prepare("UPDATE servicios SET activo = 0 WHERE id_servicio = :id");
        $stmt->execute([':id' => $id]);
    }
}