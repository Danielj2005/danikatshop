<?php

require_once "../config/SERVER.php";
require_once "../model/mainModel.php"; 

try {

    $method = $_SERVER['REQUEST_METHOD'];

    // --- OBTENER PRODUCTOS PARA EDITAR (GET) ---
    if ($method === 'GET') {
        $details = $_GET['details'] ?? false;

        if ($details): 
            $id = $_GET['UID'];
            $path = $_GET['path'] ?? null;
            
            $phone = $conn->prepare("SELECT telefono FROM users WHERE id = 2");
            $phone->execute();
            $number = $phone->fetchAll(PDO::FETCH_ASSOC);
            $phone = $number[0];
            
            $stmt = $conn->prepare("SELECT * FROM productos WHERE id = $id");
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $products = $products[0];

            // Ruta de la carpeta de imágenes
            $files = explode(",",$products['images']);  
            $quety = $products;
            
        ?>
            <div class="custom-carousel">
                <div class="carousel-track-container">
                    <ul class="carousel-track" id="carouselTrack">
                        <?php foreach ($files as $file) { ?>
                            <li class="carousel-slide ${active}">
                                <img src=".<?= $file ?>" onerror="this.onerror=null; this.src='./img/404.png';" onerror="this.src='ruta/imagen-no-encontrada.jpg'">
                            </li>
                        <?php  } ?>
                    </ul>
                </div>
                <?php if (count($files) > 1) { ?>
                    <button class="carousel-button prev-btn" id="prevBtn">&#10094;</button>
                    <button class="carousel-button next-btn" id="nextBtn">&#10095;</button>
                <?php  } ?>
            </div>

            
            <hr class="md:hidden mt-5 mb-3">
            <div class="p-3 text-start border border-slate-800 rounded-3" style="height: fit-content;">
                <h3 class="fw-bold mb-4 text-xl">
                    <?= ucwords(strtolower($quety['nombre'])) ?>
                </h3>
                <div class="">
                    <div class="bg_badge_precio badge border border-white rounded-5">
                        <span class="fs-5"><?= $quety['precio'] >= 1.00 ? "$ ".$quety['precio'] : 'Bajo pedido' ?></span>
                    </div>
                </div>
                
                <p class="col-12 fw-bold text-muted mb-4 "><?= $quety['description'] ?></p>
            </div>

        <?php else:
            $id = modeloPrincipal::decryptionId($_GET['UID']);

            $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $products = $products[0];
            // Convertimos el string de imágenes de nuevo a Array para el JS
            $products['imgs'] = explode(",",$products['images']);  ?>

                <input name="id" value="<?= modeloPrincipal::encryptionId($id) ?>" type="hidden">
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
        endif;            
    }

    
} catch(PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>