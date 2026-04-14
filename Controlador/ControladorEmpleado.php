<?php

class ControladorEmpleado
{
    public function __construct()
    {
        $this->proteger('empleado');
    }

    /**
     * Página principal del empleado.
     */
    public function index(): void
    {
        $usuario = $_SESSION['usuario'];
        require_once __DIR__ . '/../vista/PanelEmpleado.php';
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function proteger(string $rolRequerido): void
    {
        if (empty($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== $rolRequerido) {
            header('Location: index.php?page=login');
            exit;
        }
    }
}