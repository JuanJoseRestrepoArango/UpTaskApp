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
        
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario = new Usuario(($_POST));

            $alertas = $usuario->validarEmail();

            if(empty($alertas)){
                //Buscar el usuario
                $usuario = Usuario::where('email',$usuario->email);

                if($usuario && $usuario->confirmado === "1"){
                    //Encontro al usuario
                    //Generar u nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    //Actualizar el usuario
                    $usuario->guardar();


                    //Enviar el Email
                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarInstrucciones();

                    //Imprimir la alerta
                    Usuario::setAlerta('exito','Hemos enviado las istrucciones a tu Email');
                }else{
                    //No encontro al usuario
                    Usuario::setAlerta('error','El usuario no es valido');
                    
                }
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar',[
            'titulo'=>'Recuperar Password',
            'alertas'=>$alertas
        ]);
    }
    public static function reestablecer(Router $router){

        $alertas = [];


        $token = s($_GET['token']);
        
        $mostrar = true;

        if(!$token) header('Location: /');

        //Identificar el usuario con este token
        $usuario = Usuario::where('token',$token);

        if(empty($usuario)){
            Usuario::setAlerta('error','Token no valido');
            $mostrar = false;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Añadir el uevo password
            $usuario->sincronizar($_POST);
            
            //Valisdar password
            $alertas = $usuario->validarPassword();

            if(empty($alertas)){
                //Hashear el pasword nuevo 
                $usuario->hashPassword();
                unset($usuario->password2);
                //Eliminar el token
                $usuario->token = "";
                //Guardar el usuario en la BD
                $resultado = $usuario->guardar();
                //Redireccionar
                if($resultado){            
                    header('Location: /');
                }
            }
           
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/reestablecer',[
            'titulo'=>'Reestablecer Password',
            'alertas'=>$alertas,
            'mostrar'=>$mostrar
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