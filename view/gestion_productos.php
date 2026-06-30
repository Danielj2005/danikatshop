<?php 
session_start();

// // importacion de la conexion a la base de datos y al modelo de usuario
// include_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

// $id_usuario = $_SESSION['id_usuario']; // se obtiene el id del usuario
// // validación para verificar que el usuario inicio sesion de manera correcta
// model_user::verificar_intento_de_acceso_al_sistema();

// // include_once "../include/verificacion_primer_inicio_usuario.php"; // se incluyen los modelos necesarios para la vista

// $primer_inicio = $_SESSION['primer_inicio'];

// if($primer_inicio == '1' || $_SESSION['dataUsuario']["primer_inicio"] == '1'){
//     echo "<script type='text/javascript'>
//             window.location.href='./mi_perfil.php';
//         </script>";
//     exit();
// }

// $permiso_productos = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['producto']['productos']);

// $r_productos = modeloPrincipal::verificar_permisos_requeridos(['r_productos']);
// $l_productos = modeloPrincipal::verificar_permisos_requeridos(['l_productos']);

// $categoria = modeloPrincipal::verificar_permisos_requeridos(['r_categoria', 'l_categoria']);
// $r_categoria = modeloPrincipal::verificar_permisos_requeridos(['r_categoria']);
// $l_categoria = modeloPrincipal::verificar_permisos_requeridos(['l_categoria']);

// $presentacion = modeloPrincipal::verificar_permisos_requeridos(['r_presentacion','l_presentacion']);
// $r_presentacion = modeloPrincipal::verificar_permisos_requeridos(['r_presentacion']);
// $l_presentacion = modeloPrincipal::verificar_permisos_requeridos(['l_presentacion']);

// $marca = modeloPrincipal::verificar_permisos_requeridos(['r_marca', 'l_marca']);
// $r_marca = modeloPrincipal::verificar_permisos_requeridos(['r_marca']);
// $l_marca = modeloPrincipal::verificar_permisos_requeridos(['l_marca']);

$permiso_productos = 11;

$r_productos = 1;
$l_productos = 1;

$categoria = 1;
$r_categoria = 1;
$l_categoria = 1;

$presentacion = 1;
$r_presentacion = 1;
$l_presentacion = 1;

$marca = 1;
$r_marca = 1;
$l_marca = 1;

// se evalua que este rol tenga el acceso a esta vista
// if ($permiso_productos) {  ?>

    <!DOCTYPE html>
    <html lang="en">
        <head>
            <!-- titulo --> 
            <title>Gestión de Productos</title> 
            <?php 
                // se incluyen los meta datos 
                include_once "./inc/meta_include.php"; 
                // se incluyen los estilos css y sus librerias a la vista
                include_once "./inc/css_include.php";
            ?>
        </head>
        <body>
            <?php
                // se incluye el header / encabezado a la vista
                include_once "./inc/header.php";
                // se incluye el menu lateral a la vista 
                include_once "./inc/adminSideBar.php";
            ?>
            <main id="main" class="main">
                <div class="pagetitle">
                    <a class="btn btn-outline-secondary mb-3" href="./">
                        <i class="bi bi-chevron-left"></i> 
                        <span>Volver al Panel Principal</span>
                    </a>
                    <h1 class="text-center titulosH my-2">Gestión de Productos</h1>
                </div>
                <section class="section dashboard">
                    <div class="row m-0"> 
                        <div id="card_gestion_productos" class="col-12 mb-3 pagetitle text-center row m-0 p-0 justify-content-around">

                            <?php if ($categoria || $presentacion > 0 || $marca > 0) : 
                                
            
                                $section = [$categoria, $presentacion, $marca];
                                $titleCard = ["Categorías","Presentaciones","Marcas"];
                                $id_modal_lists = ["btn_ver_listas_categoria","btn_ver_listas_presentacion","btn_ver_listas_marca"];
                                $text_btn_modal_lists = ["Lista de Categorías","Lista de Presentaciones","Lista de Marcas"];
                                $text_btn_modal_reg = ["Registrar Nueva Categoría","Registrar Nueva Presentación","Registrar Nueva Marca"];
                                $attb_modal = [
                                    ["registrarCategoria", "listaCategoria"], 
                                    ["registrarPresentacion", "listaPresentacion"], 
                                    ["registrarMarca", "listaMarca"]
                                ];
                                $permisos_section = [
                                    [$r_categoria, $l_categoria], 
                                    [$r_presentacion, $l_presentacion], 
                                    [$r_marca, $l_marca]
                                ];


                                for ($i = 0; $i < 3; $i++) {
                                    if ($section[$i]): ?>

                                        <div class="text-center col-12 col-md-3 fs-4 border card">
                                            <h3 class="text-center mt-2 titulosH fs-4 fw-bold "><?= $titleCard[$i]; ?></h3>

                                            <?php if ($permisos_section[$i][0] == 1 ): ?>

                                                <div class="text-center mb-2">
                                                    <button modal="<?= $attb_modal[$i][0]; ?>" type="button" data-bs-toggle="modal" data-bs-target="#modal" class="mb-2 btn_modal btn btn-primary">
                                                        <i class="bi bi-plus-circle"></i> <?= $text_btn_modal_reg[$i]; ?>
                                                    </button>
                                                </div>

                                            <?php endif; if ($permisos_section[$i][1] == 1 ): ?>

                                                <div class="text-center mb-2">
                                                    <button modal="<?= $attb_modal[$i][1]; ?>" id="<?= $id_modal_lists[$i]; ?>" type="button" class="btn_modal btn btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal">
                                                        <i class="bi bi-list-columns-reverse"></i> <?= $text_btn_modal_lists[$i]; ?>
                                                    </button>
                                                </div>

                                            <?php endif; ?>
                                        </div>   

                                    <?php endif;
                                }

                            ?>
                        </div>

                            <!-- listado de Categoría -->

                        <?php endif; ?>

                        <!-- registro y listado de productos -->

                        <div class="col-12 mb-3 pagetitle text-center">
                            <div class="card">
                                <div class="card-body row p-3">
                                    
                                    <?php if ($r_productos && $l_productos): ?>

                                        <div class="setCol col-md-6 text-center col-md-4 col-12 mb-3">
                                            <button data-bs-target="#producto_modal"  data-bs-toggle="modal" id="btn-toggle" type="button" class="col-12 btn btn-success">
                                                <i class="bi bi-plus-circle"></i> Registrar Productos 
                                            </button>
                                        </div>

                                    <?php endif; ?>

                                    <div class="setCol text-center col-12 col-md-4 mb-2 <?= $r_productos == 0 ? 'col-md-12' : 'col-md-6'?>">
                                        <div class="col-12 dropdown">
                                            <button class="col-12 btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-file-text"></i>
                                                <span>Exportar Lista (.PDF)</span>
                                            </button>

                                            <ul class="dropdown-menu">
                                                <li> <hr class="dropdown-divider"> </li>
                                                <li class="p-2 text-center">
                                                    <a class="btn btn-outline-primary" target="_blank" href="./reportes/lista_productos.php">
                                                        <i class="bi bi-file-text"></i> 
                                                        <span>Todos los Productos</span>
                                                    </a>
                                                </li>
                                                <li> <hr class="dropdown-divider"> </li>
                                                <li class="p-2 text-center">
                                                    <form action="./reportes/lista_productos.php" method="post" class="" target="_blank">
														<input type="hidden" name="UUIDS" value="<?php //modeloPrincipal::encryptionId("1") ?>">
                                                            
                                                        <button type="submit" class="btn btn-outline-success">
                                                            <i class="bi bi-file-text"></i> 
                                                            <span>Productos Con Stock</span>
                                                        </button>
                                                    </form>
                                                </li>
                                                <li> <hr class="dropdown-divider"> </li>
                                                <li class="p-2 text-center">
                                                    <form action="./reportes/lista_productos.php" method="post" class="" target="_blank">
														<input type="hidden" name="UUIDS" value="<?php //modeloPrincipal::encryptionId("0") ?>">
                                                            
                                                        <button type="submit" class="btn btn-outline-danger" >
                                                            <i class="bi bi-file-text"></i> 
                                                            <span>Productos sin Stock</span>
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <hr>
                                    
                                    <h3 id="titleModuleProducts" class="my-2 col-12 fs-4 fw-bold"><?= $l_productos == 1 ? 'Inventario de Productos' : 'Registro de Productos'?></h3>
                                    <div class="my-3 col-12 text-start">
                                        <p class="text-secondary fs-6 fw-bold mb-1 card-title">Productos de color: </p>
                                        <ul class="list-unstyled overflow-hidden">
                                            <?php 
                                                $colors = ["primary","warning","danger"];
                                                $texts = ["Gran cantidad de stock (20 o más)","Poca cantidad de stock (18 o menos)","Baja cantidad de stock (9 o menos)"];

                                                for ($i = 0; $i < 3; $i++) { ?>
                                                    <li class="list-item">
                                                        <span class="rounded-5 badge fw-bold text-bg-<?php echo $colors[$i]; ?> text-<?php echo $colors[$i]; ?>">.</span>
                                                        <span class="fw-bold"><?php echo $texts[$i]; ?></span>
                                                    </li>
                                                <?php }

                                            ?>
                                        </ul>
                                    </div>
                                    <?php if ($l_productos == 1 ): ?>

                                        <div id="tableListProducts" class="justify-content-between align-items-center table table-responsive">
                                            <table class="table example mb-3 table-striped" id="example">
                                                <thead>
                                                    <tr>
                                                        <th class="col text-center" scope="col">N.º</th>
                                                        <th class="col text-center" scope="col">Producto</th>
                                                        <th class="col text-center" scope="col">Stock</th>
                                                        <th class="col text-center" scope="col">Precio de Venta ($)</th>
                                                        <th class="col text-center" scope="col">Última Entrada</th>
                                                        <th class="col text-center" scope="col">Editar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php //producto_model::lista(); ?>  
                                                </tbody>
                                            </table>
                                        </div>

                                    <?php endif; if ($r_productos == 1 ): ?>

                                    <?php endif; ?>  
                                </div>
                            </div>
                        </div>
                    </>
                </section>
            </main>

            <!-- Modal registro de produtos -->
            <div class="modal fade" id="producto_modal" tabindex="-1" aria-labelledby="producto_modal_label" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="producto_modal_label">Registro de productos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="registrar_producto" action="../controlador/producto_controlador.php" method="post" class="<?= $l_productos == 1 ? '' : 'd-none'?> tableRegisterProducts text-start SendFormAjax row justify-content-around" autocomplete="off" data-type-form="save">
                        <input type="hidden" name="id_dolar" id="dolar" value="<?php //modeloPrincipal::obtener_id_precio_dolar(); ?>">
                        <input type="hidden" name="modulo" value="Guardar">
                            
                        <div class="tableRegisterProducts text-center col-12 col-md-4 mb-3 <?= $l_productos == 1 ? 'col-md-6' : 'd-none'?> ">
                            <button type="button" id="btn_add_card_product" class="col-12 btn btn-success bi bi-plus-circle">&nbsp;Agregar a la Lista de Producto</button>
                        </div>

                        <div id="reader" style="display: none;"></div>

                        <div id="result"></div>

                        <div class="col-12 mb-1">
                            <div class="form-group">
                                <p class="form-p">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
                            </div>
                        </div>

                        <div id="tableRegisterProducts" class="<?= $l_productos == 1 ? '' : 'd-none'?> table table-responsive">
                            <table class="table mb-3">
                                <thead>
                                    <tr>
                                        <th class="col text-center" scope="col">Código<span style="color:#f00;"> * </span></th>
                                        <th class="col text-center" scope="col">Nombre <span style="color:#f00;"> * </span></th>
                                        <th class="col text-center" scope="col">Marca <span style="color:#f00;"> * </span></th>
                                        <th class="col text-center" scope="col">Presentación <span style="color:#f00;"> * </span></th>
                                        <th class="col text-center" scope="col">Categoría <span style="color:#f00;"> * </span></th>
                                        <th class="col text-center" scope="col">Quitar</th>
                                    </tr>
                                </thead>
                                <tbody id="tableProduct">
                                    <tr id="producto_1">

                                        <td class="col text-center">
                                            <div class="col-12 mb-3 input-group">
                                                <button type="button" id="startButton" class="bi-qr-code-scan input-group-text"></button>
                                                <input type="text" minlength="2" maxlength="13" class="form-control" name="code[]" id="code" placeholder="Escribe el código del producto" autocomplete="off">
                                            </div>
                                        </td>

                                        <td class="col text-center">
                                            <input type="text" class="form-control mb-3" list="datalist_nombre_productos" name="nombre_producto[]" id="input_nombre_producto2" placeholder="ingresa el nombre" autocomplete="off">
                                            <datalist id="datalist_nombre_productos">
                                                <?php //producto_model::options_nombres_productos(); ?> 
                                            </datalist>
                                        </td>

                                        <td class="col text-center">
                                            <select id="marcas_1" class="form-select mb-3" name="marcas[]" id="input_nombre_marca" >
                                                <option selected disabled> Selecciona una opción</option>
                                                <?php //marca_model::optionsId(); ?>
                                            </select>
                                        </td>

                                        <td class="col text-center">
                                            <select id="presentacion_1" class="form-select mb-3" name="presentacion[]" id="input_nombre_presentacion">
                                                <option selected disabled> Selecciona una opción</option>
                                                <?php //presentacion_model::optionsId(); ?>
                                            </select>
                                        </td>

                                        <td class="col text-center">
                                            <select id="categoria_1" class="form-select mb-3" name="categoria[]">
                                                <option selected disabled> Selecciona una opción</option>
                                                <?php //category_model::optionsId(); ?>
                                            </select>
                                        </td>

                                        <td class="col text-center">
                                            <button type="button" onclick="document.getElementById(`producto_1`).remove();" class="btn btn-outline-danger bi bi-trash"></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="registrar_producto" class="btn btn-primary"><i class="bi bi-check-circle me-2"></i>Registrar</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-circle me-2"></i>Cancelar</button>
                </div>
                </div>
            </div>
            </div>
            <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
            <script src="./js/scanQr.js" type="text/javascript"></script>

            <script type="text/javascript">


                // mostrar u ocultar el campo de datos del proveedor segun el tipo de compra seleccionado
                const dataBuyEntries = () => {
                    const tipoCompra = document.querySelector('#tipo_compra_id').value;
                    const datProvider = document.querySelector('#datProvider');

                    if (tipoCompra === 'compra_proveedor' && datProvider.classList.contains('d-none')) {
                        datProvider.classList.remove('d-none');
                    }else{
                        datProvider.classList.add('d-none');
                    }
                };

                const toggle = ()=> {
                    const btnToggle = document.getElementById('btn-toggle');
                    btnToggle.classList.toggle('btn-success');
                    btnToggle.classList.toggle('btn-secondary');

                    const titleBtn = [
                        '<i class="bi bi-plus-circle"></i> Registrar Productos ',
                        `<i class="bi bi-list-columns-reverse"></i> Ver lista `
                    ];

                    btnToggle.innerHTML = btnToggle.innerHTML == titleBtn[0] ? titleBtn[1] : titleBtn[0];

                    document.getElementById('tableRegisterProducts').classList.toggle('d-none');
                    document.getElementById('tableListProducts').classList.toggle('d-none');

                    const titleRegister = "Registro de Productos";
                    const titleList = "Inventario de Productos";
                    const titleModule = document.getElementById('titleModuleProducts');
                    
                    titleModule.textContent == titleList ? titleModule.textContent = titleRegister : titleModule.textContent = titleList;

                    document.querySelectorAll('.tableRegisterProducts').forEach(element => {
                        element.classList.toggle('d-none');
                    });
                    document.querySelectorAll('.setCol').forEach(element => {
                        element.classList.toggle('col-md-6');
                    });
                };
            
            </script>

            <?php 
                //include_once "./modal/plantillaModalCustom.php"; 
                
                // se incluye el footer / pie de pagina a la vista
                include_once "./inc/footer.php";
                // se incluyen los script de javascript a la vista 
                include_once "./inc/scripts_include.php"; 
            
                //model_user::validar_sesion_activa($id_usuario);
        
                //config_model::verificar_actualizacion_configuracion(); 
            ?>
            <script src="./js/añadir_producto.js"></script>
        </body>
    </html>

<?php //}else{
    // se registran las acciones del usuario en la bitacora y es redirijido al inicio
   // bitacora::intento_de_acceso_a_vista_sin_permisos("Gestión de Productos");
//}