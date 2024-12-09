<?php

namespace Controllers;

use Model\Proyecto;
use MVC\Router;

class DashboardController{
    public static function index(Router $router){


        session_start();

        isAuth();

        $router->render('dashboard/index',[
            'titulo' => 'Proyectos'
        ]);
    }

    
    public static function crear_proyecto(Router $router){
        session_start();

        isAuth();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);
            
            //Validacion 
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)){

                //Generar url unica
                $proyecto->url = md5(uniqid());
                //Almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];
                //Guardar proyecto
                $proyecto->guardar();
                //REdireccionar
                header('Location: /proyecto?url='.$proyecto->url);
                
            }
        }
        $router->render('dashboard/crear-proyecto',[
            'titulo' => 'Crear Proyecto',
            'alertas'=>$alertas
        ]);
    }
    public static function perfil(Router $router){
        session_start();

        isAuth();

        $router->render('dashboard/perfil',[
            'titulo' => 'Perfil'
        ]);
    }
}