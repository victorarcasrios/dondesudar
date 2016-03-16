<?php

// La lógica de las urls es 
// index.php?r=controlador/accion&parametros

require "db.php";
require "config.php";
require "activeUser.php";

session_start();
if(!isset($_SESSION["user"]))
    $_SESSION["user"] = new activeUser(0);

// Si venimos de la misma website
if (isset($_GET['r']))
    $r = $_GET['r'];
else {// Si por el contrario, venimos de fuera de la website
    $r = 'site/index';
}

// Gestion y ejecución de controller/action
list($controllername, $action) = explode('/', $r);

// Si existe la clase controlador, la instancia
$controllerclass = $controllername . 'Controller';
if (file_exists("controllers/$controllerclass.php")) {
    require "controllers/$controllerclass.php";
    $controller = new $controllerclass;
} else
    die("Error al crear controlador " . $controllerclass);

// Si existe la acción en el controlador, la ejecuta
$metodo = 'action' . $action;
if (!method_exists($controller, $metodo))
    die("No existe esa acción: $metodo");
else
    $controller->$metodo();
?>
