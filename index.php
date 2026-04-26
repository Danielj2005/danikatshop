<!DOCTYPE html>
<html lang="es" class="dark">
<?php 

include_once "./model/mainModel.php"; // se incluye el model principal
include_once "./model/productModel.php"; // se incluye el model producto

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
<link href="./view/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

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
                <button onclick="navigate('admin')" class="d-none text-purple-400 text-sm font-bold">Gestionar</button>
                <button onclick="logout()" class="d-none text-slate-500 text-xs"><i class="fas fa-sign-out-alt"></i></button>
                <a href="login.php" class="text-slate-700 hover:text-purple-500 transition"><i class="fas fa-user-lock"></i></a> 
            </div>
        </div>
    </nav>
    

    <div id="loader" class="flex flex-col items-center justify-center min-h-screen">
        <div class="mb-4">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-fuchsia-500 bg-clip-text text-transparent">
                DanikatShop
            </h1>
        </div>
        <div class="relative">
            <div class="w-10 h-10 border-2 border-purple-500/20 border-t-purple-500 rounded-full animate-spin"></div>
        </div>
        <p class="mt-4 text-slate-500 text-sm animate-pulse italic">
            Preparando sorpresas para ti...
        </p>
    </div>

    <div id="app" style="display:none !important;" class=" min-h-screen">


        <header class="py-12 px-6 text-center animate-fade-in">
            <h2 class="text-4xl font-bold italic text-white mb-2">Todo lo que buscas en un solo lugar</h2>
        </header>
        <main class="max-w-7xl mx-auto p-6">
            <?php   producto_model::obtenerCatalogo();  ?>
        </main>
    </div>




    <script src="view/js/bootstrap.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="view/js/SendForm.js"></script>
    <script src="view/js/sweetalert2.min.js"></script>
    <script src="view/js/app.js"></script>
</body>

</html>