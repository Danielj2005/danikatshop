<?php
// api.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Datos de conexión (Cámbialos por los que te da InfinityFree)
/*
$host = 'sql204.infinityfree.com'; 
$db_name = 'if0_41737603_danikat_bd';
$username = 'if0_41737603';
$password = '0iAMk3Kc0lb'; 
*/

$host = 'localhost'; 
$db_name = 'danikat_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $method = $_SERVER['REQUEST_METHOD'];

    // --- OBTENER PRODUCTOS (GET) ---
    if ($method === 'GET') {
        $stmt = $conn->prepare("SELECT * FROM productos ORDER BY id DESC");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Convertimos el string de imágenes de nuevo a Array para el JS
        foreach ($products as &$p) {
            $p['imgs'] = explode(',', $p['images']);
        }
        
        echo json_encode($products);
    }

    // --- GUARDAR O ACTUALIZAR PRODUCTO (POST) ---
    if ($method === 'POST') {
        

        // Dentro de api.php, en la sección del POST
        $data = json_decode(file_get_contents("php://input"), true);
        $action = $data['action'];

        switch ($action) {
            case 'login':
                $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
                $stmt->execute([$data['user'], $data['pass']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user) {
                    echo json_encode(["status" => "success", "user" => ["name" => $user['full_name'], "role" => $user['role']]]);
                } else {
                    echo json_encode(["status" => "error"]);
                }
                break;

            case 'add_product':
                $c = $data['content'];
                $sql = "INSERT INTO products (name, price, category, description, images) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$c['name'], $c['price'], $c['category'], $c['description'], $c['images']]);
                echo json_encode(["status" => "success"]);
                break;

            case 'update_product':
                $c = $data['content'];
                $sql = "UPDATE products SET name=?, price=?, category=?, description=?, images=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$c['name'], $c['price'], $c['category'], $c['description'], $c['images'], $c['id']]);
                echo json_encode(["status" => "success"]);
                break;

            case 'delete_product':
                $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
                $stmt->execute([$data['id']]);
                echo json_encode(["status" => "success"]);
                break;
        }
    }
} catch(PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>