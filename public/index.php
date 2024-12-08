<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\LoginController;
use Controllers\DashboardController;
$router = new Router();

//RUTas login
$router->get('/',[LoginController::class,'login']);
$router->post('/',[LoginController::class,'login']);
$router->get('/logout',[LoginController::class,'logout']);

//Rutas Creacion de cuentas 
$router->get('/crear',[LoginController::class,'crear']);
$router->post('/crear',[LoginController::class,'crear']);
//Rutas REcuperar de cuentas 
$router->get('/recuperar',[LoginController::class,'recuperar']);
$router->post('/recuperar',[LoginController::class,'recuperar']);
//Rutas reestablecer de cuentas 
$router->get('/reestablecer',[LoginController::class,'reestablecer']);
$router->post('/reestablecer',[LoginController::class,'reestablecer']);
//Rutas Confirmacion de cuentas 
$router->get('/mensaje',[LoginController::class,'mensaje']);
$router->get('/confirmar',[LoginController::class,'confirmar']);

//Rutas ZONA DE PROYECTOS
$router->get('/dashboard',[DashboardController::class,'index']);
$router->get('/crear-proyecto',[DashboardController::class,'crear_proyecto']);
$router->get('/perfil',[DashboardController::class,'perfil']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();