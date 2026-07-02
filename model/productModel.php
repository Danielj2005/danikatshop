<?php

class producto_model extends modeloPrincipal {

    /* funciones de catálogo de productos */
    public static function obtenerCatalogo ($condition) {
        
        $catalogo = modeloPrincipal::consultar("SELECT id, nombre, precio, images FROM productos WHERE state = 1 ORDER BY nombre ASC"); 
        $prices = modeloPrincipal::obtener_precio_dolar(); 
        
        if (mysqli_num_rows($catalogo) > 0) { ?>
            
            <div class="grid gap-4 justify-around items-center grid-cols-1 md:grid-cols-4">
                <?php
    
                    while ($mostrar = mysqli_fetch_array($catalogo)) { 
                        // $idSecure = modeloPrincipal::encryptionId($mostrar["id"]);
    
                        $imgSrc = explode(',', $mostrar['images']);
                        $url = $imgSrc[0];
                ?>
            
                    <div class="product-card group bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden hover:border-purple-500/50 transition-all duration-500 animate-slide-up">
                        <div class="relative h-64 overflow-hidden cursor-pointer">
                            <img src="<?= $url ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute bottom-0 flex flex-wrap gap-2 items-center">
                                <?php if ($condition) : ?>
                                    <?php if ($mostrar["precio"] <= 1.00 ): ?>
                                        <div class="backdrop-blur-md bg-black/60 border border-white/10 bottom-4 left-4 px-4 py-1 relative rounded-full">
                                            <span class="text-sm font-bold text-white">Bajo pedido</span>
                                        </div>
        
                                    <?php else: ?>
                                        <div class="backdrop-blur-md bg-black/60 border border-white/10 bottom-4 left-4 px-4 py-1 relative rounded-full">
                                            <span class="text-sm font-bold text-white">
                                                <?= "$ ".self::formatnumber("USD",$mostrar["precio"]); ?>
                                            </span>
                                            
                                        </div>

                                        <div class="backdrop-blur-md bg-black/60 border border-white/10 bottom-4 left-4 px-4 py-1 relative rounded-full">
        
                                            <span class="text-sm font-bold text-white">
                                                <?= "Bs ".self::formatnumber("VES",$mostrar["precio"] * $prices['USD']); ?>
                                            </span>
                                        </div>
        
                                        <div class="backdrop-blur-md bg-black/60 border border-white/10 bottom-4 left-4 px-4 py-1 relative rounded-full">
                                            <span class="text-sm font-bold text-white">
                                                <?= "€ ".self::formatnumber("VES",$mostrar["precio"] * $prices['EURO']); ?>
                                            </span>
                                        </div>
                                        
                                        <div class="hidden backdrop-blur-md bg-black/60 border border-white/10 bottom-4 left-4 px-4 py-1 relative rounded-full">
                                            <span class="text-sm font-bold text-white">
                                                <?= "USDT ".self::formatnumber("VES",$mostrar["precio"] * ($prices['USD'] * 1.3 )); ?>
                                            </span>
                                            
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="backdrop-blur-md bg-black/60 border border-white/10 bottom-4 left-4 px-4 py-1 relative rounded-full">
                                        <span class="text-sm font-bold text-white"><?= $mostrar["precio"] >= 1.00 ? "$ ".$mostrar["precio"] : 'Bajo pedido' ; ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-white text-md font-semibold mb-1 truncate mb-4"><?= ucwords(strtolower($mostrar['nombre'])) ?></h3>
                            <div class="row justify-content-center align-items-center">
                                <div class="col-12 mb-3">
                                    <form action="./producto.php" method="post">
                                        <input type="hidden" value="<?= $mostrar['id'] ?>" name="id" />
                                        <button type="submit" class="w-full bg-slate-800 hover:bg-purple-600 text-white py-3 rounded-2xl transition-all flex items-center justify-center gap-2">
                                            <i class="bi bi-eye text-lg"></i> <span class="text-sm font-bold">Ver Detalles</span>
                                        </button>
                                    </form>
                                </div>
                                <div class="col-12 mb-3">
                                    <button onclick="askWhatsApp('<?= ucwords(strtolower($mostrar['nombre'])) ?>', <?= $mostrar['precio'] ?>, <?= PHONE ?>)" 
                                        type="submit" class="w-full bg-emerald-800 hover:bg-purple-600 text-white py-3 rounded-3xl transition-all flex items-center justify-center gap-2">
                                            <i class="bi bi-whatsapp text-lg fs-3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php }else{ ?>

            <div class="grid grid-cols-1 gap-4">
                <div class="bg-red-700 border border-slate-800 rounded-[2rem] transition-all duration-500 animate-slide-up">
                    
                    <div class="p-4 text-center">
                        <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                        <h3 class="h1 text-center text-white font-semibold mb-1 truncate mb-4">En este momento no hay productos disponibles.</h3>

                    </div>
                </div>
            </div>
        <?php } 
    }

    // funcion para obtener el id de un categoria
    public static function obtener_id_recien_registrada(){
        $id_producto = mysqli_fetch_array(modeloPrincipal::consultar("SELECT MAX(id) AS id FROM productos"));
        $id_producto = $id_producto['id'];
        return $id_producto;
    }

    public static function formatnumber (string $moneda, float $precio) {
        if ($moneda == "USD") {
            return number_format($precio, 2, ".", ",");
        }else{
            return number_format($precio, 2, ",", ".");
        }
    }

    public static function lista(int $estado = 1, array $prices = []) {
        
        // se guardan los datos en un array y se imprime
        
        $catalogo = modeloPrincipal::consultar("SELECT id, nombre, precio, images, state FROM productos WHERE state = $estado ORDER BY nombre ASC"); 
        
        while ($mostrar = mysqli_fetch_assoc($catalogo)) {

            $imgSrc = $mostrar['images'];

            $id_producto = $mostrar["id"];
            $categorias = modeloPrincipal::consultar("SELECT C.nombre AS categorias FROM `categorias_productos` AS CP 
                INNER JOIN categorias AS C ON C.id = CP.categoria_id
                WHERE CP.producto_id = $id_producto"); 

            $stock = rand(1,60);
            
            $stock = $stock > 30 ? "primary" : $stock;
            $stock = $stock < 30 ? "warning" : $stock;
            $stock = $stock < 20 ? "danger" : $stock;
            $stock = $mostrar["precio"] < 1 ? "secondary" : $stock;
            $stock = $mostrar["precio"] > 1 && $stock ? "success" : $stock;

            ?>
            <tr class="text-center">
                <td class="text-center"></td>
                <td class="text-start">
                    <p class="fw-bold mb-1">
                        <span class="rounded-5 badge fw-bold text-bg-<?= $stock ?> text-<?= $stock ?>">.</span>
                        <?= ucwords(strtolower($mostrar["nombre"])) ?>
                    </p>
                    <small class="d-flex gap-1 text-muted align-items-center"> 
                        <?php while ($cat = mysqli_fetch_assoc($categorias)) { ?> 
                            <span class="bg-indigo-600 badge p-2 text-white rounded-5 text-bg-dark">
                                <?= $cat['categorias'] ?>
                            </span>
                        <?php } ?> 
                    </small>
                </td>
                <td class="text-center">
                    <?php if ($mostrar["precio"] < 1): ?>
                        <div class="flex justify-center gap-2 flex-wrap items-center">
                            <span class="badge text-bg-danger p-2 text-sm">Bajo pedido</span>
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
                        <span class="small d-none d-md-block">Ver Imagen</span>
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
        <?php } 
    }

    public static function actualizar_estado($estado, $id_producto){
        // se comprueba que no exista un registro con los mismos datos
        
        if (!modeloprincipal::UpdateSQL("productos", "state = $estado", "id = $id_producto")) {
            return false;
        }
        return true;
    }

}
