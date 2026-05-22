<?php

require_once __DIR__ . '/../Conn/conexion.php';
require_once __DIR__ . '/../Modelo/ModeloServicio.php';
require_once __DIR__ . '/../Modelo/ModeloProductoAdmin.php';
require_once __DIR__ . '/../Modelo/ModeloInforme.php';
require_once __DIR__ . '/../Modelo/ModeloTrabajador.php';

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
        $periodo  = in_array($_GET['periodo'] ?? '', ['dia', 'semana', 'mes', 'ano', 'todo'])
                    ? ($_GET['periodo'] ?? 'todo')
                    : 'todo';
        unset($_SESSION['admin_msg']);

        $modeloServicio   = new ModeloServicio($this->pdo);
        $modeloProducto   = new ModeloProductoAdmin($this->pdo);
        $modeloInforme    = new ModeloInforme($this->pdo);
        $modeloTrabajador = new ModeloTrabajador($this->pdo);

        $empleados    = $modeloTrabajador->obtenerTodos();
        $servicios    = $modeloServicio->obtenerTodos();
        $productos    = $modeloProducto->obtenerTodos();
        $resumen      = $modeloInforme->resumenGeneral($periodo);
        $svcVendidos  = $modeloInforme->serviciosMasVendidos($periodo);
        $prodVendidos = $modeloInforme->productosMasVendidos($periodo);
        $porEmpleado  = $modeloInforme->ventasPorEmpleado($periodo);
        $porDia       = $modeloInforme->ventasPorDia($periodo);
        $porMetodo        = $modeloInforme->ingresosPorMetodo($periodo);
        $ventasRecientes  = $modeloInforme->ventasRecientes(25);

        $editServicio = null;
        $editProducto = null;
        $editEmpleado = null;

        if ($seccion === 'servicios' && isset($_GET['editar'])) {
            $editServicio = $modeloServicio->obtenerPorId((int)$_GET['editar']);
        }
        if ($seccion === 'productos' && isset($_GET['editar'])) {
            $editProducto = $modeloProducto->obtenerPorId((int)$_GET['editar']);
        }
        if ($seccion === 'empleados' && isset($_GET['editar'])) {
            $editEmpleado = $modeloTrabajador->obtenerPorId((int)$_GET['editar']);
        }

        require_once __DIR__ . '/../Vista/PanelAdmin.php';
    }

    // ── SERVICIOS ─────────────────────────────────────────────────────────────

    public function crearServicio(): void
    {
        csrf_check();
        $modelo = new ModeloServicio($this->pdo);
        $modelo->crear(trim($_POST['nombre']), (float)$_POST['precio'], (int)$_POST['duracion'], trim($_POST['especialidad'] ?? 'peluqueria'));
        $_SESSION['admin_msg'] = 'Servicio creado correctamente.';
        header('Location: index.php?page=admin&seccion=servicios');
        exit;
    }

    public function actualizarServicio(): void
    {
        csrf_check();
        $modelo = new ModeloServicio($this->pdo);
        $modelo->actualizar((int)$_POST['id'], trim($_POST['nombre']), (float)$_POST['precio'], (int)$_POST['duracion'], trim($_POST['especialidad'] ?? 'peluqueria'));
        $_SESSION['admin_msg'] = 'Servicio actualizado correctamente.';
        header('Location: index.php?page=admin&seccion=servicios');
        exit;
    }

    public function eliminarServicio(): void
    {
        $modelo = new ModeloServicio($this->pdo);
        $modelo->eliminar((int)$_GET['id']);
        $_SESSION['admin_msg'] = 'Servicio eliminado.';
        header('Location: index.php?page=admin&seccion=servicios');
        exit;
    }

    public function activarServicio(): void
    {
        $modelo = new ModeloServicio($this->pdo);
        $modelo->activar((int)$_GET['id']);
        $_SESSION['admin_msg'] = 'Servicio activado.';
        header('Location: index.php?page=admin&seccion=servicios');
        exit;
    }

    // ── PRODUCTOS ─────────────────────────────────────────────────────────────

    public function crearProducto(): void
    {
        csrf_check();
        $modelo = new ModeloProductoAdmin($this->pdo);
        $modelo->crear(trim($_POST['nombre']), (float)$_POST['precio'], (int)$_POST['stock']);
        $_SESSION['admin_msg'] = 'Producto creado correctamente.';
        header('Location: index.php?page=admin&seccion=productos');
        exit;
    }

    public function actualizarProducto(): void
    {
        csrf_check();
        $modelo = new ModeloProductoAdmin($this->pdo);
        $modelo->actualizar((int)$_POST['id'], trim($_POST['nombre']), (float)$_POST['precio'], (int)$_POST['stock']);
        $_SESSION['admin_msg'] = 'Producto actualizado correctamente.';
        header('Location: index.php?page=admin&seccion=productos');
        exit;
    }

    public function eliminarProducto(): void
    {
        $modelo = new ModeloProductoAdmin($this->pdo);
        $modelo->eliminar((int)$_GET['id']);
        $_SESSION['admin_msg'] = 'Producto eliminado.';
        header('Location: index.php?page=admin&seccion=productos');
        exit;
    }

    public function activarProducto(): void
    {
        $modelo = new ModeloProductoAdmin($this->pdo);
        $modelo->activar((int)$_GET['id']);
        $_SESSION['admin_msg'] = 'Producto activado.';
        header('Location: index.php?page=admin&seccion=productos');
        exit;
    }

    // ── EMPLEADOS ─────────────────────────────────────────────────────────────

    public function crearEmpleado(): void
    {
        csrf_check();
        $modelo = new ModeloTrabajador($this->pdo);

        try {
            $id = $modelo->crear(trim($_POST['nombre']), trim($_POST['email']), trim($_POST['password']), trim($_POST['especialidad']));
        } catch (PDOException $e) {
            $_SESSION['admin_msg'] = (str_contains($e->getMessage(), 'Duplicate') || str_contains($e->getMessage(), 'UNIQUE'))
                ? 'Ya existe un empleado con ese email.'
                : 'No se pudo crear el empleado: ' . $e->getMessage();
            header('Location: index.php?page=admin&seccion=empleados');
            exit;
        }

        if (isset($_FILES['foto_empleado']) && $_FILES['foto_empleado']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['foto_empleado']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $filename = 'emp_' . $id . '_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['foto_empleado']['tmp_name'], __DIR__ . '/../public/img/logos/' . $filename);
                $modelo->actualizarLogo($id, $filename);
            }
        }

        $_SESSION['admin_msg'] = 'Empleado creado correctamente.';
        header('Location: index.php?page=admin&seccion=empleados');
        exit;
    }

    public function actualizarEmpleado(): void
    {
        csrf_check();
        $id     = (int)$_POST['id'];
        $modelo = new ModeloTrabajador($this->pdo);

        try {
            $modelo->actualizar($id, trim($_POST['nombre']), trim($_POST['email']), trim($_POST['especialidad']), isset($_POST['activo']));
        } catch (PDOException $e) {
            $_SESSION['admin_msg'] = (str_contains($e->getMessage(), 'Duplicate') || str_contains($e->getMessage(), 'UNIQUE'))
                ? 'Ya existe otro empleado con ese email.'
                : 'No se pudo actualizar el empleado: ' . $e->getMessage();
            header('Location: index.php?page=admin&seccion=empleados&editar=' . $id);
            exit;
        }

        if (!empty(trim($_POST['password']))) {
            $modelo->actualizarPassword($id, trim($_POST['password']));
        }

        // Quitar foto
        if (!empty($_POST['quitar_foto'])) {
            $emp = $modelo->obtenerPorId($id);
            if (!empty($emp['logo'])) {
                @unlink(__DIR__ . '/../public/img/logos/' . $emp['logo']);
            }
            $modelo->actualizarLogo($id, null);
        } elseif (isset($_FILES['foto_empleado']) && $_FILES['foto_empleado']['error'] === UPLOAD_ERR_OK) {
            $ext  = strtolower(pathinfo($_FILES['foto_empleado']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $emp  = $modelo->obtenerPorId($id);
                if (!empty($emp['logo'])) {
                    @unlink(__DIR__ . '/../public/img/logos/' . $emp['logo']);
                }
                $filename = 'emp_' . $id . '_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['foto_empleado']['tmp_name'], __DIR__ . '/../public/img/logos/' . $filename);
                $modelo->actualizarLogo($id, $filename);
            }
        }

        $_SESSION['admin_msg'] = 'Empleado actualizado correctamente.';
        header('Location: index.php?page=admin&seccion=empleados');
        exit;
    }

    public function activarEmpleado(): void
    {
        $modelo = new ModeloTrabajador($this->pdo);
        $modelo->activar((int)$_GET['id']);
        $_SESSION['admin_msg'] = 'Empleado activado.';
        header('Location: index.php?page=admin&seccion=empleados');
        exit;
    }

    public function eliminarEmpleado(): void
    {
        $modelo = new ModeloTrabajador($this->pdo);
        $modelo->desactivar((int)$_GET['id']);
        $_SESSION['admin_msg'] = 'Empleado desactivado.';
        header('Location: index.php?page=admin&seccion=empleados');
        exit;
    }

    public function eliminarDefinitivamenteEmpleado(): void
    {
        $modelo = new ModeloTrabajador($this->pdo);
        $id     = (int)$_GET['id'];

        if ($modelo->tieneVentas($id)) {
            $_SESSION['admin_msg'] = 'No se puede eliminar: el empleado tiene ventas registradas.';
            header('Location: index.php?page=admin&seccion=empleados');
            exit;
        }

        $modelo->eliminarDefinitivamente($id);
        $_SESSION['admin_msg'] = 'Empleado eliminado definitivamente.';
        header('Location: index.php?page=admin&seccion=empleados');
        exit;
    }

    public function resetearVentas(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin&seccion=dashboard');
            exit;
        }
        csrf_check();

        $passwordIntroducida = $_POST['password_confirm'] ?? '';
        $stmt = $this->pdo->prepare("SELECT id_usuario, password FROM usuarios WHERE email = :email AND rol = 'admin' LIMIT 1");
        $stmt->execute([':email' => $_SESSION['usuario']['email']]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Soporta hashes bcrypt y, transitoriamente, texto plano (legacy)
        $passwordOk = false;
        if ($admin) {
            if (preg_match('/^\$2[ayb]\$/', $admin['password'])) {
                $passwordOk = password_verify($passwordIntroducida, $admin['password']);
            } elseif (hash_equals((string)$admin['password'], $passwordIntroducida)) {
                $passwordOk = true;
                // Migración silenciosa a bcrypt
                $upd = $this->pdo->prepare("UPDATE usuarios SET password = :p WHERE id_usuario = :id");
                $upd->execute([
                    ':p'  => password_hash($passwordIntroducida, PASSWORD_BCRYPT),
                    ':id' => $admin['id_usuario'],
                ]);
            }
        }

        if (!$passwordOk) {
            $_SESSION['admin_msg'] = 'Contraseña incorrecta. No se han eliminado las ventas.';
            header('Location: index.php?page=admin&seccion=dashboard');
            exit;
        }

        $this->pdo->exec("DELETE FROM detalle_servicio");
        $this->pdo->exec("DELETE FROM detalle_producto");
        $this->pdo->exec("DELETE FROM ventas");
        $_SESSION['admin_msg'] = 'Todas las ventas han sido eliminadas.';
        header('Location: index.php?page=admin&seccion=dashboard');
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