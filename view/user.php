<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- sweet-alert 2 -->
<link href="./css/sweetalert2.min.css" rel="stylesheet">
<link href="./css/toastify.css" rel="stylesheet">

<!-- estilos custom -->
<link href="./css/main.css" rel="stylesheet">

<link rel="stylesheet" href="./css/select2.min.css">

<link href="./css/bootstrap.min.css" rel="stylesheet">
<link href="./css/bootstrap-icons.css" rel="stylesheet">
<link href="./css/dataTables.bootstrap5.min.css" rel="stylesheet">

<link href="./css/animate.min.css" rel="stylesheet">
<!-- Template Main CSS File -->
<link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="./" class="logo d-flex align-items-center">
        <img src="img/favicon.ico" alt="">
        <span class="d-none d-lg-block">POLLERA LA CHINITA</span>
        </a>
        <?php //if ($_SESSION['dataUsuario']["primer_inicio"] == '0') { ?>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        <?php //} ?>

    </div>

    <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="POST" action="#">
            <input type="text" name="query" placeholder="BUSCAR VENTA POR NÚMERO DE FACTURA O POR NOMBRE DEL CLIENTE" title="Enter search keyword">
            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form>
    </div> 

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

        <li class="nav-item dropdown">

            <button class="btn bg-secondary-light nav-icon fst-italic fs-6" data-bs-toggle="dropdown">
            <i class="bi bi-currency-exchange"></i>
            &nbsp; Tasa USD: <span id="tasa_dolar"></span>Bs
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

            <button class="nav-link nav-profile d-flex align-items-center pe-0" data-bs-toggle="dropdown">
            <span class="d-none d-md-block dropdown-toggle ps-2">'nombre' 'apellido'</span>
            </button>

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
                <h6>nombre apellido</h6>
                <span>'nombreRolUsuario</span>
            </li>

            <li> <hr class="dropdown-divider"> </li>

            <li>
                <a class="dropdown-item d-flex align-items-center" href="./mi_perfil.php">
                <i class="bi bi-person"></i>
                <span>Mi Pefil</span>
                </a>
            </li>

            <li> <hr class="dropdown-divider"> </li>

            <?php 
                /*/ Lista de todos los permisos que pertenecen al Módulo de Configuración/Ajustes

                $permiso_ajustes = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['ajustes']);

                // se evalua que este rol tenga el acceso a esta vista

                if ($permiso_ajustes) { */?>
                
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="./configuracion.php">
                        <i class="bi bi-gear-fill"></i>
                        <span>Configuración</span>
                    </a>
                </li>

            <?php //} ?>

            <li> <hr class="dropdown-divider"> </li>

            <li>
                <a class="dropdown-item d-flex align-items-center btn-exit-system" href="#!">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </li>

            </ul>
        </li>
        </ul>
    </nav>
    </header>

    <div class="msjFormSend"></div>


    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            <!-- apartado de página principal -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="./"> <i class="bi bi-speedometer2"></i> <span>Panel de Control</span> </a>
            </li>

            <?php //if ($permiso_productos || $permiso_proveedor) {  ?>

                <li class="nav-item">

                    <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-box-seam-fill"></i>
                        <span>Inventario</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>

                    <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">

                        <?php //if ($permiso_productos) : ?>

                            <li>
                                <a href="./gestion_productos.php">
                                    <i class="bi bi-circle"></i>
                                    <span>Gestión de Productos</span>
                                </a>
                            </li>

                        <?php //endif;
                        //if ($permiso_entrada_productos): ?>

                            <li>
                                <a href="./entrada_de_productos.php">
                                    <i class="bi bi-circle"></i>
                                    <span>Registro de Compras</span>
                                </a>
                            </li>

                        <?php // endif;
                       // if ($permiso_proveedor) : ?>

                            <li>
                                <a href="./proveedor.php">
                                    <i class="bi bi-circle"></i>
                                    <span>Gestión de Proveedores</span>
                                </a>
                            </li>

                        <?php //endif; ?>
                    </ul>
                </li>

            <?php //}
            //if ($permiso_servicio) { ?>

                <li class="nav-item">
                    <a href="gestion_servicios.php" class="nav-link collapsed">
                        <i class="bi bi-person-workspace"></i>
                        <span> Gestión de Servicios</span>
                    </a>
                </li>

            <?php //}
            //if ($permiso_modulo_venta) { ?>

                <li class="nav-item">

                    <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-currency-dollar"></i>
                        <span>Ventas</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>

                    <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">

                        <?php //if (array_key_exists("g_venta", $_SESSION['permisosRol'])) {  ?>

                            <li>
                                <a href="./generar_venta.php">
                                    <i class="bi bi-circle"></i>
                                    <span>Generar venta</span>
                                </a>
                            </li>

                        <?php // }
                        //if (array_key_exists("l_venta", $_SESSION['permisosRol']) || array_key_exists("d_venta", $_SESSION['permisosRol']) || array_key_exists("f_venta", $_SESSION['permisosRol'])) {  ?>

                            <li>
                                <a href="./venta.php">
                                    <i class="bi bi-circle"></i>
                                    <span>Historial de Ventas</span>
                                </a>
                            </li>

                        <?php //} ?>

                    </ul>
                </li>

            <?php //}
            //if ($permiso_modulo_cliente || $permiso_modulo_usuario || $permiso_modulo_rol) { ?>

                <li class="nav-item">

                    <a class="nav-link collapsed" data-bs-target="#user-list" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-people-fill"></i>
                        <span>Usuarios</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>

                    <ul id="user-list" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                        <?php //if ($permiso_modulo_cliente): ?>

                            <!-- modulo de clientes -->
                            <li class="nav-item">
                                <a class="nav-link collapsed" href="./cliente.php">
                                    <i class="bi bi-circle"></i>
                                    <span>Clientes</span>
                                </a>
                            </li>

                        <?php //endif;
                        //if ($permiso_modulo_usuario): ?>

                            <li class="nav-item">
                                <a class="nav-link collapsed" href="./empleados.php">
                                    <i class="bi bi-circle"></i>
                                    <span>Empleados</span>
                                </a>
                            </li>

                        <?php //endif; ?>
                    </ul>
                </li>

            <?php //} ?>

            <!-- apartado del perfil de usuario  -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="./mi_perfil.php"> <i class="bi bi-person-fill"></i> <span>Mi Perfil</span> </a>
            </li>

            <li class="nav-item"> <button class="nav-link collapsed btn-exit-system"> <i class="bi bi-box-arrow-right"></i> <span>Cerrar Sesión</span> </button> </li>
        </ul>
    </aside>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1> Panel de Control </h1>
        </div>

        <section class="section dashboard">
            <div class="row">

                <div class="col-12 mb-1">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total de las Ventas del Día</h5>

                            <div class="row">
                                <div class="col-12 col-md-6 mb-1">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text" id="basic-addon1">Total ($)</span>
                                        <input type="text" class="form-control" disabled id="TotalUSD" readOnly value="">
                                        <span class="input-group-text" id="basic-addon1">$</span>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 mb-1">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text" id="basic-addon1">Total (Bs)</span>
                                        <input type="text" class="form-control" disabled id="TotalBS" readOnly value="">
                                        <span class="input-group-text" id="basic-addon1">Bs.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="card top-selling overflow-auto mb-4">

                                <div class="card-body">
                                    <h5 class="mb-3 card-title"> Ventas Recientes </h5>

                                    <div class="table-responsive">
                                        <table class="table example table-hover table-striped mb-0" id="example">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" scope="col" style="width: 5%;">N.°</th>
                                                    <th class="text-center" scope="col">N.° de Factura</th>

                                                    <th class="text-center" scope="col">Cédula/RIF Cliente</th>

                                                    <th class="text-center" scope="col" style="width: 10%;">Total </th>

                                                    <th class="text-center" scope="col">Fecha y Hora</th>
                                                    <th class="text-center" scope="col" style="width: 8%;">Detalles</th>
                                                </tr>
                                            </thead>
                                            <tbody> </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>