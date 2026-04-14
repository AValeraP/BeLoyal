<?php

class ControladorAdmin
{
    public function __construct()
    {
        $this->proteger('admin');
    }

    /**
     * Página principal del admin.
     */
    public function index(): void
    {
        $usuario = $_SESSION['usuario'];
        require_once __DIR__ . '/../vista/PanelAdmin.php';
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Si no hay sesión o el rol no coincide, redirige al login.
     */
    private function proteger(string $rolRequerido): void
    {
        if (empty($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== $rolRequerido) {
            header('Location: index.php?page=login');
            exit;
        }
    }
}