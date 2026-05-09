<?php

require_once "../model/mainModel.php"; 
require_once "../model/productModel.php"; 
require_once "../config/SERVER.php";

try {

    $method = $_SERVER['REQUEST_METHOD'];

    // --- OBTENER PRODUCTOS PARA EDITAR (GET) ---
    if ($method === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['UID'];
        // $prices = ["status" => 'success', "USD" => 500.46, "EURO" => 589.27];
        $prices = $data['prices'];
        producto_model::lista($id, $prices);
    }
    
} catch(PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>