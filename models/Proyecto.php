<?php

namespace Model;

use Model\ActiveRecord;

class Proyecto extends ActiveRecord{
    protected static $tabla = 'proyectos';

    
    public ?int $id = null;
    public string $proyecto = '';
    public string $url = '';
    public ?int $propietarioId = null;


    protected static $columnasDB = [
        'id',
        'proyecto',
        'url',
        'propietarioId'
    ];

    public function __construct($args = []){
        $this->id = $args ['id'] ?? null;
        $this->proyecto = $args ['proyecto'] ?? '';
        $this->url = $args ['url'] ?? '';
        $this->propietarioId = $args ['propietarioId'] ?? null;
    }

    public function validarProyecto(){
        if(!$this->proyecto){
            self::$alertas['error'][] = 'El Nombre del Proyecto es Obligatorio';
        }

        return self::$alertas;
    }
}
