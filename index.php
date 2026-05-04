<?php
session_start();
error_reporting(E_ALL);

require_once 'Conn/conexion.php';

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

    case 'empleado':
        require_once 'Controlador/ControladorEmpleado.php';
        (new ControladorEmpleado())->index();
        break;

    case 'registrar_venta':
        require_once 'Controlador/ControladorVenta.php';
        (new ControladorVenta())->registrar();
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada.";
        break;
}