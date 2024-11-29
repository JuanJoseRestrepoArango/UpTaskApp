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

    public static function recuperar(){
        echo "Desde recuperar";

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        }
    }
    public static function reestablecer(){
        echo "Desde reestablecer";

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        }
    }
    public static function mensaje(){
        echo "Desde mensaje";

    }
    public static function confirmar(){
        echo "Desde confirmar";

    }
}