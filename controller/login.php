<?php 
session_start();

include_once "../model/mainModel.php"; // se incluye el model principal

include_once "../model/userModel.php";  // se incluye el model de usuario
// include_once "../../model/roleModel.php"; // se incluye el model rol

// include_once "../../model/bitacoraModel.php"; // se incluye el model de bitacora
include_once "../model/alertModel.php"; // se incluye el model de alertas

// se incluyen los modelos necesarios para la vista

$intentos_inicio_sesion = 3;

// Se limpian y validan los datos recibidos a través de POST (usuario y contraseña). 
$usuario = modeloPrincipal::limpiar_cadena($_POST['user']);
$contraseña = modeloPrincipal::limpiar_cadena($_POST['pass']);

// Se verifica que no se hayan recibido campos vacíos.
modeloPrincipal::validar_campos_vacios([$usuario, $contraseña]);
// Se realiza una consulta a la base de datos para verificar si el usuario existe y si las credenciales son correctas.
$selectUser = model_user::consulta_usuario_condicion("*", "correo = '$usuario'");

// obtenemos el resultado de la consulta y la guardamos en un array
$datos_usuario = mysqli_fetch_array($selectUser);

$id_usuario = $datos_usuario["id"];

// Si el usuario y contraseña no están registrados, se muestra un mensaje de error.
if(mysqli_num_rows($selectUser) < 1){
    $_SESSION['logged_in'] = false;
    $_SESSION["intentos_sesion"]++; // se incrementa el contador de intentos de inicio de sesión
    alert_model::alerta_simple(
        '¡Ocurrió un error inesperado!',
        'El usuario es incorrecto, por favor verifica e intenta nuevamente',
        'error'
    );
    exit();
}

// se verifica si el numero de intentos de inicio de sesión es igual, a 3
if ($_SESSION["intentos_sesion"] == $intentos_inicio_sesion) {
    // se bloquea el usuario para iniciar sesion en caso de alcanzar el limite de intentos
    modeloPrincipal::UpdateSQL(
        "users",
        "state = 0",
        "id = $id_usuario"
    );

    $_SESSION["intentos_sesion"] = 0;

    alert_model::alerta_simple(
        '¡Cuenta bloqueada!',
        'Su cuenta ha sido bloqueada por razones de seguridad. Para activar nuevamente, por favor contacte al administrador del sistema.',
        'warning'
    );
    exit();
}


$hash = $datos_usuario["password"];

// $password = modeloPrincipal::hashear_contrasena($contraseña);
// se verifica si la contraseña es correcta

if (!password_verify($contraseña, $hash)) {
    $_SESSION["intentos_sesion"]++; // se incrementa el contador de intentos de inicio de sesión
    alert_model::alerta_simple(
        '¡Ocurrió un error inesperado!',
        'La contraseña es incorrecta, por favor verifica e intenta nuevamente',
        'error'
    );
    exit();
}


/** se verifica si el usuario esta activo **/
if ($datos_usuario["state"] == 0 || $datos_usuario["role"] != 1) {
    alert_model::alerta_simple(
        '¡Cuenta inactiva!',
        'Su cuenta se encuentra inactiva, por favor contacte al administrador del sistema.',
        'warning'
    );
    exit();
}


$_SESSION['logged_in'] = true; // variable de inicio de sesion

$_SESSION['dataUser'] = [
    "nombre" => $datos_usuario["full_name"],
    "correo" => $datos_usuario["correo"],
    "id" => $datos_usuario["id"],
    "rol" => $datos_usuario["role"],
    "estado" => $datos_usuario["state"]
];


echo '<script type="text/javascript">

        window.location = "./view/index.php";
    </script>';


mysqli_free_result($selectUser);
exit();