<?php

class ModeloServicio
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Devuelve los servicios que corresponden a la especialidad del empleado
     * MÁS siempre las bebidas (categoría 'bebida').
     *
     * Si la especialidad es 'todas', devuelve todos los servicios excepto bebidas.
     */
    public function obtenerPorEspecialidad(string $especialidad): array
    {
        if ($especialidad === 'todas') {
            // Admin o empleado con acceso total: todo excepto bebidas
            $stmt = $this->pdo->prepare(
                "SELECT id_servicio, nombre, categoria, precio
                 FROM servicios
                 WHERE activo = 1 AND categoria != 'bebida'
                 ORDER BY categoria, nombre"
            );
            $stmt->execute();
        } else {
            // Solo su especialidad + bebidas
            $stmt = $this->pdo->prepare(
                "SELECT id_servicio, nombre, categoria, precio
                 FROM servicios
                 WHERE activo = 1 AND (categoria = :especialidad OR categoria = 'bebida')
                 ORDER BY categoria, nombre"
            );
            $stmt->execute([':especialidad' => $especialidad]);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Agrupa los servicios por categoría para renderizado en la vista.
     * Devuelve un array [ 'barberia' => [...], 'bebida' => [...], ... ]
     */
    public function agruparPorCategoria(array $servicios): array
    {
        $grupos = [];
        foreach ($servicios as $s) {
            $grupos[$s['categoria']][] = $s;
        }
        return $grupos;
    }
}
