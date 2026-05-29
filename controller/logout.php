<?php
/*------- configuración y conexión a base de datos -------*/
//iniciamos la sesion 
session_start();

require_once "../config/SERVER.php";

include_once "../modelo/modeloPrincipal.php";

session_unset(); // remueve o elimina las variables de sesion
session_destroy(); // Destruye la sesión actual

header("location: ../");
