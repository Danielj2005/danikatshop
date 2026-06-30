<?php
// session_start();

// // importacion de la conexion a la base de datos y al modelo principal
// include_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

// $id_usuario = $_SESSION['id_usuario']; // se obtiene el id del usuario

// // validación para verificar que el usuario inicio sesion de manera correcta
// model_user::verificar_intento_de_acceso_al_sistema();

// include_once "../include/verificacion_primer_inicio_usuario.php"; // se incluyen los modelos necesarios para la vista

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- titulo -->
    <title>Panel de Control</title>
    <!-- metadatos -->
    <?php
    include_once "./inc/meta_include.php";
    // estilos y librerias css
    include_once "./inc/css_include.php";
    ?>
</head>

<body class=" ">
    <?php
    include_once "./inc/header.php"; 
    include_once "./inc/adminSideBar.php";

    //   $total_ventas_del_dia = venta_model::totales_ventas_del_dia();

    //   $total_hoy_dolar = $total_ventas_del_dia['dolares'];
    //   $total_hoy_bs = $total_ventas_del_dia['bs'];
    ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1> Panel de Control </h1>
        </div>

        <section class="dashboard">
            <div class="row">

                <div class="col-12 mb-1">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total de las Ventas del Día</h5>

                            <div class="row">
                                <div class="col-12 col-md-6 mb-1">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text" id="basic-addon1">Total ($)</span>
                                        <!-- <input type="text" class="form-control" disabled id="TotalUSD" readOnly value="
                                         <?php //($total_hoy_dolar == "") ? 0 : modeloPrincipal::number_format_prices($total_hoy_dolar) ?>"> -->
                                        <input type="text" class="form-control" disabled id="TotalUSD" readOnly value="($total_hoy_dolar) ?>">
                                        <span class="input-group-text" id="basic-addon1">$</span>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 mb-1">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text" id="basic-addon1">Total (Bs)</span>
                                        <!-- <input type="text" class="form-control" disabled id="TotalBS" readOnly value="
                                         <?php //($total_hoy_bs == "") ? '0' : modeloPrincipal::number_format_prices($total_hoy_bs) ?>"> -->
                                        <input type="text" class="form-control" disabled id="TotalBS" readOnly value="$total_hoy_bs) ?>">
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
                                            <tbody> <?php //venta_model::lista_ventas_diarias(); 
                                                    ?> </tbody>
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
    
    <?php
    //   include_once "./modal/plantillaModalCustom.php";

    include_once "./inc/footer.php";
    include_once "./inc/scripts_include.php";

    // model_user::validar_sesion_activa($id_usuario);

    //   config_model::verificar_actualizacion_configuracion(); 
    ?>
</body>

</html>