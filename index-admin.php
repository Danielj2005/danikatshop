<?php
session_start();

// importacion de la conexion a la base de datos y al modelo de usuario
require_once "config/SERVER.php";
require_once "model/mainModel.php"; // se incluye el model principal
require_once "model/productModel.php";


$page = isset($_POST['page']) ? max(1, intval($_POST['page'])) : 1;
$per_page = isset($_POST['per_page']) ? max(1, intval($_POST['per_page'])) : 16;

$offset = ($page - 1) * $per_page;

$catalogo = mysqli_fetch_all(modeloPrincipal::consultar("SELECT id, nombre, precio, images FROM productos WHERE state = 1 ORDER BY nombre ASC LIMIT $per_page OFFSET $offset")); 
$PHONE = "04244189963";

$filters = $data['filter'] ?? null;

if ($filters !== null) {
    $addQuery = "WHERE C.nombre IN ('" . implode("','", $filters) . "') GROUP BY P.id";
    // obtener total filtrado
    $countQuery = "SELECT COUNT(DISTINCT P.id) AS total FROM productos AS P INNER JOIN categorias_productos AS CP ON P.id = CP.producto_id INNER JOIN categorias AS C ON C.id = CP.categoria_id WHERE C.nombre IN ('" . implode("','", $filters) . "')";
    $total_stmt = modeloPrincipal::consultar($countQuery);
    $total_row = mysqli_fetch_assoc($total_stmt);
    $total = intval($total_row['total']);
    $query = "SELECT P.id, P.nombre, P.precio, P.images FROM productos AS P INNER JOIN categorias_productos AS CP ON P.id = CP.producto_id INNER JOIN categorias AS C ON C.id = CP.categoria_id $addQuery ORDER BY P.nombre ASC LIMIT $per_page OFFSET $offset";
} else {
    $total_stmt = modeloPrincipal::consultar("SELECT COUNT(*) AS total FROM productos WHERE state = 1");
    $total_row = mysqli_fetch_assoc($total_stmt);
    $total = intval($total_row['total']);
    $query = "SELECT P.id, P.nombre, P.precio, P.images FROM productos AS P WHERE P.state = 1 ORDER BY P.nombre ASC LIMIT $per_page OFFSET $offset";
}

$total_pages = ceil($total / $per_page);

$totalPages = max(1, ceil($total / $per_page));

$maxButtons = 7;
$start = max(1, $page - floor($maxButtons / 2));
$end = min($totalPages, $start + $maxButtons - 1);
if ($end - $start < $maxButtons - 1) {
    $start = max(1, $end - $maxButtons + 1);
}

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
    <link href="./view/css/carousel.css" rel="stylesheet">

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
                                    <div class="position-relative overflow-hidden mb-3" style="height: 15rem;">
                                        <img src="<?= $images ?>" class="w-100 h-100 rounded-bottom-0 rounded-4" alt="Imagen del producto">
                                        <div class="badge_precio_container">
                                            <div class="bg_badge_precio badge border border-white position-relative rounded-5">
                                                <span class="precio_card"><?= $precio >= 1.00 ? "$ ".$precio : 'Bajo pedido' ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-start">
                                        <a onclick="detallesProductoById(<?= $id ?>)" class="fs-6 text-muted mb-4 fw-semibold" data-bs-toggle="modal" data-bs-target="#exampleModal"><?= ucwords(strtolower($nombre)); ?></a>
                                    </div>
                                    <div class="">
                                        <div class="row justify-content-around align-items-cente">
                                            <div class="col-6 mb-2 text-center">
                                                <button onclick="detallesProductoById(<?= $id ?>)" type="button" class="btn_details rounded-5 btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    <i class="bi bi-eye"></i>
                                                    <span class="small">Ver Detalles</span>
                                                </button> 
                                            </div>
                                            <div class="col-6 mb-2 text-center">
                                                <button onclick="askWhatsApp('<?= $nombre ?>', <?= $precio ?>, <?= $PHONE ?>)" 
                                                    type="submit" class="btn btn-success rounded-5">
                                                        <i class="bi bi-whatsapp"></i>
                                                        <span class="small d-non">WhatsApp</span>
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

            <div id="catalog-pagination" class="w-100 d-flex justify-content-center align-items-center gap-2 my-6">
                
                <form id="" method="POST" action="index-admin.php" class="SendFormAjax text-start">
                    
                    <input id="" name="page" type="hidden" value="<?= $page - 1 ?>">
                    <button type="submit" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i>
                        <span class="small">Anterior</span>
                    </button>   
                </form>
                <?php for ($p = $start; $p <= $end; $p++) { ?>

                    <button type="button" class="<?= $p === $page ? 'btn btn-primary' : 'btn btn-outline-primary' ?>">
                        <span class="small"><?= $p ?></span>
                    </button>  

                <?php } ?>

                <form id="" method="POST" action="index-admin.php" class="SendFormAjax text-start">
            
                    <input id="" name="page" type="hidden" value="<?= $page + 1 ?>">
                    <button <?= $page >= $totalPages ? 'disabled' : '' ?> type="submit" class="btn btn-primary">
                        <span class="small">Siguiente</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>  
                </form>
            </div>
        </section>

    </main>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content bg-slate-900 rounded-3xl border border-slate-800 shadow-2xl">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detalles de producto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="gap-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 modal-body" id="modalBody">

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