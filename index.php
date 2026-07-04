<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="E-comerce catálogo de productos" name="description">
    <meta content="E-comerce, catálogo de productos, ventas, pedidos, whatsapp" name="keywords">
    <meta content="Daniel Barrueta" name="author">

    <title>DanikatShop - Todo lo que buscas en un solo lugar </title>


    <script src="https://cdn.tailwindcss.com"></script>
    
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
            <a href="./" class="d-md-block d-none mb-3 text-center md:text-left sm:hidden">
                <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-fuchsia-500 bg-clip-text text-transparent">DanikatShop</h1>
                <p class="text-[10px] text-slate-500 uppercase tracking-widest">Todo lo que buscas en un solo lugar</p>
            </a>

            <div class="align-items-center d-flex gap-3 w-100">
                <div class="mb-3 position-relative d-flex align-items-center w-full mx-auto">
                    <input type="text" placeholder="Buscar tortas, arreglos..." 
                        oninput="handleSearch(this.value)"
                        class="w-full bg-slate-900 border border-slate-700 rounded-full px-4 py-2 text-sm focus:ring-2 ring-purple-500 outline-none">
                    <i class="bi bi-search absolute right-4 top-2 text-slate-500"></i>
                </div>

                <div class="mb-3 flex gap-4 items-center">
                    <a href="login.php" class="text-slate-700 hover:text-purple-500 transition"><i class="fs-3 bi bi-person-circle"></i></a> 
                </div>
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
            <h2 class="md:text-2xl sm:text-xl font-bold italic text-white mb-2">Todo lo que buscas en un solo lugar</h2>
            
            <!-- Filtros por Categoría -->
            <div class="dropdown text-center" data-bs-theme="dark">
                <button class="btn btn-primary dropdown-toggle position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-sliders" ></i>
                    <span class="" > Filtros  </span>
                    <span id="num_filter" class="d-none badge position-absolute text-bg-danger" style="top: -.8rem;  right: -1rem;"></span>
                </button>
                
                <ul id="category-filters" class="dropdown-menu">
                    <li id="dropdown-item-all" class=" dropdown-item transition-all hover:bg-purple-500/20" >
                        <button onclick="filterByCategory('all', 0)" class="category-btn">Todos</button>
                    </li>
                </ul>
            </div>
        </header>
        <main class="max-w-7xl mx-auto p-3" id="main">
            <div id="cards" class="grid gap-3 justify-around grid-cols-2 sm:grid-cols-2 md:grid-cols-4"></div>
        </main>
    </div>
    
    <!-- Modal -->
    <div data-bs-theme="dark" class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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


    <!-- Custom scripts for all pages-->
    <script src="view/js/bootstrap.bundle.min.js"></script>
    <script src="view/js/sweetalert2.min.js"></script>
    <script src="view/js/DanikatAlert.js"></script>
    <script src="view/js/renderCatalogo.js"></script>
    <script src="view/js/catalogo.js"></script>
    <script src="view/js/index.js"></script>
</body>

</html>