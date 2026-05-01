<?php 
session_start();

$_SESSION["id_producto"] = $_POST['id'] ?? null;

?>

<!DOCTYPE html>
<html lang="es" class="dark">
<?php 

include_once "./config/SERVER.php";

$id = $_POST['id'] ?? null;

if ($id == null || empty($_SESSION["id_producto"])) {
    
    session_unset(); // remueve o elimina las variables de sesion
    session_destroy(); // Destruye la sesión actual
    header("location: ./index.php");
}
?>

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta content="E-comerce catálogo de productos" name="description">
<meta content="E-comerce, catálogo de productos, ventas, pedidos, whatsapp" name="keywords">
<meta content="Daniel Barrueta" name="author">

<title>DanikatShop - Todo lo que buscas en un solo lugar </title>


<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    slate: { 950: '#020617', },
                    purple: { 400: '#c084fc', 500: '#a855f7', 600: '#9333ea', },
                    fuchsia: { 500: '#d946ef', 600: '#c026d3', 700: '#a21caf', },
                },
                fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], },
            }
        }
    }
</script>

<!-- Favicons -->
<link href="./view/img/logo.jpeg" rel="shortcut icon" type="image/x-icon">

<!-- Custom fonts for this template-->
<link href="./view/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

<link href="./view/css/app.css" rel="stylesheet">
<link href="./view/css/bootstrap.min.css" rel="stylesheet">
<link href="./view/css/bootstrap-icons.css" rel="stylesheet">
<link href="./view/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="./view/css/sweetalert2.min.css" rel="stylesheet">
<link href="./view/css/toastify.css" rel="stylesheet">
</head>

<body id="" class="font-sans antialiased brand-bg">
	<nav class="sticky top-0 z-40 bg-slate-950 border-b border-purple-900/20 p-4">
        <div class="max-w-7xl mx-auto d-flex flex-col flex-md-row gap-4 justify-content-between align-items-center">
            <a href="./" class=" text-center md:text-left">
                <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-fuchsia-500 bg-clip-text text-transparent">DanikatShop</h1>
                <p class="text-[10px] text-slate-500 uppercase tracking-widest">Todo lo que buscas en un solo lugar</p>
            </a>

            <div class="relative w-full md:w-1/2">
                <input type="text" placeholder="Buscar tortas, arreglos, manualidades..." 
                    oninput="handleSearch()" value=""
                    class="w-full bg-slate-900 border border-slate-700 rounded-full px-5 py-2 text-sm focus:ring-2 ring-purple-500 outline-none">
                <i class="fas fa-search absolute right-4 top-2.5 text-slate-500"></i>
            </div>

            <div class="flex gap-4 items-center">
                <a href="./index.php" class="text-slate-400 hover:text-purple-500 transition"><i class="fs-2 bi bi-house-fill me-3"></i>Volver al Catálogo</a> 
            </div>
        </div>
    </nav> 

    <div id="app" class=" min-h-screen">
    
        <div class="ms-5 p-3">
            <a href="./index.php" class="text-slate-400 hover:text-purple-500 transition"><i class="fs-2 bi bi-house-fill me-3"></i>Volver al Catálogo</a> 
        </div>
        <main class="max-w-7xl mx-auto p-6">
            <?php

            // $id = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '=') + 1) ?? null;
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

            <div class="p-6">

                <div class="group bg-slate-900/40 border border-slate-800 rounded-[2rem] overflow-hidden hover:border-purple-500/50 transition-all duration-500 animate-slide-up">
                    
                    <div id="carouselExampleIndicators" class="carousel slide carousel-dark">
                        <div class="carousel-inner">

                            <?php 
                                $active = 'active';
                                foreach ($files as $file) {
                                    echo '<div class="carousel-item '.$active.' ">';
                                    echo '<img src="'.$file.'" style="width:20rem; height: 20rem; " class="" alt="...">';
                                    echo '</div>';
                                    $active = '';
                                }
                            ?>

                        </div>
                        <?php if (count($files) > 1): ?>
                            <button class="text-purple-900 carousel-control-prev carousel-dark" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-primary p-4 rounded-2xl" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next carousel-dark" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                <span class="bg-primary carousel-control-next-icon p-4 rounded-2xl" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        <?php endif;  ?>

                    </div>

                    <div class="p-6 row align-items-center justify-content-center text-start">
                        <h3 class="col-12 text-white font-semibold fs-3 mb-2 truncate"><?= ucwords(strtolower($quety['nombre'])) ?></h3>
                        
                        <p class="col-12 fs-4 font-bold text-slate-400 mb-4 "><?= $quety['description'] ?></p>
                        <span class="col-12 fs-4 font-bold text-emerald-400 mb-4 "><?= $quety['precio'] < 1.00 ? 'Bajo pedido' : "$". $quety['precio'] ?></span>
                        <div class="col-12 mb-3 text-center d-flex justify-content-center">
                            <button onclick="askWhatsApp('<?= ucwords(strtolower($quety['nombre'])) ?>', <?= $quety['precio'] ?>, <?= $phone['telefono'] ?>)" type="submit" class=" bg-emerald-800 hover:bg-purple-600 text-white py-3 rounded-3xl transition-all flex items-center justify-center p-3">
                                <i class="fab fa-whatsapp text-lg fs-3 me-2"></i>Saber más
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>




    <script src="view/js/bootstrap.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="view/js/sweetalert2.min.js"></script>
    <script src="view/js/app.js"></script>
</body>

</html>