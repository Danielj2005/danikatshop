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
        // $prices = ["status" => 'success', "USD" => 500.46, "EURO" => 589.27];
        $prices = $data['prices'];

        if ($UID >= 0 && $UID <= 1) {
            // Valid state, proceed with fetching products
            producto_model::lista($state, $prices);
        }else if ($UID == 2) {
            
            $catalogo = modeloPrincipal::consultar("SELECT id, nombre, precio, images, state FROM productos WHERE state = $state ORDER BY nombre ASC"); 
            
            while ($mostrar = mysqli_fetch_assoc($catalogo)) {

                $imgSrc = $mostrar['images'];

                $id_producto = $mostrar["id"];
                $categorias = modeloPrincipal::consultar("SELECT C.nombre AS categorias FROM `categorias_productos` AS CP 
                    INNER JOIN categorias AS C ON C.id = CP.categoria_id
                    WHERE CP.producto_id = $id_producto"); 

                ?>


                <tr class="text-center">
                    <td class="text-center"></td>
                    <td class="text-start">
                        <p class=" fw-bold mb-1"><?= ucwords(strtolower($mostrar["nombre"])) ?> </p>
                        <small class="d-flex gap-1 text-muted align-items-center"> 
                            <?php while ($cat = mysqli_fetch_assoc($categorias)) { ?> 
                                <span class="bg-indigo-600 badge text-white rounded-3xl">
                                    <?= $cat['categorias'] ?>
                                </span>
                            <?php } ?> 
                        </small>
                    </td>
                    <td class="text-center">
                        <?php if ($mostrar["precio"] < 1): ?>
                            <div class="flex justify-center gap-2 flex-wrap items-center">
                                <span class="badge text-bg-danger text-sm">Bajo pedido</span>
                            </div>

                        <?php else: ?>
                            <div class="dropdown flex justify-center gap-2 flex-wrap items-center mb-2">

                                <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?= "$ ".self::formatnumber("USD",$mostrar["precio"]); ?>
                                </button>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-item"> 
                                        <span id="moneda_bs" class=" text-sm badge fw-bold text-bg-primary me-2"> <?= "Bs ".self::formatnumber("VES",$mostrar["precio"] * $prices['USD']); ?></span> 
                                        <i class="btn bi bi-copy" onclick="copyToClipboard('<?= self::formatnumber('VES',$mostrar['precio'] * $prices['USD']); ?>')"></i>
                                    </li>
                                    <li class="dropdown-item">
                                        <span id="moneda_euro" class=" text-sm badge fw-bold text-bg-secondary me-2"> <?= "€ ".self::formatnumber("VES",$mostrar["precio"] * $prices['EURO']); ?></span> 
                                        <i class="btn bi bi-copy" onclick="copyToClipboard('<?= self::formatnumber('VES',$mostrar['precio'] * $prices['EURO']); ?>')"></i>
                                    </li>
                                    <li class="d-none"> 
                                        <span id="moneda_usdt" class=" text-sm badge text-bg-info me-2"> <?= "USDT ".self::formatnumber("VES",$mostrar["precio"] * ($prices['USD'] * 1.3 )); ?></span> 
                                        <i class="btn bi bi-copy" onclick="copyToClipboard('<?= self::formatnumber('VES',$mostrar['precio'] * ($prices['USD'] * 1.3 )); ?>')"></i>
                                    </li>
                                        
                                </ul>
                            </div>

                        <?php endif; ?>
                    </td>
                    <td>
                        <button onclick="verImagen('<?= $imgSrc; ?>','<?= $mostrar['nombre'] ?>' )" class="btn btn-secondary text-xs">
                            <i class="bi bi-image mr-1"></i> 
                            <span class="d-none d-md-block font-bold">Ver Imagen</span>
                        </button>
                    </td>
                    <td class="col text-center">
                        <button data-bs-toggle="modal" data-bs-target="#editar_producto"
                            onclick="editingProduct('<?= modeloPrincipal::encryptionId($mostrar['id']) ?>')" class="btn_edit_produto btn btn-warning text-xs">
                                <i class="bi bi-pencil-square"></i>
                        </button>
                    </td>
                    <td class="col text-center">
                        <?php 
                            if ($mostrar["state"] == 1) { ?>
                            <form action="../controller/producto_controlador.php" method="post" class="SendFormAjax" data-type-form="update_estate" >
                                <input type="hidden" name="modulo" value="activo">          
                                <input type="hidden" name="id" value="<?= modeloPrincipal::encryptionId($mostrar['id']) ?>">
                                <button class="btn btn-danger bi bi-x-circle text-xs" title="estado del producto" type="submit"> </button>
                            </form>
                            <?php } else { ?>
                            <form action="../controller/producto_controlador.php" method="post" class="SendFormAjax" data-type-form="update_estate" >
                                <input type="hidden" name="modulo" value="inactivo">          
                                <input type="hidden" name="id" value="<?= modeloPrincipal::encryptionId($mostrar['id']) ?>">
                                <button class="btn btn-success bi bi-check-circle text-xs" title="state de la categoría"> </button>
                            </form>
                        <?php }  ?>
                    </td>
                </tr>
                
                <div data-categories="" class="product-card product_${id} group bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden hover:border-purple-500/50 transition-all duration-500 animate-slide-up">
                    
                    <div class="custom-carousel">
                        <div class="carousel-track-container">
                            <ul class="carousel-track" id="carouselTrack">
                                <li class="carousel-slide ${active}">
                                    <img src=".${url}" onerror="this.onerror=null; this.src='./img/404.png';" onerror="this.src='ruta/imagen-no-encontrada.jpg'">
                                </li>
                            </ul>
                        </div>

                        <?php items.length > 1 ?>
                            `<button class="carousel-button prev-btn" id="prevBtn">&#10094;</button>
                            <button class="carousel-button next-btn" id="nextBtn">&#10095;</button>` : ``
                        <?php } ?>
                    </div>

                    <div class="p-3">
                        <div class="">
                            <button onclick="detallesProductoById()" class="text-sm mb-3 text-white font-semibold" data-bs-toggle="modal" data-bs-target="#exampleModal"> producto </button>
                        </div>

                        <div class="flex flex-wrap justify-between">
                            <div class="mb-3">
                                <button onclick="editingProduct()" type="button" class="btn_details btn btn-outline-warning rounded-2xl transition-all gap-2 flex items-center justify-center " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="bi bi-pencil-square text-lg"></i>
                                    <span class="d-none d-md-block text-sm font-bold"> Editar</span>
                                </button> 
                            </div>

                            <div class="mb-3">
                                <button onclick="detallesProductoById()" type="button" class="btn_details btn btn-outline-secondary rounded-2xl transition-all gap-2 flex items-center justify-center " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="bi bi-eye text-lg"></i> 
                                    <span class="d-none d-md-block text-sm font-bold"> Ver Detalles</span>
                                </button> 
                            </div>

                            <div class="mb-2">
                                <button onclick="detallesProductoById(2)" type="button" class="btn_details btn btn-outline-danger rounded-2xl transition-all gap-2 flex items-center justify-center " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="bi bi-x-circle text-lg"></i> 
                                    <span class="d-none d-md-block text-sm font-bold"> Desactivar</span>
                                </button> 
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