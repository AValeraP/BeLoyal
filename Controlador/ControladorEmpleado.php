<?php

require_once __DIR__ . '/../Conn/conexion.php';
require_once __DIR__ . '/../Modelo/ModeloProductos.php';
require_once __DIR__ . '/../Modelo/ModeloTrabajador.php';

class ControladorEmpleado
{
    public function __construct()
    {
        $this->proteger('empleado');
    }

    public function index(): void
    {
        global $pdo;

        $usuario = $_SESSION['usuario'];

        // Obtiene datos frescos del empleado desde la BD
        $modeloTrabajador = new ModeloTrabajador($pdo);
        $trabajador       = $modeloTrabajador->obtenerPorId($usuario['id']);

        if (!$trabajador) {
            session_destroy();
            header('Location: index.php?page=login');
            exit;
        }

        $especialidad    = $trabajador['especialidad'];
        $modeloProductos = new ModeloProductos($pdo);
        $bebidas         = $modeloProductos->obtenerBebidas();

        // Bonos: solo para peluquería y usuarios con especialidad 'todas'
        $mostrarBonos = in_array($especialidad, ['peluqueria', 'todas']);
        $bonos        = $mostrarBonos ? $modeloProductos->obtenerBonos() : [];

        if ($especialidad === 'todas') {
            $servicios     = $modeloProductos->obtenerTodosLosServicios();
            $seccionTitulo = 'Todos los servicios';
        } elseif ($especialidad === 'peluqueria') {
            $servicios     = $modeloProductos->obtenerServiciosPeluqueros();
            $seccionTitulo = 'Servicios';
        } elseif ($especialidad === 'trenzas') {
            $servicios     = $modeloProductos->obtenerServiciosTrenzas();
            $seccionTitulo = 'Trenzas';
        } elseif ($especialidad === 'uñas') {
            $servicios     = $modeloProductos->obtenerServiciosUnas();
            $seccionTitulo = 'Uñas';
        } else {
            $servicios     = [];
            $seccionTitulo = 'Servicios';
        }

        $config = file_exists(__DIR__ . '/../Conn/config.php')
                ? require __DIR__ . '/../Conn/config.php'
                : [];
        $stripePublicKey = $config['stripe_public_key'] ?? '';

        require_once __DIR__ . '/../Vista/PanelEmpleado.php';
    }

    private function proteger(string $rol): void
    {
        if (empty($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== $rol) {
            header('Location: index.php?page=login');
            exit;
        }
    }
}