<?php 
session_start();

require_once "../model/mainModel.php"; // se incluye el model principal
require_once "../model/alertModel.php"; // se incluye el model de alertas
require_once "../model/categoryModel.php"; // se incluye el model de categorias

// modulo a trabajar

$modulo = modeloprincipal::limpiar_cadena($_POST["modulo"]);


if (!isset($_POST["modulo"])) {
    alert_model::alerta_simple("Ocurrio un error!","Ha ocurrido un error al procesar tu solicitud","error");
    exit();
}

if($modulo === "Guardar"){
    /* 
        Se recibe el nombre del categoría.
        se limpia la cadena con la función limpiar_cadena().
        se convierte a minúsculas con la función strtolower().
        luego se pone la primera letra de cada palabra en mayúscula con la función ucwords().
    */
    $nombre = modeloPrincipal::primeraLetraMayus(modeloPrincipal::limpiar_cadena($_POST['nombre_categoria']));
    $descripcion = modeloPrincipal::limpiar_cadena($_POST['descripcion']);
    
    modeloPrincipal::validar_campos_vacios([$nombre, $descripcion]); // Se verifica que no se hayan recibido campos vacíos.
    
    // se comprueba que no exista un registro con los mismos datos
    if(mysqli_num_rows(modeloPrincipal::consultar("SELECT nombre, descripcion FROM categorias WHERE nombre = '$nombre' OR descripcion = '$descripcion'")) > 0){
        /********** No se puede registrar un usuario si ya existe **********/
        alert_model::alerta_simple("¡Ocurrio un error!","El nombre que ingresaste ya se encuentra en uso.","error");
        exit(); 
    }

    if (modeloPrincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ., ]{3,40}",$nombre)) {
        alert_model::alert_of_format_wrong("nombre");
        exit();
    }

    if (modeloPrincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ,. ]{3,200}",$descripcion)) {
        alert_model::alert_of_format_wrong("Descripción");
        exit();
    }
    
    
    // se registran los datos del categoría
    try {
        $registrar = category_model::registrar($nombre, $descripcion);
        
        if (!$registrar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al registrar una categoría.","error");
        }
        
        alert_model::alert_reg_success();
        
        exit();
    } catch (Exception $e) {
        alert_model::alert_reg_error();
        exit();
    }
}

$id_categoria = modeloPrincipal::decryptionId($_POST["UID"]);
$id_categoria = modeloPrincipal::limpiar_cadena($id_categoria);

if ($modulo === "activo") {
    
    $datos_originales = category_model::consultar_categoria_por_id("*", $id_categoria);
    $datos_originales = mysqli_fetch_array($datos_originales);
    $datos_originales['estado'] = $datos_originales['estado'] == 1 ? 'Activo' : 'Inactivo';

    try {
        $actualizar = category_model::actualizar_state("0", $id_categoria);
        
        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al modificar el estado una categoría.","error");
        }

        alert_model::alert_mod_success();

        exit();
    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }
}

if ($modulo === "inactivo") {

    $datos_originales = category_model::consultar_categoria_por_id("*", $id_categoria);
    $datos_originales = mysqli_fetch_array($datos_originales);
    $datos_originales['estado'] = $datos_originales['estado'] == 1 ? 'Activo' : 'Inactivo';

    try {
        $actualizar = category_model::actualizar_state("1", $id_categoria);
        
        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al modificar el estado una categoría.","error");
        }
        
        alert_model::alert_mod_success();
        exit();

    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }
}