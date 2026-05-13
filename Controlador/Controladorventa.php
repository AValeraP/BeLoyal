<?php

require_once __DIR__ . '/../Conn/conexion.php';
require_once __DIR__ . '/../Servicios/ServicioQuaderno.php';

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

        $idUsuario     = $_SESSION['usuario']['id'];
        $total         = (float) $body['total'];
        $metodoPago    = $body['metodo_pago'];
        $items         = $body['items'];
        $emailCliente  = trim($body['email_cliente']  ?? '');
        $nombreCliente = trim($body['nombre_cliente'] ?? 'Cliente');

        try {
            $this->pdo->beginTransaction();

            // ── 1. Validar stock de todos los productos antes de vender ──
            foreach ($items as $item) {
                if ($item['tipo'] !== 'producto') continue;

                $idRef    = (int) $item['id'];
                $cantidad = (int) $item['cantidad'];

                $check = $this->pdo->prepare(
                    "SELECT nombre, stock FROM productos WHERE id_producto = :id AND activo = 1"
                );
                $check->execute([':id' => $idRef]);
                $producto = $check->fetch(PDO::FETCH_ASSOC);

                if (!$producto) {
                    $this->pdo->rollBack();
                    echo json_encode(['ok' => false, 'error' => 'Producto no disponible']);
                    exit;
                }

                if ($producto['stock'] < $cantidad) {
                    $this->pdo->rollBack();
                    echo json_encode([
                        'ok'    => false,
                        'error' => "Stock insuficiente de \"{$producto['nombre']}\". Solo quedan {$producto['stock']} unidades.",
                    ]);
                    exit;
                }
            }

            // ── 2. Insertar venta en BD ─────────────────────────────────
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

            // ── 3. Insertar detalles y actualizar stock ──────────────────
            foreach ($items as $item) {
                $tipo     = $item['tipo'];
                $idRef    = (int) $item['id'];
                $cantidad = (int) $item['cantidad'];
                $precio   = (float) $item['precio'];
                $subtotal = $precio * $cantidad;

                if ($tipo === 'servicio') {
                    $s = $this->pdo->prepare(
                        "INSERT INTO detalle_servicio (id_venta, id_servicio, cantidad, precio_unitario, subtotal)
                         VALUES (:id_venta, :id_servicio, :cantidad, :precio_unitario, :subtotal)"
                    );
                    $s->execute([
                        ':id_venta'        => $idVenta,
                        ':id_servicio'     => $idRef,
                        ':cantidad'        => $cantidad,
                        ':precio_unitario' => $precio,
                        ':subtotal'        => $subtotal,
                    ]);
                } elseif ($tipo === 'producto') {
                    $s = $this->pdo->prepare(
                        "INSERT INTO detalle_producto (id_venta, id_producto, cantidad, precio_unitario, subtotal)
                         VALUES (:id_venta, :id_producto, :cantidad, :precio_unitario, :subtotal)"
                    );
                    $s->execute([
                        ':id_venta'        => $idVenta,
                        ':id_producto'     => $idRef,
                        ':cantidad'        => $cantidad,
                        ':precio_unitario' => $precio,
                        ':subtotal'        => $subtotal,
                    ]);

                    // ── Bajar stock ──────────────────────────────────────
                    $upd = $this->pdo->prepare(
                        "UPDATE productos SET stock = GREATEST(stock - :cantidad, 0) WHERE id_producto = :id"
                    );
                    $upd->execute([':cantidad' => $cantidad, ':id' => $idRef]);

                    // ── Desactivar solo si stock llega exactamente a 0 ───
                    $des = $this->pdo->prepare(
                        "UPDATE productos SET activo = 0 WHERE id_producto = :id AND stock = 0"
                    );
                    $des->execute([':id' => $idRef]);
                }
            }

            $this->pdo->commit();

            // ── 4. Emitir ticket en Quaderno ────────────────────────────
            $quaderno   = new ServicioQuaderno();
            $resultadoQ = $quaderno->emitirTicket(
                items:         $items,
                total:         $total,
                metodoPago:    $metodoPago,
                emailCliente:  $emailCliente,
                nombreCliente: $nombreCliente,
                idVenta:       $idVenta
            );

            echo json_encode([
                'ok'             => true,
                'id_venta'       => $idVenta,
                'ticket_enviado' => $resultadoQ['ok'],
                'ticket_error'   => $resultadoQ['error'] ?? null,
            ]);

        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }

        exit;
    }
}