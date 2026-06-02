<?php

require_once "../config/SERVER.php";
require_once "../model/mainModel.php"; 
require_once "../model/productModel.php"; 

try {

    $method = $_SERVER['REQUEST_METHOD'];

    // --- OBTENER PRODUCTOS PARA EDITAR (GET) ---
    if ($method === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $state = $data['state'];
        $UID = $data['UID'];
        
        
        $prices = $data['prices'];

        if ($UID >= 0 && $UID <= 1) {
            // Valid state, proceed with fetching products
            producto_model::lista($state, $prices);

        }else if ($UID == 2) {
            
            $catalogo = modeloPrincipal::consultar("SELECT id, nombre, precio, images, state FROM productos WHERE state = $state ORDER BY nombre ASC"); 
            
            while ($mostrar = mysqli_fetch_assoc($catalogo)) {
        
                $imgSrc = explode(',', $mostrar['images']);

                $images = $imgSrc[0];

                $id_producto = $mostrar["id"];
                $categorias = modeloPrincipal::consultar("SELECT C.nombre AS categorias FROM `categorias_productos` AS CP 
                    INNER JOIN categorias AS C ON C.id = CP.categoria_id
                    WHERE CP.producto_id = $id_producto"); 

                ?>


                <div data-categories="" class="product-card product_${id} group bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden hover:border-purple-500/50 transition-all duration-500 animate-slide-up">
                    
                    <div class="overflow-hidden cursor-pointer" style="height: 15rem;">
                        <img src=".<?= $images ?>" onerror="this.onerror=null; this.src='./img/404.png';" onerror="this.src='./img/404.png'">
                    </div>


                    <div class="p-3">
                        <div class="">
                            <button onclick="detallesProductoById()" class="text-sm mb-3 text-white font-semibold" data-bs-toggle="modal" data-bs-target="#exampleModal"> <?= ucwords(strtolower($mostrar["nombre"])) ?> </button>
                        </div>
                        
                        <div class="">
                            <?php if ($mostrar["precio"] <= 1.00 ): ?>

                                
                                <div class="mb-3 flex gap-3 items-center justify-around"> 
                                    <span class="badge text-bg-danger text-sm font-bold text-white">Bajo pedido</span>
                                </div>
                            <?php else: ?>
                                <div class="align-items-center gap-2 justify-content-start mb-3 row">

                                    <div class="mb-2"> 
                                        <button class="btn btn-success px-1 py-0" id="basic-addon2" onclick="copyToClipboard('<?= producto_model::formatnumber('USD',$mostrar['precio']); ?>')">
                                            <spna><?= "$ ".producto_model::formatnumber("USD",$mostrar["precio"]); ?></span>
                                            <i class="text-white btn bi bi-copy"></i>
                                        </button>
                                    </div>

                                    <div class="mb-2">
                                        
                                        <button class="btn px-1 py-0 btn-primary" id="basic-addon2" onclick="copyToClipboard('<?= producto_model::formatnumber('VES',$mostrar['precio'] * $prices['USD']); ?>')">
                                            <span><?= "Bs ".producto_model::formatnumber("VES",$mostrar["precio"] * $prices['USD']); ?></span>
                                            <i class="text-white btn bi bi-copy"></i>
                                        </button>
                                    </div>

                                    <div class="mb-2"> 
                                        
                                        <button class="btn px-1 py-0 btn-secondary" id="basic-addon2" onclick="copyToClipboard('<?= producto_model::formatnumber('VES',$mostrar['precio'] * $prices['EURO']); ?>')">
                                            <span><?= "€ ".producto_model::formatnumber("VES",$mostrar["precio"] * $prices['EURO']); ?></span>
                                            <i class="text-white btn bi bi-copy"></i>
                                        </button>
                                    </div>

                                    <div class="d-none"> 
                                        <label class="text-secondary"><?= "USDT ".producto_model::formatnumber("VES",$mostrar["precio"] * ($prices['USD'] * 1.3 )); ?></label>
                                        
                                        <button class="btn btn-outline-secondary p-0" id="basic-addon2" onclick="copyToClipboard('<?= producto_model::formatnumber('VES',$mostrar['precio'] * ($prices['USD'] * 1.3 )); ?>')">
                                            <i class="text-white btn bi bi-copy"></i>
                                        </button>
                                    </div>

                                </div>
                            <?php endif; ?>
                        </div>


                        <div class="flex flex-wrap justify-between">
                            <div class="mb-3">
                                <button onclick="editingProduct('<?= modeloPrincipal::encryptionId($mostrar['id']) ?>')" type="button" class="text-sm btn_details btn btn-outline-warning transition-all gap-2 flex items-center justify-center " data-bs-toggle="modal" data-bs-target="#editar_producto">
                                    <i class="bi bi-pencil-square"></i>
                                    <span class="d-none d-md-block font-bold"> Editar</span>
                                </button> 
                            </div>

                            <div class="mb-3">
                                <button onclick="detallesProductoById(<?= $mostrar['id'] ?>)" type="button" class="text-sm btn_details btn btn-outline-secondary transition-all gap-2 flex items-center justify-center " data-bs-toggle="modal" data-bs-target="#detallesModal">
                                    <i class="bi bi-eye"></i> 
                                    <span class="d-none d-md-block font-bold"> Ver Detalles</span>
                                </button> 
                            </div>

                            <div class="mb-2">
                                <?php if ($mostrar["state"] == 1) { ?>

                                    <form action="../controller/producto_controlador.php" method="post" class="SendFormAjax" data-type-form="update_estate" >
                                        <input type="hidden" name="modulo" value="activo">          
                                        <input type="hidden" name="id" value="<?= modeloPrincipal::encryptionId($mostrar['id']) ?>">
                                        <button class="btn btn-outline-danger bi bi-x-circle text-sm" title="estado del producto" type="submit"> </button>
                                    </form>

                                <?php } else { ?>

                                    <form action="../controller/producto_controlador.php" method="post" class="SendFormAjax" data-type-form="update_estate" >
                                        <input type="hidden" name="modulo" value="inactivo">          
                                        <input type="hidden" name="id" value="<?= modeloPrincipal::encryptionId($mostrar['id']) ?>">
                                        <button class="btn btn-success bi bi-check-circle text-sm" title="state de la categoría"> </button>
                                    </form>

                                <?php }  ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php } 
        }else {
            echo json_encode(["status" => "error", "message" => "Invalid state value. Must be 0 or 1."]);
            exit;

        }
    }
    
} catch(PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>