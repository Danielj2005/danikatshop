<?php
// api.php

require_once "../model/mainModel.php"; // se incluye el model principal

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
        $id = modeloPrincipal::decryptionId($_GET['UID']);

        $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $products = $products[0];
        // Convertimos el string de imágenes de nuevo a Array para el JS
        $products['imgs'] = explode(",",$products['images']);  ?>

            <div class="col-12 col-md-6 mb-3">
                <label class="col-form-label">Nombre del producto <span style="color:#f00;">*</span> </label>
                <input name="producto" value="<?= $products['nombre'] ?>" placeholder="Nombre" required class="mb-3 w-full bg-slate-800 p-3 rounded-xl border-none text-white outline-none focus:ring-1 ring-purple-500">
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="col-form-label">Precio (opcional)</label>
                <input name="price" value="<?= $products['precio'] ?>" type="number" step="0.01" placeholder="Precio ($)" class="w-full mb-3 bg-slate-800 p-3 rounded-xl border-none text-white outline-none focus:ring-1 ring-purple-500">
            </div>
            
            <div class="rounded-3xl mb-4 bg-white col-12 table-responsive overflow-hidden overflow-x-auto">

                <table class="mb-3 no-footer table table-borderless table-group-divider table-hover table-striped">
                    <thead>
                        <tr class="text-black">
                            <th class="col text-center" scope="col">Imagen</th>
                            <th class="col text-center" scope="col">Editar</th>
                            <th class="col text-center" scope="col">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        foreach ($products['imgs'] as $img) { ?>
                            
                            <tr class="text-black">
                                <th class="col text-center" scope="col">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img src=".<?= $img ?>" style="width: 5rem; height:5rem; " class="d-block" alt="...">
                                    </div>
                                </th>
                                <th class="col text-center" scope="col">
                                    <input type="file" name="image[]" accept="image/*" class="rounded-3xl border p-2 my-3 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:bg-purple-600 hover:file:bg-purple-500 cursor-pointer text-black transition"/>
                                </th>
                                <th class="col text-center" scope="col">
                                    <button dataId="<?= $products['id'] ?>" class="btn_modal btn btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </th>
                            </tr>
                        
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="col-12 mb-3">
                <label class="col-form-label">Cargar más Imagenes del producto <span style="color:#f00;">*</span> </label>
                <input type="file" name="image[]" multiple accept="image/*" class="rounded-3xl border p-2 my-3 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:bg-purple-600 hover:file:bg-purple-500 cursor-pointer text-white transition"/>
            </div>

            <div class="col-12 mb-3">
                <label class="col-form-label">Descripción <span style="color:#f00;">*</span> </label>
                <textarea name="desc" value="<?= $products['description'] ?>" placeholder="Descripción del producto..." class="w-full bg-slate-800 p-3 rounded-xl border-none text-white h-24 text-sm outline-none focus:ring-1 ring-purple-500"><?= $products['description'] ?></textarea>
            </div>
            
        <?php
    }
    
} catch(PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>