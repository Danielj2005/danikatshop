<?php
session_start();

// importacion de la conexion a la base de datos y al modelo de usuario
require_once "config/SERVER.php";
require_once "model/mainModel.php"; // se incluye el model principal
require_once "model/productModel.php";

// se evalua que este rol tenga el acceso a esta vista
// if ($permiso_productos) {  
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- titulo -->
    <title>DANIKAT SHOP</title>
    <!-- metadatos -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Control de Inventario, Punto de Venta y Gestión de Clientes y Proveedores.">
    <meta name="keywords" content="Inventario, POS, gestión de clientes, proveedores">
    <meta name="author" content="DANIEL BARRUETA">

    <!-- Favicons -->
    <link href="./view/img/logo.jpeg" rel="shortcut icon" type="image/x-icon">

    <!-- sweet-alert 2 -->
    <link href="./view/css/sweetalert2.min.css" rel="stylesheet">
    <link href="./view/css/toastify.css" rel="stylesheet">

    <link href="./view/css/select2.min.css" rel="stylesheet">

    <link href="./view/css/bootstrap.min.css" rel="stylesheet">
    <link href="./view/css/bootstrap-icons.css" rel="stylesheet">
    <link href="./view/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <link href="./view/css/animate.min.css" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="./view/css/nice_admin_styles/styles.css" rel="stylesheet">


</head>

<body class="toggle-sidebar">

    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="./" class="logo d-flex align-items-center">
                <img src="view/img/logo.jpeg" alt="">
                <span class="d-none d-lg-block">DaniKat Shop</span>
            </a>
        </div>


        <div class="search-bar">
            <form class="search-form d-flex align-items-center" method="POST" action="#">
                <input type="text" name="query" placeholder="Buscar tortas, arreglos, manualidades..." title="Enter search keyword">
                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <?php
        // $id_usuario = $_SESSION['id_usuario'];

        // $precio_dolar_actual = modeloPrincipal::obtener_precio_dolar();

        // $_SESSION['dolar'] = $precio_dolar_actual;

        // $tiempo_config = modeloPrincipal::obtener_tiempo_inactividad();

        // echo '<script type="text/javascript"> const tiempo_config = '.$tiempo_config.' * 60 * 1000</script>';

        ?>

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item dropdown">

                    <button class="btn bg-secondary-light nav-icon fst-italic fs-6" data-bs-toggle="dropdown">
                        <i class="bi bi-currency-exchange"></i>
                        &nbsp; Tasa USD: <span id="tasa_dolar">623.56<?php // modeloPrincipal::number_format_prices((float)$precio_dolar_actual) 
                                                                        ?></span>Bs
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                        <li class="dropdown-header row justify-content-center">
                            <h6 class="text-center mb-3">Opciones de Actualización</h6>
                            <div class=" col-12 mb-2">
                                <button id="btn_update_dolar_auto" class="w-100 btn btn-success text-center">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <span class="p-2 ms-2">Sincronizar Tasa (Automático)</span>
                                </button>
                            </div>
                            <div class=" col-12 mb-2">
                                <button class="btn btn-warning text-center w-100" data-bs-toggle="modal" data-bs-target="#dolarUpdate" id="btnUpdate">
                                    <i class="bi bi-pencil-square"></i>
                                    <span class="p-2 ms-2">Establecer Tasa (Manual)</span>
                                </button>
                            </div>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown pe-3">

                    <button class="nav-link nav-profile d-flex align-items-center pe-0">
                        <span class="d-none d-md-block bi bi-person-circle">&nbsp;USER
                            <?php // $_SESSION['dataUsuario']['nombre']." ".$_SESSION['dataUsuario']['apellido']; ?>
                        </span>
                    </button>
                </li>
            </ul>
        </nav>
    </header>
    <div class="msjFormSend"></div>



    <main id="main" class="main">
        <div class="pagetitle">
            <h1 class="text-center fs-1 titulosH my-2">Todo lo que buscas en un solo lugar</h1>
        </div>
        <section class="section dashboard">
            <div class="row gap-1 justify-content-around align-items-center">

                <div class="col-12 col-md-3 fs-4 rounded-4 card" data-bs-theme="drk">
                    <div data-categories="" class="product-card product_${id} overflow-hidden">
                        <div class="position-relative overflow-hidden " style="height: 15rem;">
                            <img src="view/img/products/lonchera_grande.jpg" class="w-100 h-100" alt="Imagen del producto">
                            <div class="position-absolute bottom-0 d-flex flex-wrap gap-2 align-items-center">
                                <div class="badge bg-black border border-white bottom-4 left-4 px-4 py-1 position-relative rounded-5">
                                    <span class="small fw-bold"><?= 2 >= 1.00 ? "$ 34" : 'Bajo pedido' ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="border-top border-2">
                            <div class="text-start">
                                <button onclick="detallesProductoById(${id})" class="btn btn-white mb-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#exampleModal">lonchera_grande</button>
                            </div>
                            <div class="row justify-content-center align-items-center">
                                <div class="w-auto mb-3 text-center">
                                    <button onclick="detallesProductoById(${id})" type="button" class="btn_details w-full p-2 rounded-4 btn btn-secondary gap-2 d-flex items-center justify-center " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <i class="bi bi-eye text-lg"></i> <span class="d-none d-md-block text-sm font-bold">Ver Detalles</span>
                                    </button> 
                                </div>
                                <div class="w-auto mb-2 text-center">
                                    <button onclick="askWhatsApp('${nombre}', ${precio}, ${PHONE})" 
                                        type="submit" class="w-full btn btn-success  p-2 d-flex items-center justify-center gap-2 rounded-5">
                                            <i class="bi bi-whatsapp text-lg"></i> <span class="d-none d-md-block text-sm font-bold">Consultar por WhatsApp</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  

                <div class="text-center col-12 col-md-3 fs-4 rounded-2 card">
                    <h3 class="text-center mt-2 titulosH fs-4 fw-bold ">Categorías</h3>


                    <div class="text-center mb-2">
                        <button modal="registrarCategoria" type="button" data-bs-toggle="modal" data-bs-target="#modal" class="mb-2 btn_modal btn btn-success">
                            <i class="bi bi-plus-circle"></i> Registrar nueva
                        </button>
                    </div>

                    <div class="text-center mb-2">
                        <button modal="listaCategoria" id="btn_ver_listas_categoria" type="button" class="btn_modal btn btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal">
                            <i class="bi bi-list-columns-reverse"></i> Ver Lista
                        </button>
                    </div>

                </div>  

                <div class="text-center col-12 col-md-3 fs-4 rounded-2 card">
                    <h3 class="text-center mt-2 titulosH fs-4 fw-bold ">Categorías</h3>


                    <div class="text-center mb-2">
                        <button modal="registrarCategoria" type="button" data-bs-toggle="modal" data-bs-target="#modal" class="mb-2 btn_modal btn btn-success">
                            <i class="bi bi-plus-circle"></i> Registrar nueva
                        </button>
                    </div>

                    <div class="text-center mb-2">
                        <button modal="listaCategoria" id="btn_ver_listas_categoria" type="button" class="btn_modal btn btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal">
                            <i class="bi bi-list-columns-reverse"></i> Ver Lista
                        </button>
                    </div>

                </div>  

                <div class="text-center col-12 col-md-3 fs-4 rounded-2 card">
                    <h3 class="text-center mt-2 titulosH fs-4 fw-bold ">Categorías</h3>


                    <div class="text-center mb-2">
                        <button modal="registrarCategoria" type="button" data-bs-toggle="modal" data-bs-target="#modal" class="mb-2 btn_modal btn btn-success">
                            <i class="bi bi-plus-circle"></i> Registrar nueva
                        </button>
                    </div>

                    <div class="text-center mb-2">
                        <button modal="listaCategoria" id="btn_ver_listas_categoria" type="button" class="btn_modal btn btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal">
                            <i class="bi bi-list-columns-reverse"></i> Ver Lista
                        </button>
                    </div>

                </div>  

                <div class="text-center col-12 col-md-3 fs-4 rounded-2 card">
                    <h3 class="text-center mt-2 titulosH fs-4 fw-bold ">Categorías</h3>


                    <div class="text-center mb-2">
                        <button modal="registrarCategoria" type="button" data-bs-toggle="modal" data-bs-target="#modal" class="mb-2 btn_modal btn btn-success">
                            <i class="bi bi-plus-circle"></i> Registrar nueva
                        </button>
                    </div>

                    <div class="text-center mb-2">
                        <button modal="listaCategoria" id="btn_ver_listas_categoria" type="button" class="btn_modal btn btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal">
                            <i class="bi bi-list-columns-reverse"></i> Ver Lista
                        </button>
                    </div>

                </div>   
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