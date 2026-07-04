<?php
session_start();

// importacion de la conexion a la base de datos y al modelo de usuario
require_once "config/SERVER.php";
require_once "model/mainModel.php"; // se incluye el model principal
require_once "model/productModel.php";


$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = isset($_GET['per_page']) ? max(1, intval($_GET['per_page'])) : 15;

$offset = ($page - 1) * $per_page;

$catalogo = mysqli_fetch_all(modeloPrincipal::consultar("SELECT id, nombre, precio, images FROM productos WHERE state = 1 ORDER BY nombre ASC LIMIT $per_page OFFSET $offset")); 
$PHONE = "04244189963";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once "./view/inc/meta_include.php"; ?>

    <!-- Favicons -->
    <link href="./view/img/logo.jpeg" rel="shortcut icon" type="image/x-icon">

    <!-- sweet-alert 2 -->
    <link href="./view/css/sweetalert2.min.css" rel="stylesheet">
    <link href="./view/css/toastify.css" rel="stylesheet">

    <link href="./view/css/bootstrap.min.css" rel="stylesheet">
    <link href="./view/css/bootstrap-icons.css" rel="stylesheet">
    <link href="./view/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <link href="./view/css/animate.min.css" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="./view/css/nice_admin_styles/styles.css" rel="stylesheet">

    <link href="./view/css/catalogo.css" rel="stylesheet">

</head>

<body class="toggle-sidebar" data-bs-theme="dak">

    <?php require_once "./view/inc/catalogo_header.php"; ?>

    <main id="main" class="main">
        <div class="pagetitle mb-5">
            <h1 class="text-center fs-1 titulosH mb-4">Todo lo que buscas en un solo lugar</h1>
            
            <!-- Filtros por Categoría -->
            <div class="dropdown text-center" data-bs-theme="dark">
                <button class="btn btn-primary dropdown-toggle position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-sliders" ></i>
                    <span class="" > Filtros  </span>
                    <span id="num_filter" class="d-none badge position-absolute text-bg-danger" style="top: -.8rem;  right: -1rem;"></span>
                </button>
                
                <ul id="category-filters" class="dropdown-menu overflow-scroll overflow-x-hidden" style="max-height: 25rem;">
                    <li id="dropdown-item-all" class="btn dropdown-item transition-all hover:bg-purple-500/20" >
                        <button onclick="filterByCategory('all', 0)" class="btn category-btn">Todos</button>
                    </li>
                </ul>
            </div>
        </div>
        <section class="section dashboard">

            <div class="producto-card-container">
                <?php
                    
                    foreach ($catalogo as $producto) {
                        $id = $producto[0];
                        $nombre = $producto[1];
                        $precio = $producto[2];
                        $images = explode(',', $producto[3]);
                        $images = $images[0];

                        ?>
                            <div class="fs-4 rounded-4 card p-2" data-bs-theme="drk">
                                <div data-categories="" class="product-card product_<?= $id ?> overflow-hidden">
                                    <div class="position-relative overflow-hidden" style="height: 15rem;">
                                        <img src="<?= $images ?>" class="w-100 h-100 rounded-bottom-0 rounded-4" alt="Imagen del producto">
                                        <div class="badge_precio_container">
                                            <div class="badge_precio badge border border-white position-relative rounded-5">
                                                <span class="precio_card"><?= $precio >= 1.00 ? "$ ".$precio : 'Bajo pedido' ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-top border-2">
                                        <div class="text-start">
                                            <button onclick="detallesProductoById(<?= $id ?>)" class="btn btn-white mb-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#exampleModal"><?= ucwords(strtolower($nombre)) ?></button>
                                        </div>
                                        <div class="row justify-content-around align-items-center">
                                            <div class="w-auto mb-3 text-center">
                                                <button onclick="detallesProductoById(<?= $id ?>)" type="button" class="btn_details w-full p-sm-3 p-md-2 rounded-4 btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    <i class="bi bi-eye text-lg"></i> <span class="small fw-bold">Ver Detalles</span>
                                                </button> 
                                            </div>
                                            <div class="w-auto mb-2 text-center">
                                                <button onclick="askWhatsApp('<?= $nombre ?>', <?= $precio ?>, <?= $PHONE ?>)" 
                                                    type="submit" class="w-full btn btn-success p-sm-3 p-md-2 d-flex items-center justify-center gap-2 rounded-5">
                                                        <i class="bi bi-whatsapp text-lg"></i> <span class="small fw-bold">Consultar por WhatsApp</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        <?php
                    }
                ?>
            </div>
        </section>
    </main>


    <!-- Modal registro de produtos -->
    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="lista_producto_inactivo_modal" tabindex="-1" aria-labelledby="lista_producto_modal_label" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="lista_producto_modal_label"><i class="bi bi-x-circle"></i> Lista de productos Inactivos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <div class="my-3 col-12 text-start">
                        <p class="text-secondary fs-6 fw-bold mb-1">Los Colores de indicadores en nombres de productos significan: </p>
                        <ul class="list-unstyled overflow-hidden">
                            <li class="list-item">
                                <span class="rounded-5 badge fw-bold text-bg-primary text-primary">.</span>
                                <span class="fw-bold">Gran cantidad de stock (50 o más)</span>
                            </li>
                            <li class="list-item">
                                <span class="rounded-5 badge fw-bold text-bg-warning text-warning">.</span>
                                <span class="fw-bold">Poca cantidad de stock (30 o menos)</span>
                            </li>
                            <li class="list-item">
                                <span class="rounded-5 badge fw-bold text-bg-danger text-danger">.</span>
                                <span class="fw-bold">Baja cantidad de stock (20 o menos)</span>
                            </li>
                            <li class="list-item">
                                <span class="rounded-5 badge fw-bold text-bg-success text-success">.</span>
                                <span class="fw-bold">Productos Bajo Pedido</span>
                            </li>
                            <li class="list-item">
                                <span class="rounded-5 badge fw-bold text-bg-secondary text-secondary">.</span>
                                <span class="fw-bold">Productos Cotizados según el pedido</span>
                            </li>
                        </ul>
                    </div>
                    <div id="tableListProducts" class="justify-content-between align-items-center table table-responsive">
                        <table class="table example mb-3 table-striped" id="example">
                            <thead>
                                <tr>
                                    <th class="col text-center" scope="col">N.º</th>
                                    <th class="col text-center" scope="col">Producto</th>
                                    <th class="col text-center" scope="col">Precios</th>
                                    <th class="col text-center" scope="col">Imagenes</th>
                                    <th class="col text-center" scope="col">Editar</th>
                                    <th class="col text-center" scope="col">Activar</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-circle me-2"></i>Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal registro de produtos -->
    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="producto_modal" tabindex="-1" aria-labelledby="producto_modal_label" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="producto_modal_label">Registro de productos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="registrar_producto" action="../controlador/producto_controlador.php" method="post" class="<?= $l_productos == 1 ? '' : 'd-none' ?> tableRegisterProducts text-start SendFormAjax row justify-content-around" autocomplete="off" data-type-form="save">
                        <input type="hidden" name="id_dolar" id="dolar" value="<?php //modeloPrincipal::obtener_id_precio_dolar(); 
                                                                                ?>">
                        <input type="hidden" name="modulo" value="Guardar">

                        <div class="tableRegisterProducts text-center col-12 col-md-4 mb-3 <?= $l_productos == 1 ? 'col-md-6' : 'd-none' ?> ">
                            <button type="button" id="btn_add_card_product" class="col-12 btn btn-success bi bi-plus-circle">&nbsp;Agregar a la Lista de Producto</button>
                        </div>

                        <div id="reader" style="display: none;"></div>

                        <div id="result"></div>

                        <div class="col-12 mb-1">
                            <div class="form-group">
                                <p class="form-p fw-bold">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
                            </div>
                        </div>

                        <div id="tableRegisterProducts" class="<?= $l_productos == 1 ? '' : 'd-none' ?> table table-responsive">
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
                                                <?php //producto_model::options_nombres_productos(); 
                                                ?>
                                            </datalist>
                                        </td>

                                        <td class="col text-center">
                                            <select id="marcas_1" class="form-select mb-3" name="marcas[]" id="input_nombre_marca">
                                                <option selected disabled> Selecciona una opción</option>
                                                <?php //marca_model::optionsId(); 
                                                ?>
                                            </select>
                                        </td>

                                        <td class="col text-center">
                                            <select id="presentacion_1" class="form-select mb-3" name="presentacion[]" id="input_nombre_presentacion">
                                                <option selected disabled> Selecciona una opción</option>
                                                <?php //presentacion_model::optionsId(); 
                                                ?>
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

    <script src="./view/js/nice_admin_scripts/main.js"></script>
    <!-- jquery -->
    <script src="./view/js/jquery-3.6.0.min.js"></script>
    <script src="./view/js/bootstrap.bundle.min.js"></script>
    
    <script src="view/js/sweetalert2.min.js"></script>
    <script src="view/js/DanikatAlert.js"></script>
    <script src="view/js/renderCatalogo.js"></script>
    <script src="view/js/catalogo.js"></script>
    <script src="view/js/index.js"></script>

    <?php
    //include_once "./modal/plantillaModalCustom.php"; 

    // se incluye el footer / pie de pagina a la vista
    include_once "./view/inc/footer.php";
    // se incluyen los script de javascript a la vista 
    // include_once "./inc/scripts_include.php"; 

    //model_user::validar_sesion_activa($id_usuario);

    //config_model::verificar_actualizacion_configuracion(); 
    ?>
    <script src="./view/js/añadir_producto.js"></script>
</body>

</html>