<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController{
    public static function index(Router $router){


        session_start();

        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioId',$id);

        
        $router->render('dashboard/index',[
            'titulo' => 'Proyectos',
            'proyectos'=>$proyectos
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
    public static function proyecto(Router $router){
        $alertas=[];
        session_start();

        isAuth();

        $token = $_GET['url'];
        

        if(!$token) header('Location: /dashboard');
        //Revisar que la persona es quien lo creo
        $proyecto = Proyecto::where('url',$token);
        if($proyecto->propietarioId !== $_SESSION['id']){
            header('Location: /dashboard');
        }

        $router->render('dashboard/proyecto',[
            'titulo'=>$proyecto->proyecto,
            'alertas'=>$alertas
        ]);
    }
    public static function perfil(Router $router){
        session_start();

        $usuario = Usuario::find($_SESSION['id']);

        isAuth();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarPerfil();

            if(empty($alertas)){

                $existeUsuario = Usuario::where('email',$usuario->email);

                if($existeUsuario && $existeUsuario->id!== $usuario->id){
                    //Mostrar mensaje de error
                    Usuario::setAlerta('error','Este Email ya pertenece a otra cuenta');
                    $alertas = $usuario->getAlertas();


                }else{
                    
                    //guardar el usuario
                    $usuario->guardar();
    
                    //Alerta de guardado
                    Usuario::setAlerta('exito','Actualizado Exitosamente');
                    $alertas = $usuario->getAlertas();
                    //Asignar el nombre nuevo a la barra
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }

            
        }

        $router->render('dashboard/perfil',[
            'titulo' => 'Perfil',
            'usuario'=>$usuario,
            'alertas'=>$alertas
        ]);
    }

    public static function cambiar_password(Router $router){
        session_start();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = Usuario::find($_SESSION['id']);

            //Sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevo_password();

            if(empty($alertas)){
                $resultado = $usuario->comprobar_password();

                if($resultado){
                    $usuario->password = $usuario->password_nuevo;
                    //Eliminar propiedades que no son necesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    //Hashear el nuevo password
                    $usuario->hashPassword();
                    //Asignar el nuevo password
                    $resultado = $usuario->guardar();
                    if($resultado){
                        Usuario::setAlerta('exito', 'Password Actualizado Correctamente');
                        $alertas = $usuario->getAlertas();
                    }
                }else{
                    Usuario::setAlerta('error', 'Password Incorrecto');
                    $alertas = $usuario->getAlertas();
                }
            }
        }

        $router->render('dashboard/cambiar-password',[
            'titulo' => 'Cambiar Password',
            'usuario'=>$usuario,
            'alertas'=>$alertas
        ]);
    }
}