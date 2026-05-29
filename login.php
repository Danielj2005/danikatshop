<?php 
session_start();

$_SESSION["intentos_sesion"] = 1;

include_once "./model/mainModel.php"; // se incluye el model principal

?>
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
</head>

<body id="" class="font-sans antialiased brand-bg" >
	<nav class="top-0 z-40 bg-slate-950 border-b border-purple-900/20 p-4">
        <div class="max-w-7xl mx-auto d-flex flex-col flex-md-row gap-4 justify-content-between align-items-center">
            <a href="./" class=" text-center md:text-left">
                <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-fuchsia-500 bg-clip-text text-transparent">DanikatShop</h1>
                <p class="text-[10px] text-slate-500 uppercase tracking-widest">Todo lo que buscas en un solo lugar</p>
            </a>

            <div class="flex gap-4 items-center">
                <a href="./index.php" class="d-flex align-items-center text-slate-400 hover:text-purple-500 transition">
                    <i class="fs-md-2 bi bi-house-fill me-3"></i>Volver al Catálogo
                </a> 
            </div>
        </div>
    </nav>

    <div id="app" class=" min-h-screen">
    
        <main class="max-w-7xl mx-auto p-6">
            
            <div class="row align-items-center justify-content-center p-4">
                <form id="login" method="POST" action="./controller/login.php" data-type-form="load" autocomplete="off" class="SendFormAjax text-start bg-[#020617] border border-slate-800 p-3 rounded-5 col-12 col-md-4 shadow-2xl">
                    
                    <div class="text-center mb-4">
                        <h2 class="text-2xl font-bold text-center my-8 bg-gradient-to-r from-purple-400 to-fuchsia-500 bg-clip-text text-transparent">Acceso Administrativo</h2>
                    </div>
        
                    
                    <div class="text-start mb-3" data-bs-theme="dark">
                        <label for="user" class="form-label fw-bold">Correo Electrónico &nbsp;<span style="color:#f00; font-size: 1.5rem;">*</span></label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text"> <i class="bi bi-person-circle"></i> </span>
                            <input id="user" name="user" type="email" placeholder="Correo" 
                                required class="form-control">
                        </div>
                    </div>

                    <div class="text-start mb-3" data-bs-theme="dark">
                        <label for="pass" class="form-label fw-bold">Contraseña &nbsp;<span style="color:#f00; font-size: 1.5rem;">*</span></label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"> <i class="bi bi-lock"></i> </span>
                            <input id="pass" name="pass" type="password" placeholder="Contraseña" required class="form-control">
                            <button id="btnEyeIcon" type="button" class="btn btn-secondary input-group-text position-" title="Mostrar contraseña" 
                                onclick="show_password('eyeIcon', 'pass')">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    
                    <div class="text-start mb-4">
                        <p class="w-full text-slate-200 mt-4 text-sm">Los campos con  <span style="color:#f00; font-size: 1rem;">*</span> son obligatorios.</p>
                    </div>

                    <button class="mb-4 w-full bg-purple-600 p-2 rounded-2xl font-bold hover:bg-purple-900 transition shadow-lg shadow-purple-500/20">Entrar</button>
                    
                    <div class="text-center mb-4">
                        <a href="./" class="btn btn-outline-secondary w-full text-slate-200 mt-4 text-sm">Volver al catálogo</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- modal recuperar contraseña -->
    <div class="modal fade p-5" id="recuperar_contraseña" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="SendFormAjax" method="post" action="./api/recuperar_contraseña">
                    <div class="modal-header">
                        <h1 class="modal-title fs-3 text-white" id="exampleModalLabel"><i class="text-white bi bi-key"></i>&nbsp; Recuperar Contraseña</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-start">
                            <label class="mb-3 text-white text-start" for="selecciona_metodo_de_recuperacion">Selecciona el Método de Recuperación<span style="color:#f00;">*</span></label>
                            <select required name="selecciona_metodo_de_recuperacion" id="selecciona_metodo_de_recuperacion" class="form-select">
                                <option disabled>Selecciona una opción</option>
                                <option value="correo">Recibir un Código por Correo </option>
                                <option value="preguntas">Responder las Preguntas de Seguridad</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="aceptar">Aceptar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="msjFormSend"></div>

    <script type="text/javascript" src="view/js/jquery-3.6.0.min.js"></script>
    <script src="view/js/bootstrap.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="view/js/sweetalert2.min.js"></script>
    <script src="view/js/DanikatAlert.js"></script>
    <script src="view/js/hiddenInput.js"></script>
    <script src="view/js/SendForm.js"></script>
    <script> SendFormAjax(); </script>
    <script src="view/js/validator.js"></script>
</body>

</html>