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
        csrf_check();

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $_SESSION['login_error'] = 'Por favor, rellena todos los campos.';
            header('Location: index.php?page=login');
            exit;
        }

        $usuario = $this->model->buscarPorEmail($email);

        if (!$usuario || !$this->verificarPassword($password, $usuario)) {
            $_SESSION['login_error'] = 'Email o contraseña incorrectos.';
            header('Location: index.php?page=login');
            exit;
        }

        if (!$usuario['activo']) {
            $_SESSION['login_error'] = 'Esta cuenta está desactivada. Contacta con el administrador.';
            header('Location: index.php?page=login');
            exit;
        }

        // Mitiga session fixation
        session_regenerate_id(true);

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
        // Limpieza completa de la sesión
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }

    /**
     * Verifica el password contra el hash almacenado.
     * Si en la BD aún hay contraseñas en texto plano (sistema antiguo),
     * las migra automáticamente a bcrypt en el primer login exitoso.
     */
    private function verificarPassword(string $password, array $usuario): bool
    {
        $almacenado = $usuario['password'];

        // Hashes bcrypt empiezan por $2y$, $2a$ o $2b$
        if (preg_match('/^\$2[ayb]\$/', $almacenado)) {
            return password_verify($password, $almacenado);
        }

        // Compatibilidad con contraseñas en texto plano (legacy)
        if (hash_equals((string)$almacenado, $password)) {
            // Migración automática: rehasheamos con bcrypt
            $this->model->actualizarHash(
                (int)$usuario['id_usuario'],
                password_hash($password, PASSWORD_BCRYPT)
            );
            return true;
        }

        return false;
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