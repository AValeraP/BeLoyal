<?php
// Cookies de sesión seguras (mitiga CSRF y session hijacking)
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();
error_reporting(E_ALL);

require_once 'Conn/conexion.php';
require_once 'Conn/csrf.php';

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

    case 'admin_activar_servicio':
        require_once 'Controlador/ControladorAdmin.php';
        (new ControladorAdmin())->activarServicio();
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

    case 'admin_activar_producto':
        require_once 'Controlador/ControladorAdmin.php';
        (new ControladorAdmin())->activarProducto();
        break;

    case 'admin_crear_empleado':
        require_once 'Controlador/ControladorAdmin.php';
        (new ControladorAdmin())->crearEmpleado();
        break;

    case 'registrar_venta':
        require_once 'Controlador/ControladorVenta.php';
        (new ControladorVenta())->registrar();
        break;

    case 'empleado':
        require_once 'Controlador/ControladorEmpleado.php';
        (new ControladorEmpleado())->index();
        break;

    case 'admin_actualizar_empleado':
        require_once 'Controlador/ControladorAdmin.php';
        (new ControladorAdmin())->actualizarEmpleado();
        break;

    case 'admin_eliminar_empleado':
        require_once 'Controlador/ControladorAdmin.php';
        (new ControladorAdmin())->eliminarEmpleado();
        break;

    case 'admin_resetear_ventas':
        require_once 'Controlador/ControladorAdmin.php';
        (new ControladorAdmin())->resetearVentas();
        break;

    case 'pago_crear_intent':
        require_once 'Controlador/Controladorpago.php';
        (new ControladorPago())->crearIntent();
        break;

    case 'pago_verificar':
        require_once 'Controlador/Controladorpago.php';
        (new ControladorPago())->verificar();
        break;

    case 'pago_registrar':
        require_once 'Controlador/Controladorpago.php';
        (new ControladorPago())->registrarTicket();
        break;

    case 'enviar_ticket':
        require_once 'Controlador/ControladorTicket.php';
        (new ControladorTicket())->enviar();
        break;

    case 'admin_eliminar_definitivo_empleado':
        require_once 'Controlador/ControladorAdmin.php';
        (new ControladorAdmin())->eliminarDefinitivamenteEmpleado();
        break;

    case 'admin_activar_empleado':
        require_once 'Controlador/ControladorAdmin.php';
        (new ControladorAdmin())->activarEmpleado();
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada.";
        break;
}