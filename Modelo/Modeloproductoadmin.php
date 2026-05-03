<?php

class ModeloProductoAdmin
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function obtenerTodos(): array
    {
        return $this->pdo->query("SELECT * FROM productos ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM productos WHERE id_producto = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function crear(string $nombre, float $precio, int $stock): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO productos (nombre, precio, stock, activo) VALUES (:nombre, :precio, :stock, 1)"
        );
        $stmt->execute([':nombre' => $nombre, ':precio' => $precio, ':stock' => $stock]);
    }

    public function actualizar(int $id, string $nombre, float $precio, int $stock): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE productos SET nombre = :nombre, precio = :precio, stock = :stock WHERE id_producto = :id"
        );
        $stmt->execute([':nombre' => $nombre, ':precio' => $precio, ':stock' => $stock, ':id' => $id]);
    }

    public function eliminar(int $id): void
    {
        $stmt = $this->pdo->prepare("UPDATE productos SET activo = 0 WHERE id_producto = :id");
        $stmt->execute([':id' => $id]);
    }
}