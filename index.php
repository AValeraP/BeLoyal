<?php
session_start();
error_reporting(E_ALL);

// Enrutador simple: lee el parámetro "page" de la URL
$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'login':
        require_once 'Controlador/ControladorAutenticacion.php';
        $controller = new ControladorAutenticacion();
        $controller->index();
        break;

    case 'login_post':
        require_once 'Controlador/ControladorAutenticacion.php';
        $controller = new ControladorAutenticacion();
        $controller->login();
        break;

    case 'logout':
        require_once 'Controlador/ControladorAutenticacion.php';
        $controller = new ControladorAutenticacion();
        $controller->logout();
        break;

    case 'admin':
        require_once 'Controlador/ControladorAdmin.php';
        $controller = new ControladorAdmin();
        $controller->index();
        break;

    case 'empleado':
        require_once 'Controlador/ControladorEmpleado.php';
        $controller = new ControladorEmpleado();
        $controller->index();
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada.";
        break;
}