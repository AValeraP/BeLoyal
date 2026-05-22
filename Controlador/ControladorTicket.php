<?php

require_once __DIR__ . '/../Conn/conexion.php';
require_once __DIR__ . '/../Servicios/ServicioQuaderno.php';

/**
 * ControladorTicket.php
 * Endpoint: POST index.php?page=enviar_ticket
 *
 * Recibe los datos de la venta + email del cliente
 * y llama a Quaderno para emitir y enviar el ticket PDF.
 */
class ControladorTicket
{
    public function __construct()
    {
        if (empty($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'empleado') {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'No autorizado']);
            exit;
        }
    }

    public function enviar(): void
    {
        header('Content-Type: application/json');

        $body = json_decode(file_get_contents('php://input'), true);

        $emailCliente  = trim($body['email_cliente']  ?? '');
        $nombreCliente = trim($body['nombre_cliente'] ?? 'Cliente');
        $idVenta       = (int) ($body['id_venta']    ?? 0);
        $metodoPago    = $body['metodo_pago']          ?? 'credit_card';
        $total         = (float) ($body['total']      ?? 0);
        $items         = $body['items']                ?? [];

        if (empty($emailCliente) || empty($items)) {
            echo json_encode(['ok' => false, 'error' => 'Email e items son obligatorios']);
            exit;
        }

        $quaderno   = new ServicioQuaderno();
        $resultado  = $quaderno->emitirTicket(
            items:         $items,
            total:         $total,
            metodoPago:    $metodoPago,
            emailCliente:  $emailCliente,
            nombreCliente: $nombreCliente,
            idVenta:       $idVenta
        );

        echo json_encode($resultado);
        exit;
    }
}
