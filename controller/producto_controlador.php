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
    $uploaded_hashes = [];
    $UID = modeloPrincipal::generar_uuid();

    // 1. Procesar los archivos si existen
    if (isset($_FILES['image'])) {
        $files = $_FILES['image'];
        $i = 1;
        foreach ($files['tmp_name'] as $key => $tmp_name) {
            if ($files['error'][$key] === 0 && is_uploaded_file($tmp_name)) {
                $file_hash = md5_file($tmp_name);

                if ($file_hash === false || in_array($file_hash, $uploaded_hashes, true)) {
                    continue;
                }

                // Obtenemos la extensión del nombre original (ej: "foto.JPG" -> "jpg")
                $extension = strtolower(pathinfo($files['name'][$key], PATHINFO_EXTENSION));

                $name = $UID . '_' . $i++ . "." . $extension;

                $target = "../storage/$name";
                
                if (move_uploaded_file($tmp_name, $target)) {
                    $target = "./storage/$name";

                    $uploaded_paths[] = $target;
                    $uploaded_hashes[] = $file_hash;
                }
            }
        }
    }

    // 2. Convertir el array de rutas y hashes a un solo string para la BD
    $images_string = implode(',', $uploaded_paths);
    $image_hash_string = implode(',', $uploaded_hashes);
    
    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$producto, $category, $desc]);
    
    // se valida el campo nombre del producto
    if (modeloPrincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{3,200}", $producto)) {
        alert_model::alerta_simple("¡Ocurrió un error!","El nombre del producto $producto no cumple con el formato establecido","error");
        exit();
    }

    // se registran los datos del producto
    try {

        $registrar = modeloPrincipal::InsertSQL("productos", "nombre, precio, description, images, image_hash, state, created_at" ,"'$producto', $price, '$desc', '$images_string', '$image_hash_string', 1, NOW()");

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
        alert_model::alert_reg_error();
        exit();
    }
    
}


if($modulo == 'Modificar'){
    
    $id_producto = modeloPrincipal::decryptionId($_POST["id"]);
    $id_producto = modeloPrincipal::limpiar_cadena($id_producto);

    $producto = $_POST['producto'];
    $price = $_POST['price'] ?? 0.00; // si no se envía un precio, se asigna un valor por defecto de 1.00
    $price = number_format($price, 2, '.', ',');
    $category = $_POST['category'];
    $image = $_POST['image'];
    $desc = $_POST['desc'];

    // Obtener imágenes y hashes actuales del producto 
    $producto_actual = modeloPrincipal::consultar("SELECT images, image_hash FROM productos WHERE id = $id_producto");
    if (mysqli_num_rows($producto_actual) === 0) {
        alert_model::alerta_simple("¡Ocurrió un error!","No se encontró el producto a modificar.","error");
        exit();
    }

    $producto_actual = mysqli_fetch_assoc($producto_actual);
    $existing_images = [];
    $existing_hashes = [];

    if (!empty($producto_actual['images'])) {
        $existing_images = array_filter(array_map('trim', explode(',', $producto_actual['images'])));
    }

    if (!empty($producto_actual['image_hash'])) {
        $existing_hashes = array_filter(array_map('trim', explode(',', $producto_actual['image_hash'])));
    }

    $uploaded_paths = [];
    $uploaded_hashes = [];
    $UID = modeloPrincipal::generar_uuid();

    // 1. Procesar los archivos si existen
    if (isset($_FILES['image'])) {
        $files = $_FILES['image'];
        $i = 1;
        foreach ($files['tmp_name'] as $key => $tmp_name) {
            if ($files['error'][$key] === 0 && is_uploaded_file($tmp_name)) {
                $file_hash = md5_file($tmp_name);

                if ($file_hash === false) {
                    continue;
                }

                if (in_array($file_hash, $existing_hashes, true) || in_array($file_hash, $uploaded_hashes, true)) {
                    continue;
                }

                // Obtenemos la extensión del nombre original (ej: "foto.JPG" -> "jpg")
                $extension = strtolower(pathinfo($files['name'][$key], PATHINFO_EXTENSION));

                $name = $UID . '_' . $i++ . "." . $extension;

                $target = "../storage/$name";
                
                if (move_uploaded_file($tmp_name, $target)) {
                    $target = "./storage/$name";

                    $uploaded_paths[] = $target;
                    $uploaded_hashes[] = $file_hash;
                }
            }
        }
    }

    $final_images = $existing_images;
    $final_hashes = $existing_hashes;

    if (!empty($uploaded_paths)) {
        $final_images = array_merge($existing_images, $uploaded_paths);
        $final_hashes = array_merge($existing_hashes, $uploaded_hashes);
    }

    $images_string = implode(',', $final_images);
    $image_hash_string = implode(',', $final_hashes);

    // Se verifica que no se hayan recibido campos vacíos.
    // modeloPrincipal::validar_campos_vacios([$producto, $category, $desc]);
    if ($id_producto === "" || $producto === "" || $desc === "") {
        alert_model::alert_fields_empty();
        exit();
    }
    // se valida el campo nombre del producto
    if (modeloPrincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{3,200}", $producto)) {
        alert_model::alerta_simple("¡Ocurrió un error!","El nombre del producto $producto no cumple con el formato establecido","error");
        exit();
    }

    try {
        $actualizar = modeloPrincipal::UpdateSQL("productos", "nombre = '$producto', precio = $price, description = '$desc', images = '$images_string', image_hash = '$image_hash_string'", "id = $id_producto");

        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al actualizar el producto.","error");
            exit();
        }

        // if (is_array($category) && count($category) > 0) {
        //     modeloPrincipal::DeleteSQL("categorias_productos", "producto_id = $id_producto");
        //     foreach ($category as $key) {
        //         $categoria_id = modeloPrincipal::decryptionId($key);
        //         $registrar_categoria = modeloPrincipal::InsertSQL("categorias_productos", "categoria_id, producto_id", "$categoria_id, $id_producto");
        //         if (!$registrar_categoria) {
        //             alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al registrar las categorías de un producto.","error");
        //             exit();
        //         }
        //     }
        // }

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

    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$id_producto]);

    // se modifican los datos del producto
    try {
        $actualizar = producto_model::actualizar_estado(0,$id_producto);

        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al eliminar un producto.","error");
        }
        
        alert_model::alert_mod_success();
        exit();
    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }
    
}


$id_producto = modeloPrincipal::decryptionId($_POST["id"]);
$id_producto = modeloPrincipal::limpiar_cadena($id_producto);

if ($modulo === "activo") {
    
    try {
        $actualizar = producto_model::actualizar_estado( 0, $id_producto);
        
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

    try {
        $actualizar = producto_model::actualizar_estado( 1, $id_producto);
        
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