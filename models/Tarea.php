<?php

namespace Model;

class Tarea extends ActiveRecord{
    protected static $tabla = 'tareas';

    public ?int $id = null;
    public string $nombre = '';
    public string $estado = '';
    public string $proyectoId = '';

    protected static $columnasDB = [
        'id',
        'nombre',
        'estado',
        'proyectoId'
    ];

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->estado = $args['estado'] ?? 0;
        $this->proyectoId = $args['proyectoId'] ?? '';
    }
}