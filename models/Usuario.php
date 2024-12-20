<?php

namespace Model;

class Usuario extends ActiveRecord{

    public ?int $id = null;
    public string $nombre = '';
    public string $email = '';
    public string $password = '';
    public string $password2 = '';
    public string $password_actual = '';
    public string $password_nuevo = '';
    public string $token = '';
    public string $confirmado = '';
    protected static $tabla = 'usuarios';

    protected static $columnasDB = [
        'id',
        'nombre',
        'email',
        'password',
        'token',
        'confirmado'
    ];

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    //Validar Login
    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio o no esta confirmado';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El Password del Usuario es Obligatorio';
        }
        if(strlen($this->password) < 8){
            self::$alertas['error'][] = 'El Password debe tener al menos 8 caracteres';
        }
        return self::$alertas;
    }

    //Validacion de usuario cuentas nuevas
    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El Nombre del Usuario es Obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El Password del Usuario es Obligatorio';
        }
        if(strlen($this->password) < 8){
            self::$alertas['error'][] = 'El Password debe tener al menos 8 caracteres';
        }
        if($this->password !== $this->password2){
            self::$alertas['error'][] = 'La Confirmacion del Password no es igual al Password';
        }

        return self::$alertas;
    }
    //ValidarEmail
    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio o no esta confirmado';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio o no esta confirmado';
        }
        return self::$alertas;
    }
    //ValidarPassword
    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = 'El Password del Usuario es Obligatorio';
        }
        if(strlen($this->password) < 8){
            self::$alertas['error'][] = 'El Password debe tener al menos 8 caracteres';
        }
        return self::$alertas;
    }
    //Validar Perfil
    public function validarPerfil(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El Nombre del Usuario es Obligatorio';
        }

        if(!$this->email){
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }

        return self::$alertas;
    }

    public function nuevo_password(): array{
        if(!$this->password_actual){
            self::$alertas['error'][] = 'El Password Actual no puede ir vacio';
        }
        if(!$this->password_nuevo){
            self::$alertas['error'][] = 'El Password Nuevo no puede ir vacio';
        }
        if(strlen($this->password_nuevo) < 8){
            self::$alertas['error'][] = 'El Password Nuevo debe tener al menos 8 caracteres';
        }
        return self::$alertas;
    }
    //Comprobar el password
    public function comprobar_password(): bool{
        return password_verify($this->password_actual, $this->password);
    }
    
    //HAshea el password
    public function hashPassword(): void{
        $this->password = password_hash($this->password,PASSWORD_BCRYPT);
    }
    //Generar un token
    public function crearToken(): void{
        $this->token = md5(uniqid());
    }
}