<?php
session_start();
error_reporting(E_ALL);

// ── Conexión única compartida ──────────────────────────────────────────────────
require_once __DIR__ . '/Conn/conexion.php';   // define $pdo

// ── Enrutador ─────────────────────────────────────────────────────────────────
$page = $_GET['page'] ?? 'login';

switch ($page) {

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

    case 'admin':
        require_once 'Controlador/ControladorAdmin.php';
        (new ControladorAdmin($pdo))->index();
        break;

    case 'empleado':
        require_once 'Controlador/ControladorEmpleado.php';
        (new ControladorEmpleado($pdo))->index();
        break;

    case 'guardar_venta':
        require_once 'Controlador/ControladorEmpleado.php';
        (new ControladorEmpleado($pdo))->guardarVenta();
        break;

    default:
        http_response_code(404);
        echo 'Página no encontrada.';
        break;
}
