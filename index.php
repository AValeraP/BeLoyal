<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
error_reporting(E_ALL);

require_once 'Conn/conexion.php';

$page = $_GET['page'] ?? 'login';

switch ($page) {
    // ── Autenticación ────────────────────────────────────────────────────────
    case 'login':
        require_once 'Controlador/ControladorAutenticacion.php';
        (new ControladorAutenticacion($pdo))->index();
        break;

    case 'login_post':
        require_once 'Controlador/ControladorAutenticacion.php';
        (new ControladorAutenticacion($pdo))->login();
        break;

    case 'logout':
        require_once 'Controlador/ControladorAutenticacion.php';
        (new ControladorAutenticacion($pdo))->logout();
        break;

    // ── Admin ────────────────────────────────────────────────────────────────
    case 'admin':
        require_once 'Controlador/ControladorAdmin.php';
        (new ControladorAdmin())->index();
        break;

    case 'admin_crear_servicio':
        require_once 'Controlador/ControladorAdmin.php';
        (new ControladorAdmin())->crearServicio();
        break;

    case 'admin_actualizar_servicio':
        require_once 'Controlador/ControladorAdmin.php';
        (new ControladorAdmin())->actualizarServicio();
        break;

    case 'admin_eliminar_servicio':
        require_once 'Controlador/ControladorAdmin.php';
        (new ControladorAdmin())->eliminarServicio();
        break;

    case 'admin_crear_producto':
        require_once 'Controlador/ControladorAdmin.php';
        (new ControladorAdmin())->crearProducto();
        break;

    case 'admin_actualizar_producto':
        require_once 'Controlador/ControladorAdmin.php';
        (new ControladorAdmin())->actualizarProducto();
        break;

    case 'admin_eliminar_producto':
        require_once 'Controlador/ControladorAdmin.php';
        (new ControladorAdmin())->eliminarProducto();
        break;

    // ── Empleado ─────────────────────────────────────────────────────────────
    case 'empleado':
        require_once 'Controlador/ControladorEmpleado.php';
        (new ControladorEmpleado())->index();
        break;

    // ── Pasarela de pago (NUEVOS) ────────────────────────────────────────────
    case 'pago_crear_intent':
        /**
         * POST /index.php?page=pago_crear_intent
         * Body: { "importe_cents": 1500, "items": [...] }
         * Respuesta: { "clientSecret": "pi_..._secret_...", "intentId": "pi_..." }
         */
        require_once 'Controlador/ControladorPago.php';
        (new ControladorPago())->crearIntent();
        break;

    case 'pago_verificar':
        /**
         * POST /index.php?page=pago_verificar
         * Body: { "payment_intent_id": "pi_..." }
         * Respuesta: { "status": "succeeded", "amount": 1500, ... }
         */
        require_once 'Controlador/ControladorPago.php';
        (new ControladorPago())->verificar();
        break;

    case 'pago_registrar':
        /**
         * POST /index.php?page=pago_registrar
         * Body: { "intent_id": "pi_...", "items": [...], "total": 15.00 }
         * Respuesta: { "ok": true, "intentId": "pi_...", "importe": 15.00 }
         */
        require_once 'Controlador/ControladorPago.php';
        (new ControladorPago())->registrarTicket();
        break;

    // ── 404 ──────────────────────────────────────────────────────────────────
    default:
        http_response_code(404);
        echo "Página no encontrada.";
        break;
}