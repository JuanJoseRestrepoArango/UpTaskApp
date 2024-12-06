<?php
namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        //REnder a la vista
        $router->render('auth/login',[
            'titulo'=>'Iniciar Sesión'
        ]);
    }


    public static function logout(){
        echo "Desde logout";

    }

    public static function crear(Router $router){
        $alertas = [];
        $usuario = new Usuario;
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)){
                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario){
                    Usuario::setAlerta('error','El Usuario ya esta registrado');
                    $alertas = Usuario :: getAlertas();
                }else{
                    //Hashear passwor
                    $usuario->hashPassword();

                    //Generar el token
                    $usuario->crearToken();
                    
                    //Crear un nuevo usuario
                    $resultado = $usuario->guardar();

                    //Enviar Email
                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarConfirmacion();
                    if ($resultado){
                        header('Location: /mensaje');
                    }
                }
            }

            
        }
        
        //REnder a la vista
        $router->render('auth/crear',[
            'titulo'=>'Crear Cuenta',
            'usuario'=>$usuario,
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        }
        $router->render('auth/recuperar',[
            'titulo'=>'Recuperar Password'
        ]);
    }
    public static function reestablecer(Router $router){

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        $router->render('auth/reestablecer',[
            'titulo'=>'Reestablecer Password'
        ]);
    }
    public static function mensaje(Router $router){
        $router->render('auth/mensaje',[
            'titulo'=>'Cuenta Creada Exitosamente'
        ]);

    }
    public static function confirmar(Router $router){

        $token = s($_GET['token']);
     

        //Encontrar al usuario con el token
        $usuario = Usuario::where('token',$token);
        
        if(empty($usuario)){
            Usuario::setAlerta('error','Token No Valido');
        }else{
            //Condirmar la cuenta
            $usuario->confirmado = 1;
            $usuario->token = '';
            unset($usuario->password2);
            //guardar usuario en la bd
            $usuario->guardar();

            Usuario::setAlerta('exito','Cuenta Comprobada');
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar',[
            'titulo'=>'Confirmacion',
            'alertas'=>$alertas
        ]);

    }
}