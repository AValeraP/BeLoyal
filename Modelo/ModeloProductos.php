<?php

class ModeloProductos
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function obtenerBonos(): array
    {
        return $this->pdo->query(
            "SELECT id_servicio AS id, nombre, precio
             FROM servicios
             WHERE activo = 1 AND especialidad = 'bono'
             ORDER BY nombre"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerBebidas(): array
    {
        return $this->pdo->query(
            "SELECT id_producto AS id, nombre, precio
             FROM productos
             WHERE activo = 1
             ORDER BY nombre"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerServiciosPeluqueros(): array
    {
        return $this->pdo->query(
            "SELECT id_servicio AS id, nombre, precio
             FROM servicios
             WHERE activo = 1 AND especialidad = 'peluqueria'
             ORDER BY nombre"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerServiciosTrenzas(): array
    {
        return $this->pdo->query(
            "SELECT id_servicio AS id, nombre, precio
             FROM servicios
             WHERE activo = 1 AND especialidad = 'trenzas'
             ORDER BY nombre"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    // Nombre del método sin ñ porque PHP no permite ñ en identificadores antiguos;
    // internamente la consulta sí busca 'uñas'.
    public function obtenerServiciosUnas(): array
    {
        return $this->pdo->query(
            "SELECT id_servicio AS id, nombre, precio
             FROM servicios
             WHERE activo = 1 AND especialidad = 'uñas'
             ORDER BY nombre"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Devuelve todos los servicios activos que no son bonos.
     * Se usa para empleados con especialidad 'todas'.
     */
    public function obtenerTodosLosServicios(): array
    {
        return $this->pdo->query(
            "SELECT id_servicio AS id, nombre, precio
             FROM servicios
             WHERE activo = 1 AND especialidad <> 'bono'
             ORDER BY especialidad, nombre"
        )->fetchAll(PDO::FETCH_ASSOC);
    }
}