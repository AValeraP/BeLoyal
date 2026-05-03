<?php

class ModeloProductos
{
    private string $dir;

    public function __construct()
    {
        $this->dir = __DIR__ . '/../Data/';
    }

    public function obtenerBonos(): array
    {
        $data = json_decode(file_get_contents($this->dir . 'bonos.json'), true);
        return $data['bonos'] ?? [];
    }

    public function obtenerBebidas(): array
    {
        return json_decode(file_get_contents($this->dir . 'bebidas.json'), true) ?? [];
    }
}
