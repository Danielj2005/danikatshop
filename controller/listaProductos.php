<?php

require_once "../model/mainModel.php"; 
require_once "../model/productModel.php"; 
require_once "../config/SERVER.php";

try {

    $method = $_SERVER['REQUEST_METHOD'];

    // --- OBTENER PRODUCTOS PARA EDITAR (GET) ---
    if ($method === 'GET') {
        $id = $_GET['UID'];

        producto_model::lista($id);
    }
    
} catch(PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>