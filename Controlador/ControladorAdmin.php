<?php

require_once __DIR__ . '/../Conn/conexion.php';
require_once __DIR__ . '/../Modelo/ModeloServicio.php';
require_once __DIR__ . '/../Modelo/ModeloProductoAdmin.php';
require_once __DIR__ . '/../Modelo/ModeloInforme.php';

class ControladorAdmin
{
    private PDO $pdo;

    public function __construct()
    {
        $this->proteger('admin');
        global $pdo;
        $this->pdo = $pdo;
    }

    public function index(): void
    {
        $usuario  = $_SESSION['usuario'];
        $seccion  = $_GET['seccion'] ?? 'dashboard';
        $mensaje  = $_SESSION['admin_msg'] ?? null;
        unset($_SESSION['admin_msg']);

        $modeloServicio  = new ModeloServicio($this->pdo);
        $modeloProducto  = new ModeloProductoAdmin($this->pdo);
        $modeloInforme   = new ModeloInforme($this->pdo);

        // Datos según sección
        $servicios       = $modeloServicio->obtenerTodos();
        $productos       = $modeloProducto->obtenerTodos();
        $resumen         = $modeloInforme->resumenGeneral();
        $svcVendidos     = $modeloInforme->serviciosMasVendidos();
        $prodVendidos    = $modeloInforme->productosMasVendidos();
        $porEmpleado     = $modeloInforme->ventasPorEmpleado();
        $porDia          = $modeloInforme->ventasPorDia();

        // Servicio/producto a editar
        $editServicio = null;
        $editProducto = null;
        if ($seccion === 'servicios' && isset($_GET['editar'])) {
            $editServicio = $modeloServicio->obtenerPorId((int)$_GET['editar']);
        }
        if ($seccion === 'productos' && isset($_GET['editar'])) {
            $editProducto = $modeloProducto->obtenerPorId((int)$_GET['editar']);
        }

        require_once __DIR__ . '/../Vista/PanelAdmin.php';
    }

    // ── SERVICIOS ─────────────────────────────────────────────────────────────

    public function crearServicio(): void
    {
        $modelo = new ModeloServicio($this->pdo);
        $modelo->crear(
            trim($_POST['nombre']),
            (float) $_POST['precio'],
            (int)   $_POST['duracion']
        );
        $_SESSION['admin_msg'] = '✓ Servicio creado correctamente.';
        header('Location: index.php?page=admin&seccion=servicios');
        exit;
    }

    public function actualizarServicio(): void
    {
        $modelo = new ModeloServicio($this->pdo);
        $modelo->actualizar(
            (int)   $_POST['id'],
            trim($_POST['nombre']),
            (float) $_POST['precio'],
            (int)   $_POST['duracion']
        );
        $_SESSION['admin_msg'] = '✓ Servicio actualizado correctamente.';
        header('Location: index.php?page=admin&seccion=servicios');
        exit;
    }

    public function eliminarServicio(): void
    {
        $modelo = new ModeloServicio($this->pdo);
        $modelo->eliminar((int) $_GET['id']);
        $_SESSION['admin_msg'] = '✓ Servicio eliminado.';
        header('Location: index.php?page=admin&seccion=servicios');
        exit;
    }

    // ── PRODUCTOS ─────────────────────────────────────────────────────────────

    public function crearProducto(): void
    {
        $modelo = new ModeloProductoAdmin($this->pdo);
        $modelo->crear(
            trim($_POST['nombre']),
            (float) $_POST['precio'],
            (int)   $_POST['stock']
        );
        $_SESSION['admin_msg'] = '✓ Producto creado correctamente.';
        header('Location: index.php?page=admin&seccion=productos');
        exit;
    }

    public function actualizarProducto(): void
    {
        $modelo = new ModeloProductoAdmin($this->pdo);
        $modelo->actualizar(
            (int)   $_POST['id'],
            trim($_POST['nombre']),
            (float) $_POST['precio'],
            (int)   $_POST['stock']
        );
        $_SESSION['admin_msg'] = '✓ Producto actualizado correctamente.';
        header('Location: index.php?page=admin&seccion=productos');
        exit;
    }

    public function eliminarProducto(): void
    {
        $modelo = new ModeloProductoAdmin($this->pdo);
        $modelo->eliminar((int) $_GET['id']);
        $_SESSION['admin_msg'] = '✓ Producto eliminado.';
        header('Location: index.php?page=admin&seccion=productos');
        exit;
    }

    private function proteger(string $rol): void
    {
        if (empty($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== $rol) {
            header('Location: index.php?page=login');
            exit;
        }
    }
}