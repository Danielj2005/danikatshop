<?php

class producto_model extends modeloPrincipal {

    /* funciones de catálogo de productos */
    public static function obtenerCatalogo () {
        
        $catalogo = modeloPrincipal::consultar("SELECT id, nombre, precio, images FROM productos WHERE state = 1 ORDER BY nombre ASC"); 
        // $telefono = mysqli_fetch_assoc(modeloPrincipal::consultar("SELECT telefono FROM uders WHERE role = 2"))['telefono']; 

        
        if (mysqli_num_rows($catalogo) > 0) { ?>
            
            <div class="row justify-content-around align-items-center">
                <?php
    
                    while ($mostrar = mysqli_fetch_array($catalogo)) { 
                        // $idSecure = modeloPrincipal::encryptionId($mostrar["id"]);
    
                        $imgSrc = explode(',', $mostrar['images']);
                        $url = $imgSrc[0];
                ?>
            
                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 group bg-slate-900/40 border border-slate-800 rounded-[2rem] overflow-hidden hover:border-purple-500/50 transition-all duration-500 animate-slide-up">
                        <div class="relative h-64 overflow-hidden cursor-pointer" onclick="openModal(<?= $mostrar['id'] ?>)">
                            <img src="<?= $url ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute bottom-4 left-4 bg-black/60 backdrop-blur-md px-4 py-1 rounded-full border border-white/10">
                                <span class="text-sm font-bold text-white"><?= $mostrar['precio'] >= 1.00 ? '$' . $mostrar['precio'] : 'Bajo pedido' ?></span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-white text-md font-semibold mb-1 truncate mb-4"><?= ucwords(strtolower($mostrar['nombre'])) ?></h3>
                            <div class="row justify-content-center align-items-center">
                                <div class="col-12 mb-3">
                                    <form action="./producto" method="get" data-type-form="load">
                                        <input type="hidden" value="<?= $mostrar['id'] ?>" name="id" />
                                        <button type="submit" class="w-full bg-slate-800 hover:bg-purple-600 text-white py-3 rounded-2xl transition-all flex items-center justify-center gap-2">
                                            <i class="bi bi-eye text-lg"></i> <span class="text-sm font-bold">Ver Detalles</span>
                                        </button>
                                    </form>
                                </div>
                                <div class="col-12 mb-3">
                                    <form action="./producto" method="get" data-type-form="load">
                                        <input type="hidden" value="<?= $mostrar['id'] ?>" name="id" />
                                        <button type="submit" class="w-full bg-slate-800 hover:bg-purple-600 text-white py-3 rounded-2xl transition-all flex items-center justify-center gap-2">
                                            <i class="fab fa-whatsapp text-lg"></i> <span class="text-sm font-bold">Consultar</span>
                                        </button>
                                    </form>
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

    public static function obtenerProductosPublicados (){

        $catalogo = modeloPrincipal::consultar("SELECT id, nombre, precio, images FROM productos ORDER BY id ASC"); 

        while ($mostrar = mysqli_fetch_array($catalogo)) {  
            
            $imgSrc = explode(',', $mostrar['images']);
            $url = $mostrar["id"].'/'.$imgSrc[0];
        ?>

            <div class="bg-slate-900/40 border border-slate-800 p-3 rounded-2xl flex items-center gap-4 hover:bg-slate-800/40 transition">
                <img src="storage/<?= $url ?>" class="w-16 h-16 object-cover rounded-xl shadow-md">
                <div class="flex-1 min-w-0">
                    <h4 class="text-white font-medium truncate"><?= ucwords(strtolower($mostrar['nombre'])) ?></h4>
                    <p class="text-slate-500 text-xs"><?= $mostrar['price'] ? '$' . $mostrar['price'] : 'Bajo pedido' ?></p>
                </div>
                <div class="flex gap-2">
                    <button onclick="prepareEdit(<?= $mostrar['id']; ?>)" class="p-2 text-blue-400 hover:bg-blue-400/10 rounded-lg transition"><i class="fas fa-edit"></i></button>
                    <button onclick="deleteProduct(<?= $mostrar['id']; ?>)" class="p-2 text-red-400 hover:bg-red-400/10 rounded-lg transition"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        <?php }
    }




    /* funciones de otro modelo de negocios */
    public static function consultar_producto($fields) {
        $consul = modeloPrincipal::consultar("SELECT $fields FROM productos");
        modeloPrincipal::verificar_consulta($consul,'producto'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    public static function consultar_condicional($fields, $condicion) {
        $consul = modeloPrincipal::consultar("SELECT $fields FROM producto WHERE $condicion");
        modeloPrincipal::verificar_consulta($consul,'producto'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    public static function consultar_por_id($id_producto) {
        $consul = modeloPrincipal::consultar("SELECT M.nombre as marca, 
            PS.cantidad AS presentacion, R.nombre AS representacion, 
            P.stock_actual, P.precio_venta,
            P.id_producto, P.codigo, P.nombre_producto, 
            C.nombre AS categoria, P.fecha_ultima_actualizacion, P.estado
            FROM producto AS P
            INNER JOIN categoria AS C ON C.id_categoria = P.id_categoria 
            INNER JOIN presentacion AS PS ON PS.id = P.id_presentacion
            INNER JOIN representacion AS R ON R.id = PS.id_representacion
            INNER JOIN marca AS M ON M.id = P.id_marca
            WHERE P.id_producto = $id_producto
        ");
        modeloPrincipal::verificar_consulta($consul,'producto'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    // funcion para obtener el id de un categoria
    public static function obtener_datos_recien_registrados($id_producto) {
        $Json = [
            'codigo' => [],
            'nombre' => [],
            'categoria' => [],
            'presentacion' => [],
            'marca' => []
        ];

        for ($i = 0; $i < count($id_producto); $i++) {
            $consul = modeloPrincipal::consultar("SELECT P.codigo, P.nombre_producto,
                C.nombre AS categoria, 
                PS.cantidad AS presentacion, R.nombre AS representacion,
                M.nombre AS marca
                FROM producto AS P
                INNER JOIN categoria AS C ON C.id_categoria = P.id_categoria 
                INNER JOIN presentacion AS PS ON PS.id = P.id_presentacion 
                INNER JOIN representacion AS R ON R.id = PS.id_representacion
                INNER JOIN marca AS M ON M.id = P.id_marca
                WHERE P.id_producto = " . $id_producto[$i]);

            if (mysqli_num_rows($consul) > 0) {
                $resultado = mysqli_fetch_array($consul);

                $Json['codigo'][] = $resultado['codigo'];
                $Json['nombre'][] = $resultado['nombre_producto'];
                $Json['categoria'][] = $resultado['categoria'];
                $Json['presentacion'][] = $resultado['presentacion']." ".$resultado['representacion'];
                $Json['marca'][] = $resultado['marca'];
            }
        }
        return $Json;
    }


    public static function obtener_todos_los_datos(){
        $consul = modeloPrincipal::consultar("SELECT M.nombre as marca, 
            PS.cantidad AS presentacion, R.nombre AS representacion, P.stock_actual, P.precio_venta,
            P.id_producto, P.codigo, P.nombre_producto, C.nombre AS categoria, P.fecha_ultima_actualizacion,
            (SELECT MAX(dolar) FROM dolar) AS tasa
            FROM producto AS P
            INNER JOIN categoria AS C ON C.id_categoria = P.id_categoria 
            INNER JOIN presentacion AS PS ON PS.id = P.id_presentacion
            INNER JOIN representacion AS R ON R.id = PS.id_representacion
            INNER JOIN marca AS M ON M.id = P.id_marca
            ORDER BY P.stock_actual DESC
        ");

        modeloPrincipal::verificar_consulta($consul,'producto'); // se verifica si la consulta fue exitosa
        return $consul;
    }
    
    // funcion para obtener el id de un categoria
    public static function obtener_id_recien_registrada(){
        $id_producto = mysqli_fetch_array(modeloPrincipal::consultar("SELECT MAX(id) AS id FROM productos"));
        $id_producto = $id_producto['id'];
        return $id_producto;
    }

    
    public static function registrar ($producto, $price, $desc, $images) {

        $registrar = modeloPrincipal::InsertSQL("productos", "nombre, precio, description, images, state, created_at" ,"'$producto', '$price', '$desc', '$images', 1, NOW()");
        return $registrar;
    }

    public static function validar_existe($campos, $id_producto){
        // se comprueba que no exista un registro con los mismos datos
        $consult = modeloPrincipal::validacion_registro_existente($campos,"producto","id_producto = $id_producto");

        if (!$consult) {
            alert_model::alert_register_exist();
            exit(); 
        }

    }


    public static function lista($estado = 1){
        
        // se guardan los datos en un array y se imprime
        
        $catalogo = modeloPrincipal::consultar("SELECT id, nombre, precio, images, state FROM productos WHERE state = $estado ORDER BY nombre ASC"); 
        


        while ($mostrar = mysqli_fetch_assoc($catalogo)) {
            $idSecure = modeloPrincipal::encryptionId($mostrar["id"]); 

            $imgSrc = $mostrar['images'];
            // $url = '.' . $imgSrc[0];

            $id_producto = $mostrar["id"];
            $categorias = modeloPrincipal::consultar("SELECT C.nombre AS categorias FROM `categorias_productos` AS CP 
                INNER JOIN categorias AS C ON C.id = CP.categoria_id
                WHERE CP.producto_id = $id_producto"); 

            ?>
            <tr class="text-center">
                <td class="text-center"></td>
                <td class="text-start">
                    <p class=" fw-bold mb-1"><?= ucwords(strtolower($mostrar["nombre"])) ?> </p>
                    <small class="flex gap-1 text-muted items-center"> 
                        <?php while ($cat = mysqli_fetch_assoc($categorias)) { ?> 
                            <span class="bg-indigo-600 text-white px-2 py-1 rounded-3xl text-xs">
                                <?= $cat['categorias'] ?>
                            </span>
                        <?php } ?> 
                    </small>
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center align-items-center">
                        <span class="badge text-bg-secondary fs-6">
                            <?= $mostrar["precio"] < 1 ? "Bajo pedido": "$ ".$mostrar["precio"]; ?>
                        </span>
                    </div>
                </td>
                <td>
                    <button onclick="verImagen('<?= $imgSrc; ?>','<?= $mostrar['nombre'] ?>' )" class="bg-slate-500 hover:bg-slate-800 text-white px-2 py-1 rounded text-xs">
                        <i class="fas fa-image mr-1"></i> 
                        <span class="xs:hidden font-bold">Ver Imagen</span>
                    </button>
                </td>
                <td class="col text-center">
                    <button data-bs-toggle="modal" data-bs-target="#editar_producto"
                        onclick="editingProduct('<?= modeloPrincipal::encryptionId($mostrar['id']) ?>')" class="btn_edit_produto btn btn-warning">
                            <i class="bi bi-pencil-square"></i>
                    </button>
                </td>
                <td class="col text-center">
                    <?php 
                        if ($mostrar["state"] == 1) { ?>
                        <form action="../controller/producto_controlador.php" method="post" class="SendFormAjax" data-type-form="update_estate" >
                            <input type="hidden" name="modulo" value="activo">          
                            <input type="hidden" name="id" value="<?= modeloPrincipal::encryptionId($mostrar['id']) ?>">
                            <button class="btn btn-success bi-check-circle" title="state de la categoría"></button>
                        </form>
                    <?php } else { ?>
                        <form action="../controller/producto_controlador.php" method="post" class="SendFormAjax" data-type-form="update_estate" >
                            <input type="hidden" name="modulo" value="inactivo">          
                            <input type="hidden" name="id" value="<?= modeloPrincipal::encryptionId($mostrar['id']) ?>">
                            <button class="btn btn-danger bi-x-circle" title="estado del producto" type="submit"></button>
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

    public static function delete_producto($id_producto){
        // se comprueba que no exista un registro con los mismos datos
        

        if (!modeloprincipal::DeleteSQL("producto", "id = $id_producto")) {
            return false;
        }
        return true;
    }


    public static function options_nombres_productos() {
        $consulta = modeloPrincipal::consultar("SELECT lower(nombre_producto) AS nombre_producto FROM producto GROUP BY nombre_producto");
        // se guardan los datos en un array y se imprime
        
        while ( $mostrar = mysqli_fetch_array($consulta)) {  ?>
            <option value="<?= ucwords(strtolower($mostrar["nombre_producto"])); ?>">
                <?= ucwords(strtolower($mostrar["nombre_producto"])); ?>
            </option>
        <?php }
    }

    //  funcion para asignar el color a un producto en una lista segun su stock actual
    public static function asignar_color_segun_stock($stock) {
    
        if ($stock == "0") { return 'text-danger';

        } elseif ($stock < "5" && $stock > "0") { return 'text-warning';

        } else { return 'text-success'; }
    }
    
    /*******************************************************************/ 
    /*     Funciones dedicadas a resolver peticiones del usuario       */
    /*******************************************************************/ 

    // funcion para agregar un producto a la lista de compras (entradas)

    public static function productos_compra_a_proveedores ($id) {
        $id_producto = modeloPrincipal::limpiar_cadena($id);

        $consulta = modeloPrincipal::consultar("SELECT P.id_producto, P.codigo, P.nombre_producto, P.stock_actual,
            PS.cantidad AS presentacion, R.nombre AS representacion,
            M.nombre AS marca,
            C.nombre AS categoria
            FROM producto AS P 
            INNER JOIN presentacion AS PS ON P.id_presentacion = PS.id 
            INNER JOIN representacion AS R ON R.id = PS.id_representacion
            INNER JOIN marca AS M ON M.id = P.id_marca
            INNER JOIN categoria AS C ON P.id_categoria = C.id_categoria
            WHERE P.id_producto = $id_producto");

                
        while ( $mostrar = mysqli_fetch_array($consulta)) { 
                
            $color_stock = self::asignar_color_segun_stock($mostrar["stock_actual"]);  
            ?>

                <tr id="tr_producto_<?= modeloPrincipal::encryptionId($mostrar['id_producto']) ?>" >
                    <td class="col text-start w-25" scope="col">
                        <p class="text-secondary fw-bold mb-1">
                            Código: <?= $mostrar["codigo"] ?>
                        </p>
                        <p class="text-primary fw-bold mb-1">
                            <?= $mostrar["nombre_producto"] . ' - ' . $mostrar["marca"] ?>
                        </p>
                        <small class="d-block text-muted">
                            Formato: <?= $mostrar["presentacion"] . ' / ' . $mostrar["representacion"] ?>
                        </small>
                        <p class="fst-italic mb-0 <?= $color_stock ?>">
                            Stock actual: <?= $mostrar["stock_actual"] ?> unidades
                        </p>
                        
                        <input type="hidden" name="id_producto[]" value="<?= modeloPrincipal::encryptionId($mostrar["id_producto"]); ?>" required>
                    </td>
                    
                    <td class="col text-center" scope="col">
                        <div class="text-secondary fw-bold mb-1 row justify-content-center">
                            <input type="text" minlength="1" maxlength="200" class="w-75 form-control form-control-sm cantidad" name="cantidad[]" onchange="calcular_total();" placeholder="ingresa la cantidad a ingresar" id="cantidad_<?= modeloPrincipal::encryptionId($mostrar["id_producto"]) ?>" required>
                        </div>
                    </td>
                    
                    <td class="col text-center" scope="col">
                        <div class="text-secondary fw-bold mb-1 row justify-content-center">
                            <input type="text" maxlength="8" class="w-75 form-control form-control-sm precio_unidad_dolar text-end" onchange="convertir_usd_a_bs('<?= modeloPrincipal::encryptionId($mostrar['id_producto']) ?>'); calcular_total();" name="precio_unidad_dolar[]" placeholder="ingresa el Precio por unidad en $" id="precio_unidad_dolar_<?= modeloPrincipal::encryptionId($mostrar['id_producto']) ?>" required>
                        </div>
                    </td>
                    
                    <td class="col text-center" scope="col">
                        <div class="text-secondary fw-bold mb-1 row justify-content-center">
                            Costo Unitario (Bs)
                            <input type="text" minlength="1" maxlength="100" readonly class="w-75 bg-secondary-subtle form-control form-control-sm precio_unidad_bs text-end" name="precio_unidad_bs[]" placeholder="ingresa el costo unitario (Bs.)" id="precio_unidad_bs_<?= modeloPrincipal::encryptionId($mostrar["id_producto"]) ?>" required>
                        </div>
                        <div class="text-secondary fw-bold mb-1 row justify-content-center">
                            Precio Venta ($)
                            <input type="text" minlength="1" maxlength="10" readonly class="w-75 text-end bg-secondary-subtle input form-control form-control-sm" name="precio_venta_dolar[]" placeholder="ingresa el precio de venta ($)" id="precio_venta_dolar_<?= modeloPrincipal::encryptionId($mostrar["id_producto"]) ?>" required>
                        </div>
                    </td>

                    <td class="text-center col" scope="col">
                        <button type="button" class="btn btn-danger bi bi-trash" onclick="quitar_elemento('tr_producto_<?= modeloPrincipal::encryptionId($mostrar['id_producto']) ?>')"></button>
                    </td>
                </tr>

            <?php
        }
    }

    // funcion para agregar un producto a la lista de servicios
    
    public static function añadir_productos_a_servico ($id) {
        $id_producto = modeloPrincipal::limpiar_cadena($id);

        $consulta = modeloPrincipal::consultar("SELECT P.id_producto, P.codigo, P.nombre_producto AS producto, 
            P.stock_actual AS stock,
            PS.cantidad AS presentacion, R.nombre AS representacion,
            M.nombre AS marca,
            C.nombre AS categoria
            FROM producto AS P 
            INNER JOIN presentacion AS PS ON P.id_presentacion = PS.id 
            INNER JOIN representacion AS R ON R.id = PS.id_representacion
            INNER JOIN categoria AS C ON P.id_categoria = C.id_categoria
            INNER JOIN marca AS M ON P.id_marca = M.id
            WHERE P.id_producto = $id_producto");
        // se guardan los datos en un array y se imprime

        while ( $mostrar = mysqli_fetch_array($consulta)) { 

            $color_stock = self::asignar_color_segun_stock($mostrar["stock"]);  
            ?>

                <tr id="tr_producto_<?= modeloPrincipal::encryptionId($mostrar["id_producto"]) ?>" >
                    <td class="col text-center" scope="col">
                        <p class="text-primary fw-bold mb-1">
                            <?= $mostrar["producto"] . ' - ' . $mostrar["marca"] ?>
                        </p>
                        <small class="d-block text-muted">
                            Formato: <?= $mostrar["presentacion"] . ' ' . $mostrar["representacion"] ?>
                        </small>
                        <p class="fst-italic mb-0 <?= $color_stock ?>">
                            Stock actual: <?= $mostrar["stock"] ?> unidades
                        </p>
                        <input type="hidden" name="id_producto[]" value="<?= modeloPrincipal::encryptionId($mostrar["id_producto"]) ?>" required>
                    </td>

                    <td class="col text-center" scope="col">
                        <div class="d-flex justify-content-center" >
                            <input 
                                type="text" 
                                class="w-25 form-control form-control-sm cantidad text-center" 
                                name="cantidad[]" 
                                placeholder="0" 
                                min="1"
                                min="100"
                                step="1"
                                title="Ingrese la cantidad de unidades que están ingresando al almacén."
                                id="cantidad_<?= modeloPrincipal::encryptionId($mostrar["id_producto"]) ?>" 
                                required
                            >
                        </div>
                    </td>
                    
                    <td class="text-center col" scope="col">
                        <button 
                            type="button" 
                            class="btn btn-outline-danger" 
                            title="Quitar este artículo del servicio"
                            onclick="quitar_elemento('tr_producto_<?= modeloPrincipal::encryptionId($mostrar['id_producto']) ?>')"
                        >
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>
            <?php
        }
    }

    //  funcion para añadir un producto a la venta
    public static function añadir_productos_a_venta ($id) {
        $id_producto = modeloPrincipal::limpiar_cadena($id);

        $consulta = modeloPrincipal::consultar("SELECT P.codigo, P.id_producto, P.nombre_producto, 
            PS.cantidad AS presentacion, R.nombre AS representacion,
            P.stock_actual, P.precio_venta, 
            C.nombre AS categoria, 
            M.nombre as marca,
            round((SELECT MAX(dolar) FROM dolar) * P.precio_venta, 2) AS precio_bs
            FROM producto AS P 
            INNER JOIN presentacion AS PS ON P.id_presentacion = PS.id 
            INNER JOIN representacion AS R ON R.id = PS.id_representacion
            INNER JOIN marca AS M ON M.id = P.id_marca
            INNER JOIN categoria AS C ON P.id_categoria = C.id_categoria 
            WHERE P.id_producto = $id_producto");

        // se guardan los datos en un array y se imprime
        while ( $mostrar = mysqli_fetch_array($consulta)) { 

            $color_stock = self::asignar_color_segun_stock($mostrar["stock_actual"]);  

            ?>
                <tr id="tr_producto_<?= modeloPrincipal::encryptionId($mostrar['id_producto']) ?>" >
                    <input type="hidden" name="id_producto[]" value="<?= modeloPrincipal::encryptionId($mostrar["id_producto"]) ?>" required>
                    <input type="text" name="id_producto[]" value="" required>
                    <td class="col text-start" scope="col">
                        <p style="width: 15rem;" class="text-secondary fw-bold mb-1">
                            Código: <?= $mostrar["codigo"] ?>
                        </p>
                        <p class="text-primary fw-bold mb-1">
                            <?= $mostrar["marca"] . ' - ' . $mostrar["nombre_producto"] . ' ' . $mostrar["presentacion"] . ' ' . $mostrar["representacion"] ?>
                        </p>
                        <small class="d-block text-muted">
                            <span class="fw-bold">Categoria:</span> <?= $mostrar["categoria"] ?>
                        </small>
                        <p class="fw-bold fst-italic mb-0 <?= $color_stock ?>">
                            Stock actual: <?= $mostrar["stock_actual"] ?> unidades
                        </p>
                    </td>

                    <td class="col text-center" scope="col">
                        <input style="width: 10rem;" type="text" class="form-control form-control-sm cantidad" name="cantidad[]" placeholder="ingresa la cantidad a vender" id="cantidad<?= $mostrar['id_producto'] ?>" onkeyup="monto_total_productos();" required>
                    </td>

                    <td class="col text-center" scope="col">
                        <input type="hidden" class=" precio_dolar" name="precio_producto_dolar[]" id="precio_dolar<?= $mostrar['id_producto'] ?>" value="<?= $mostrar["precio_venta"] ?>" required>
                        <input type="hidden" class=" precio_bs" name="precio_producto_bolivar[]" id="precio_bs<?= $mostrar['id_producto'] ?>" value="<?= $mostrar["precio_bs"] ?>" required>
                    
                        <div class="row justify-content-center">
                            <p class="col-12 mb-2"><span class="badge text-bg-success fs-6"> <?= modeloPrincipal::number_format_prices($mostrar["precio_venta"]); ?> $ </span></p>
                            <p class="col-12 mb-2"><span class="badge text-bg-secondary fs-6"> <?= modeloPrincipal::number_format_prices($mostrar["precio_bs"]); ?> Bs</span> </p>
                        </div>
                        
                    </td>
                    
                    <td class="text-center col" scope="col">
                        <button type="button" class="btn btn-danger bi bi-trash" onclick="quitar_elemento('tr_producto_<?= modeloPrincipal::encryptionId($mostrar['id_producto']) ?>')"></button>
                    </td>
                </tr>
            <?php
        }
    }

    // funcion para añadir productos a una entrada (compra)
    public static function añadir_productos_para_registrar ($id) {
        
        $rand = rand(10000,50000);
        ?>

        <tr id="producto_<?= $rand ?>">

            <td class="text-center">
                <div class="col-12 mb-3 input-group">
                    <button type="button" id="startButton" class="bi-qr-code-scan input-group-text"></button>
                    <input type="text" minlength="2" maxlength="13" class="form-control" name="code[]" id="code<?= $rand ?>" placeholder="Escribe el código del producto" autocomplete="off">
                </div>
            </td>

            <td class="text-center">
                <div class="col-12 mb-3">
                    <input type="text" class="form-control mb-3" list="Nombre_dataList_<?= $rand ?>" name="nombre_producto[]" id="input_nombre_producto2" placeholder="Escribe el nombre" autocomplete="off">

                    <datalist id="Nombre_dataList_<?= $rand ?>">
                        <?php self::options_nombres_productos(); ?> 
                    </datalist>
                </div>
            </td>
            <td class="text-center">
                <div class="col-12 mb-3">
                    <select class="form-select mb-3" name="marcas[]" id="marca_<?= $rand ?>">
                        <option selected disabled>Selecciona una opción</option>
                        <?php marca_model::optionsId(); ?> 
                    </select>
                </div>
            </td>
            <td class="text-center">
                <div class="col-12 mb-3">
                    <select class="form-select mb-3" name="presentacion[]" id="presentacion<?= $rand ?>">
                        <option selected disabled>Selecciona una opción</option>
                        <?php presentacion_model::optionsId(); ?>
                    </select>
                </div>
            </td>
            <td class="text-center">
                <div class="col-12 mb-3">
                    <select class="form-select mb-3" name="categoria[]" id="categoria<?= $rand ?>">
                        <option selected disabled>Selecciona una opción</option>
                        <?php category_model::optionsId(); ?>
                    </select>
                </div>
            </td>

            <td class="text-center">
                <button type="button" onclick="document.getElementById(`producto_<?= $rand ?>`).remove();" class="btn btn-outline-danger bi bi-trash"></button>
            </td>
        </tr>
    <?php 
    }

    public static function bitacora_registro_productos ($cambios) {
        
        bitacora::bitacora("Modificación exitosa del estado de una categoría.",'<p class="mb-3 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp; Se modificó el estado de una categoría con la siguiente informacón.</p> 
            <h4 class="text-center card-title"><b> Información de la categoría </b></h4>
            <div class="d-flex justify-content-between border-bottom"> <p> Nombre</p> '.$cambios['nombre'].' </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Descripción</p> '.$cambios['descripcion'].' </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Estado</p> '.$cambios['estado'].' </div>');
        
    }
}
