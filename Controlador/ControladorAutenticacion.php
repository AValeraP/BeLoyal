<?php

require_once __DIR__ . '/../Conn/conexion.php';
require_once __DIR__ . '/../Modelo/ModeloUsuario.php';

class ControladorAutenticacion
{
    private ModeloUsuario $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new ModeloUsuario($pdo);
    }

    public function index(): void
    {
        if (!empty($_SESSION['usuario'])) {
            $this->redirigirSegunRol($_SESSION['usuario']['rol']);
        }

        $mensajeerror = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        require_once __DIR__ . '/../Vista/LogIn.php';
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=login');
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $_SESSION['login_error'] = 'Por favor, rellena todos los campos.';
            header('Location: index.php?page=login');
            exit;
        }

        $usuario = $this->model->buscarPorEmail($email);

        if (!$usuario || $password !== $usuario['password'] || !$usuario['activo']) {
            $_SESSION['login_error'] = 'Email o contraseña incorrectos.';
            header('Location: index.php?page=login');
            exit;
        }

        $_SESSION['usuario'] = [
            'id'          => $usuario['id_usuario'],
            'nombre'      => $usuario['nombre'],
            'email'       => $usuario['email'],
            'rol'         => $usuario['rol'],
            'especialidad'=> $usuario['especialidad'],
        ];

        $this->redirigirSegunRol($usuario['rol']);
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }

    private function redirigirSegunRol(string $rol): void
    {
        header($rol === 'admin'
            ? 'Location: index.php?page=admin'
            : 'Location: index.php?page=empleado'
        );
        exit;
    }
}