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
        // Sin stock se crea desactivado, para que no salga en el punto de venta
        $stmt = $this->pdo->prepare(
            "INSERT INTO productos (nombre, precio, stock, activo) VALUES (:nombre, :precio, :stock, :activo)"
        );
        $stmt->execute([':nombre' => $nombre, ':precio' => $precio, ':stock' => $stock, ':activo' => $stock > 0 ? 1 : 0]);
    }

    public function actualizar(int $id, string $nombre, float $precio, int $stock): void
    {
        // Sin stock se desactiva. Si estaba agotado (stock 0 y desactivado) y se repone, se vuelve a activar.
        // 'activo' va antes que 'stock' porque MySQL evalúa el SET de izquierda a derecha.
        $stmt = $this->pdo->prepare(
            "UPDATE productos
             SET activo = CASE WHEN :stock_nuevo = 0 THEN 0
                               WHEN stock = 0 AND activo = 0 THEN 1
                               ELSE activo END,
                 nombre = :nombre, precio = :precio, stock = :stock
             WHERE id_producto = :id"
        );
        $stmt->bindValue(':stock_nuevo', $stock, PDO::PARAM_INT);   // como número, para que "= 0" compare bien
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':precio', $precio);
        $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function eliminar(int $id): void
    {
        $stmt = $this->pdo->prepare("UPDATE productos SET activo = 0 WHERE id_producto = :id");
        $stmt->execute([':id' => $id]);
    }

    /** Activa el producto solo si tiene stock. Devuelve false si no se pudo por estar agotado. */
    public function activar(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE productos SET activo = 1 WHERE id_producto = :id AND stock > 0");
        $stmt->execute([':id' => $id]);
        if ($stmt->rowCount() > 0) return true;
        $p = $this->obtenerPorId($id);
        return $p && (int) $p['stock'] > 0;   // ya estaba activo
    }

    public function tieneVentas(int $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM detalle_producto WHERE id_producto = :id");
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Elimina definitivamente el producto de la BD.
     * Solo borra productos inactivos; llamar solo si tieneVentas() devuelve false.
     */
    public function eliminarDefinitivamente(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM productos WHERE id_producto = :id AND activo = 0");
        $stmt->execute([':id' => $id]);
    }
}