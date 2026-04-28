<?php

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
        $usuario      = $_SESSION['usuario'];
        $modelo       = new ModeloProductos();
        $trabajadores = (new ModeloTrabajador())->obtenerTodos();
        $bonos        = $modelo->obtenerBonos();
        $bebidas      = $modelo->obtenerBebidas();

        require_once __DIR__ . '/../vista/PanelEmpleado.php';
    }

    private function proteger(string $rol): void
    {
        if (empty($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== $rol) {
            header('Location: index.php?page=login');
            exit;
        }
    }
}
