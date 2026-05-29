<?php

require_once "../config/SERVER.php";
require_once "../model/mainModel.php"; 
require_once "../model/productModel.php"; 

try {

    $method = $_SERVER['REQUEST_METHOD'];

    // --- OBTENER PRODUCTOS PARA EDITAR (GET) ---
    if ($method === 'GET') {
        $multiMoneda = $_GET['multiMoneda'] ?? false;

        $catalogo = mysqli_fetch_all(modeloPrincipal::consultar("SELECT id, nombre, precio, images FROM productos WHERE state = 1 ORDER BY nombre ASC")); 
        $stmt_categorias = modeloPrincipal::consultar("SELECT nombre FROM categorias WHERE state = 1 ORDER BY nombre ASC");
        $categorias_lista = array_column(mysqli_fetch_all($stmt_categorias, MYSQLI_ASSOC), 'nombre');
        
        $productosCategorias = []; 

        $productos = [];
        foreach ($catalogo as $producto) {
            $id = $producto[0];
            $nombre = $producto[1];
            $precio = $producto[2];
            $images = explode(',', $producto[3]);
            $images = $images[0];

            $productos[] = [
                "id" => $id,
                "nombre" => $nombre,
                "precio" => $precio,
                "images" => $images
            ];

            $categriasProductos = mysqli_fetch_all(modeloPrincipal::consultar("SELECT C.nombre FROM categorias_productos AS CP
                INNER JOIN categorias AS C ON C.id = CP.categoria_id
                INNER JOIN productos AS P ON P.id = CP.producto_id
                WHERE P.id = $id ORDER BY C.nombre")); 

            $productosCategorias[] = [
                "$id" => $categriasProductos
            ];

        }

        $data = [
            "status" => "success",
            "productos" => json_encode($productos),
            "multiMoneda" => $multiMoneda,
            "productosCategorias" => json_encode($productosCategorias),
            "categorias" => $categorias_lista
        ];

        echo json_encode($data);

    }
    
} catch(PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}