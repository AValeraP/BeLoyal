<?php

require_once __DIR__ . '/../Modelo/ModeloUsuario.php';

class ControladorAutenticacion
{
    private $model;

    public function __construct()
    {
        $this->model = new UsuarioModel();
    }

    /**
     * Muestra el formulario de login (GET).
     */
    public function index(): void
    {
        // Si ya hay sesión activa, redirigimos directamente
        if (!empty($_SESSION['usuario'])) {
            $this->redirigirSegunRol($_SESSION['usuario']['rol']);
        }

        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        require_once __DIR__ . '/../Vista/LogIn.php';
    }

    /**
     * Procesa el formulario de login (POST).
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=login');
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validación básica de campos vacíos
        if ($email === '' || $password === '') {
            $_SESSION['login_error'] = 'Por favor, rellena todos los campos.';
            header('Location: index.php?page=login');
            exit;
        }

        $usuario = $this->model->buscarPorEmail($email);

        // Verificamos existencia, contraseña y que el usuario esté activo
if (!$usuario || $password !== $usuario['password'] || !$usuario['activo']) {            $_SESSION['login_error'] = 'Credenciales incorrectas';
            header('Location: index.php?page=login');
            exit;
        }

        // Guardamos datos mínimos en sesión (nunca la contraseña)
        $_SESSION['usuario'] = [
            'id'     => $usuario['id_usuario'],
            'nombre' => $usuario['nombre'],
            'email'  => $usuario['email'],
            'rol'    => $usuario['rol'],
        ];

        $this->redirigirSegunRol($usuario['rol']);
    }

    /**
     * Cierra la sesión y devuelve al login.
     */
    public function logout(): void
    {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function redirigirSegunRol(string $rol): void
    {
        if ($rol === 'admin') {
            header('Location: index.php?page=admin');
        } else {
            header('Location: index.php?page=empleado');
        }
        exit;
    }
}