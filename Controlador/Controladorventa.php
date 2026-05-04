<?php

require_once __DIR__ . '/../Conn/conexion.php';

class ControladorVenta
{
    private PDO $pdo;

    public function __construct()
    {
        if (empty($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'empleado') {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'No autorizado']);
            exit;
        }

        global $pdo;
        $this->pdo = $pdo;
    }

    public function registrar(): void
    {
        header('Content-Type: application/json');

        $body = json_decode(file_get_contents('php://input'), true);

        if (empty($body['items']) || empty($body['total']) || empty($body['metodo_pago'])) {
            echo json_encode(['ok' => false, 'error' => 'Datos incompletos']);
            exit;
        }

        $idUsuario  = $_SESSION['usuario']['id'];
        $total      = (float) $body['total'];
        $metodoPago = $body['metodo_pago'];
        $items      = $body['items'];

        try {
            $this->pdo->beginTransaction();

            // 1. Insertar venta
            $stmt = $this->pdo->prepare(
                "INSERT INTO ventas (total, metodo_pago, id_empleado, id_usuario)
                 VALUES (:total, :metodo_pago, :id_empleado, :id_usuario)"
            );
            $stmt->execute([
                ':total'       => $total,
                ':metodo_pago' => $metodoPago,
                ':id_empleado' => $idUsuario,
                ':id_usuario'  => $idUsuario,
            ]);
            $idVenta = (int) $this->pdo->lastInsertId();

            // 2. Insertar detalles según tipo de item
            foreach ($items as $item) {
                $tipo      = $item['tipo'];       // 'servicio' o 'producto'
                $idRef     = (int) $item['id'];   // id real en BD
                $cantidad  = (int) $item['cantidad'];
                $precio    = (float) $item['precio'];
                $subtotal  = $precio * $cantidad;

                if ($tipo === 'servicio') {
                    $s = $this->pdo->prepare(
                        "INSERT INTO detalle_servicio (id_venta, id_servicio, cantidad, precio_unitario, subtotal)
                         VALUES (:id_venta, :id_servicio, :cantidad, :precio_unitario, :subtotal)"
                    );
                    $s->execute([
                        ':id_venta'       => $idVenta,
                        ':id_servicio'    => $idRef,
                        ':cantidad'       => $cantidad,
                        ':precio_unitario'=> $precio,
                        ':subtotal'       => $subtotal,
                    ]);
                } elseif ($tipo === 'producto') {
                    $s = $this->pdo->prepare(
                        "INSERT INTO detalle_producto (id_venta, id_producto, cantidad, precio_unitario, subtotal)
                         VALUES (:id_venta, :id_producto, :cantidad, :precio_unitario, :subtotal)"
                    );
                    $s->execute([
                        ':id_venta'       => $idVenta,
                        ':id_producto'    => $idRef,
                        ':cantidad'       => $cantidad,
                        ':precio_unitario'=> $precio,
                        ':subtotal'       => $subtotal,
                    ]);
                }
            }

            $this->pdo->commit();
            echo json_encode(['ok' => true, 'id_venta' => $idVenta]);

        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }

        exit;
    }
}