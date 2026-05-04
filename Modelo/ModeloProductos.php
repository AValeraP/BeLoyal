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

    public function obtenerServiciosPeluqueros(): array
    {
        return json_decode(file_get_contents($this->dir . 'servicios_peluqueros.json'), true) ?? [];
    }

    public function obtenerServiciosTrenzas(): array
    {
        return json_decode(file_get_contents($this->dir . 'servicios_trenzas.json'), true) ?? [];
    }

    public function obtenerServiciosUnas(): array
    {
        return json_decode(file_get_contents($this->dir . 'servicios_unas.json'), true) ?? [];
    }
}
