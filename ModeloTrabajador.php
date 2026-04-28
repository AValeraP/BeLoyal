<?php

class ModeloTrabajador
{
    // Para añadir un trabajador nuevo, añade una entrada aquí.
    // 'bonos' => true  si el trabajador vende bonos
    // 'unas'  => true  si el trabajador hace uñas (solo ve bebidas + uñas en el futuro)
    private array $trabajadores = [
        [
            'id'     => 'luis',
            'nombre' => 'Luis',
            'rol'    => 'Barbero',
            'bonos'  => true,
        ],
        [
            'id'     => 'maria',
            'nombre' => 'María',
            'rol'    => 'Estilista',
            'bonos'  => false,
        ],
        [
            'id'     => 'sofia',
            'nombre' => 'Sofía',
            'rol'    => 'Manicurista',
            'bonos'  => false,
        ],
    ];

    public function obtenerTodos(): array
    {
        return $this->trabajadores;
    }
}
