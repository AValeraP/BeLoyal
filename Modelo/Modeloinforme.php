<?php

class ModeloInforme
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function filtroFecha(string $periodo): string
    {
        return match($periodo) {
            'dia'    => "AND DATE(fecha) = CURDATE()",
            'semana' => "AND YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1)",
            'mes'    => "AND YEAR(fecha) = YEAR(CURDATE()) AND MONTH(fecha) = MONTH(CURDATE())",
            'ano'    => "AND YEAR(fecha) = YEAR(CURDATE())",
            default  => "",
        };
    }

    public function resumenGeneral(string $periodo = 'todo'): array
    {
        $filtro = $this->filtroFecha($periodo);
        return $this->pdo->query(
            "SELECT COUNT(*) as total_ventas, COALESCE(SUM(total), 0) as ingresos_totales
             FROM ventas WHERE 1=1 $filtro"
        )->fetch(PDO::FETCH_ASSOC);
    }

    public function ingresosPorMetodo(string $periodo = 'todo'): array
    {
        $filtro = $this->filtroFecha($periodo);

        $rows = $this->pdo->query(
            "SELECT metodo_pago, COUNT(*) as ventas, COALESCE(SUM(total), 0) as ingresos
             FROM ventas
             WHERE 1=1 $filtro
             GROUP BY metodo_pago"
        )->fetchAll(PDO::FETCH_ASSOC);

        $efectivo = ['ventas' => 0, 'ingresos' => 0.0];
        $tarjeta  = ['ventas' => 0, 'ingresos' => 0.0];

        foreach ($rows as $row) {
            $metodo = strtolower(trim($row['metodo_pago'] ?? ''));
            if (str_contains($metodo, 'efectivo')) {
                $efectivo['ventas']   += (int)   $row['ventas'];
                $efectivo['ingresos'] += (float)  $row['ingresos'];
            } else {
                $tarjeta['ventas']    += (int)   $row['ventas'];
                $tarjeta['ingresos']  += (float)  $row['ingresos'];
            }
        }

        return [
            'efectivo' => $efectivo,
            'tarjeta'  => $tarjeta,
            'detalle'  => $rows,
        ];
    }

    public function serviciosMasVendidos(string $periodo = 'todo'): array
    {
        $filtro = $this->filtroFecha($periodo);
        return $this->pdo->query(
            "SELECT s.nombre, SUM(ds.cantidad) as unidades, SUM(ds.subtotal) as ingresos
             FROM detalle_servicio ds
             JOIN servicios s ON s.id_servicio = ds.id_servicio
             JOIN ventas v ON v.id_venta = ds.id_venta
             WHERE 1=1 $filtro
             GROUP BY ds.id_servicio
             ORDER BY unidades DESC
             LIMIT 10"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function productosMasVendidos(string $periodo = 'todo'): array
    {
        $filtro = $this->filtroFecha($periodo);
        return $this->pdo->query(
            "SELECT p.nombre, SUM(dp.cantidad) as unidades, SUM(dp.subtotal) as ingresos
             FROM detalle_producto dp
             JOIN productos p ON p.id_producto = dp.id_producto
             JOIN ventas v ON v.id_venta = dp.id_venta
             WHERE 1=1 $filtro
             GROUP BY dp.id_producto
             ORDER BY unidades DESC
             LIMIT 10"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ventasPorEmpleado(string $periodo = 'todo'): array
    {
        $filtro = str_replace('fecha', 'v.fecha', $this->filtroFecha($periodo));

        $rows = $this->pdo->query(
            "SELECT
                u.nombre,
                u.logo,
                COUNT(v.id_venta) as ventas,
                COALESCE(SUM(v.total), 0) as ingresos,
                COALESCE(SUM(CASE WHEN LOWER(v.metodo_pago) LIKE '%efectivo%' THEN v.total ELSE 0 END), 0) as ingresos_efectivo,
                COALESCE(SUM(CASE WHEN LOWER(v.metodo_pago) NOT LIKE '%efectivo%' AND v.metodo_pago IS NOT NULL THEN v.total ELSE 0 END), 0) as ingresos_tarjeta
             FROM usuarios u
             LEFT JOIN ventas v ON v.id_usuario = u.id_usuario AND 1=1 $filtro
             WHERE u.rol = 'empleado'
             GROUP BY u.id_usuario
             ORDER BY ingresos DESC"
        )->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    public function ventasPorDia(string $periodo = 'todo'): array
    {
        $filtro = $this->filtroFecha($periodo);
        if ($periodo === 'todo') {
            $filtro = "AND fecha >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        }
        return $this->pdo->query(
            "SELECT DATE(fecha) as dia, COUNT(*) as ventas, SUM(total) as ingresos
             FROM ventas
             WHERE 1=1 $filtro
             GROUP BY DATE(fecha)
             ORDER BY dia ASC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }
}