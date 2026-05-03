<?php

class ModeloInforme
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Total de ventas y ingresos
    public function resumenGeneral(): array
    {
        $row = $this->pdo->query(
            "SELECT COUNT(*) as total_ventas, COALESCE(SUM(total), 0) as ingresos_totales FROM ventas"
        )->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    // Servicios más vendidos
    public function serviciosMasVendidos(): array
    {
        return $this->pdo->query(
            "SELECT s.nombre, SUM(ds.cantidad) as unidades, SUM(ds.subtotal) as ingresos
             FROM detalle_servicio ds
             JOIN servicios s ON s.id_servicio = ds.id_servicio
             GROUP BY ds.id_servicio
             ORDER BY unidades DESC
             LIMIT 10"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    // Productos más vendidos
    public function productosMasVendidos(): array
    {
        return $this->pdo->query(
            "SELECT p.nombre, SUM(dp.cantidad) as unidades, SUM(dp.subtotal) as ingresos
             FROM detalle_producto dp
             JOIN productos p ON p.id_producto = dp.id_producto
             GROUP BY dp.id_producto
             ORDER BY unidades DESC
             LIMIT 10"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ventas por empleado
    public function ventasPorEmpleado(): array
    {
        return $this->pdo->query(
            "SELECT u.nombre, COUNT(v.id_venta) as ventas, COALESCE(SUM(v.total), 0) as ingresos
             FROM usuarios u
             LEFT JOIN ventas v ON v.id_usuario = u.id_usuario
             WHERE u.rol = 'empleado'
             GROUP BY u.id_usuario
             ORDER BY ingresos DESC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ventas por día (últimos 30 días)
    public function ventasPorDia(): array
    {
        return $this->pdo->query(
            "SELECT DATE(fecha) as dia, COUNT(*) as ventas, SUM(total) as ingresos
             FROM ventas
             WHERE fecha >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             GROUP BY DATE(fecha)
             ORDER BY dia ASC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }
}