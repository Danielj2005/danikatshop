<?php 
session_start();

require_once "../model/mainModel.php"; // se incluye el model principal
require_once "../model/alertModel.php"; // se incluye el model de alertas
require_once "../model/productModel.php"; // se incluye el model de categorias

// modulo a trabajar
modeloPrincipal::verificarModuloATrabajar("modulo");

$modulo = modeloprincipal::limpiar_cadena($_POST["modulo"]);

// verificar si el modulo es guardar
if($modulo === 'Guardar'){
    
    $producto = $_POST['producto'];
    $price = $_POST['price'] ?? 0.00; // si no se envía un precio, se asigna un valor por defecto de 1.00
    $price = number_format($price, 2, '.', ',');
    $category = $_POST['category'];
    $image = $_POST['image'];
    $desc = $_POST['desc'];

    $uploaded_paths = [];
    $UID = modeloPrincipal::generar_uuid();

    // 1. Procesar los archivos si existen
    if (isset($_FILES['image'])) {
        $files = $_FILES['image'];
        $i = 1;
        foreach ($files['tmp_name'] as $key => $tmp_name) {
            if ($files['error'][$key] === 0) {
                // Obtenemos la extensión del nombre original (ej: "foto.JPG" -> "jpg")
                $extension = strtolower(pathinfo($files['name'][$key], PATHINFO_EXTENSION));

                $name = $UID . '_' . $i++ . "." . $extension;

                $target = "../storage/$name";
                
                if (move_uploaded_file($tmp_name, $target)) {
                    $target = "./storage/$name";

                    $uploaded_paths[] = $target;
                }
            }
        }
    }

    // 2. Convertir el array de rutas a un solo string para la BD
    $images_string = implode(',', $uploaded_paths);
    
    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$producto, $category, $desc]);
    
    // se valida el campo nombre del producto
    if (modeloPrincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{3,200}", $producto)) {
        alert_model::alerta_simple("¡Ocurrió un error!","El nombre del producto $producto no cumple con el formato establecido","error");
        exit();
    }

    // se registran los datos del producto
    try {

        $registrar = modeloPrincipal::InsertSQL("productos", "nombre, precio, description, images, state, created_at" ,"'$producto', $price, '$desc', '$images_string', 1, NOW()");

        if (!$registrar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al registrar un producto.","error");
            exit();
        }

        $id_producto = producto_model::obtener_id_recien_registrada();

        foreach ($category as $key) {
            $categoria_id = modeloPrincipal::decryptionId($key);
            $registrar = modeloPrincipal::InsertSQL("categorias_productos", "categoria_id, producto_id" ,"$categoria_id, $id_producto");
        
            if (!$registrar) {
                alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al registrar las categorías de un producto.","error");
                exit();
            }
        }

        alert_model::alert_reg_success();
        exit();
    } catch (Exception $e) {
        echo $price;
        echo $e;
        alert_model::alert_reg_error();
        exit();
    }
    
}


if($modulo === 'Modificar'){
        
    $id_producto = modeloPrincipal::decryptionId($_POST["id"]);
    $id_producto = modeloPrincipal::limpiar_cadena($id_producto);

    $price = modeloPrincipal::limpiar_cadena($_POST['precio']);

    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$price, $id_producto]);

    // se modifican los datos del producto
    try {
        $actualizar = producto_model::actualizar_producto($price, $id_producto);

        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al actualizar el precio de un producto.","error");
        }

        alert_model::alert_mod_success();
        exit();
    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }
    
}

if($modulo === 'Eliminar'){
        
    $id_producto = modeloPrincipal::decryptionId($_POST["id"]);
    $id_producto = modeloPrincipal::limpiar_cadena($id_producto);

    $price = modeloPrincipal::limpiar_cadena($_POST['precio']);

    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$price, $id_producto]);

    // se modifican los datos del producto
    try {
        $actualizar = producto_model::actualizar_producto($price, $id_producto);

        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al actualizar el precio de un producto.","error");
        }

        alert_model::alert_mod_success();
        exit();
    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }
    
}
