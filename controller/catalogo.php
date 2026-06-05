<?php

require_once "../config/SERVER.php";
require_once "../model/mainModel.php"; 
require_once "../model/productModel.php"; 

try {

    $method = $_SERVER['REQUEST_METHOD'];

    // --- OBTENER PRODUCTOS PARA EDITAR (GET) ---
    if ($method === 'GET') {
        $multiMoneda = $_GET['multiMoneda'] ?? false;
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $per_page = isset($_GET['per_page']) ? max(1, intval($_GET['per_page'])) : 15;

        $offset = ($page - 1) * $per_page;

        // total de productos
        $total_stmt = modeloPrincipal::consultar("SELECT COUNT(*) AS total FROM productos WHERE state = 1");
        $total_row = mysqli_fetch_assoc($total_stmt);
        $total = intval($total_row['total']);

        $catalogo = mysqli_fetch_all(modeloPrincipal::consultar("SELECT id, nombre, precio, images FROM productos WHERE state = 1 ORDER BY nombre ASC LIMIT $per_page OFFSET $offset")); 
        
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
                "nombre" => ucwords(strtolower($nombre)),
                "precio" => $precio,
                "images" => $images
            ];

        }

        $data = [
            "status" => "success",
            "productos" => json_encode($productos),
            "multiMoneda" => $multiMoneda,
            "categorias" => $categorias_lista,
            "total" => $total,
            "per_page" => $per_page,
            "page" => $page,
            "total_pages" => ceil($total / $per_page)
        ];
    
        echo json_encode($data);
    }
    
    // --- OBTENER PRODUCTOS PARA EDITAR (POST) ---
    if ($method === 'POST') {

        $data = json_decode(file_get_contents("php://input"), true);

        $filters = $data['filter'] ?? null;
        $page = isset($data['page']) ? max(1, intval($data['page'])) : 1;
        $per_page = isset($data['per_page']) ? max(1, intval($data['per_page'])) : 15;
        $offset = ($page - 1) * $per_page;

        $multiMoneda = $data['multiMoneda'] ?? false;

        if ($filters !== null) {
            $addQuery = "WHERE C.nombre IN ('" . implode("','", $filters) . "') GROUP BY P.id";
            // obtener total filtrado
            $countQuery = "SELECT COUNT(DISTINCT P.id) AS total FROM productos AS P INNER JOIN categorias_productos AS CP ON P.id = CP.producto_id INNER JOIN categorias AS C ON C.id = CP.categoria_id WHERE C.nombre IN ('" . implode("','", $filters) . "')";
            $total_stmt = modeloPrincipal::consultar($countQuery);
            $total_row = mysqli_fetch_assoc($total_stmt);
            $total = intval($total_row['total']);
            $query = "SELECT P.id, P.nombre, P.precio, P.images FROM productos AS P INNER JOIN categorias_productos AS CP ON P.id = CP.producto_id INNER JOIN categorias AS C ON C.id = CP.categoria_id $addQuery ORDER BY P.nombre ASC LIMIT $per_page OFFSET $offset";
        } else {
            $total_stmt = modeloPrincipal::consultar("SELECT COUNT(*) AS total FROM productos WHERE state = 1");
            $total_row = mysqli_fetch_assoc($total_stmt);
            $total = intval($total_row['total']);
            $query = "SELECT P.id, P.nombre, P.precio, P.images FROM productos AS P WHERE P.state = 1 ORDER BY P.nombre ASC LIMIT $per_page OFFSET $offset";
        }

        $catalogo = mysqli_fetch_all(modeloPrincipal::consultar($query)); 
        
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
                "nombre" => ucwords(strtolower($nombre)),
                "precio" => $precio,
                "images" => $images
            ];

        }

        $data = [
            "status" => "success",
            "productos" => json_encode($productos),
            "multiMoneda" => $multiMoneda,
            "categorias" => $categorias_lista,
            "total" => $total,
            "per_page" => $per_page,
            "page" => $page,
            "total_pages" => ceil($total / $per_page)
        ];


        echo json_encode($data);

    }


} catch(PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}