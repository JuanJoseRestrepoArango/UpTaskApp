<?php
namespace Controllers;

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


        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        }
        //REnder a la vista
        $router->render('auth/crear',[
            'titulo'=>'Crear Cuenta'
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
        $router->render('auth/confirmar',[
            'titulo'=>'Confirmacion'
        ]);

    }
}