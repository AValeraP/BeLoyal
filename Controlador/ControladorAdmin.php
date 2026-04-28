<?php

class ControladorAdmin
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->proteger('admin');
        $this->pdo = $pdo;
    }

    public function index(): void
    {
        $usuario = $_SESSION['usuario'];
        require_once __DIR__ . '/../Vista/PanelAdmin.php';
    }

    private function proteger(string $rolRequerido): void
    {
        if (empty($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== $rolRequerido) {
            header('Location: index.php?page=login');
            exit;
        }
    }
}
