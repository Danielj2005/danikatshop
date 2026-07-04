
<!-- Favicons -->
<link href="./img/logo.jpeg" rel="shortcut icon" type="image/x-icon">

<!-- sweet-alert 2 -->
<link href="./css/sweetalert2.min.css" rel="stylesheet">
<link href="./css/toastify.css" rel="stylesheet">

<link href="./css/select2.min.css" rel="stylesheet">

<link href="./css/bootstrap.min.css" rel="stylesheet">
<link href="./css/bootstrap-icons.css" rel="stylesheet">
<link href="./css/dataTables.bootstrap5.min.css" rel="stylesheet">

<link href="./css/animate.min.css" rel="stylesheet">
<!-- Template Main CSS File -->
<link href="./css/nice_admin_styles/styles.css" rel="stylesheet">

<style>
    .card-title {
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #012970;
        padding-bottom: 0.5rem;
    }
    .invalid {
        border: var(--bs-red) 2px solid;
    }
    .valid {
        border: var(--bs-green) 2px solid;
    }
    .glassmorph {
        background-color: rgba(0, 0, 0, 0.50);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5);
        -moz-backdrop-filter: blur(10px);
    }
    .container {
        margin-top: 4em;
        margin-bottom: 4em;
    }

    .titulosH{
        color:#012970;
        font-weight: bold;
    } 

    /* CSS personalizado para el separador */
    .dotted-separator {
        border: none;
        border-top: 1px dotted #000;
        margin: 8px 0; /* Espaciado vertical para recibo */
    }

    /* CSS para asegurar que el formato de texto sea pequeño y apto para recibos */
    .small {
        font-size: 0.8rem;
    }
</style>


<?php 
// se obtiene la configuracion de la base de datos
$configuracion = [
    'iva' => 20 /*config_model::obtener_dato('porcentaje_iva')*/,
    'ganancia' => 20 /*config_model::obtener_dato('porcentaje_ganancia')*/
];
?>
<!-- se obtiene el porcentaje del iva y de la ganancia para los productos -->
<script type="text/javascript">
    const IVA = <?= $configuracion['iva'] ?> ;
    const PORCENTAJE_GANANCIA = <?= $configuracion['ganancia'] ?>;
    // url de la api del router
    const URL_API = "./inc/api.php";
</script>