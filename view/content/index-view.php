<div class="d-none" id="wrapper">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <?php include_once 'view/inc/navBarindex.php'; ?>

            <div class="container-fluid">
                
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Bienvenid@ a DaniKat Shop!</h1>
                </div>
                
                <!-- Content Row -->
                <div class="row justify-content-around">
                
                    <?php 
                    
                        $cardContent = [
                            'cardTitle' => [
                                'Monedero', 
                                'Cartera con llavero', 
                                'Lonchera Grande', 
                                'Bandolero de corazón', 
                                'Lonchera térmica unicolor', 
                                'Monedero pequeño'
                            ],
                            'cantidad' => [
                                10, 
                                28, 
                                18, 
                                2, 
                                11, 
                                2
                            ],
                            'url' => [
                                'producto', 
                                'producto', 
                                'producto', 
                                'producto', 
                                'producto', 
                                'producto'
                            ],
                            'img' => [
                                'monedero.jpg', 
                                'cartera_con_llavero.jpg', 
                                'lonchera_grande.jpg', 
                                'bandolero_corazon.jpg', 
                                'lonchera_termica.jpg', 
                                'monedero_peq.jpg'
                            ],
                        ];
                    
                        
                        for ($i = 0; $i < 6; $i++) { 
                            ?>
                            <div class="col-md-3 mb-3 col-xl-2 col-6 col-sm-4">
                                <div class="card shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                
                                                <div class="text-center mb-3">
                                                    <img src="<?= SERVERURL; ?>view/img/products/<?= $cardContent['img'][$i]; ?>" alt="" class="img-fluid" style="max-height: 150px;">
                                                </div>
                
                                                <div class="text-xs font-weight-bold text-capitalize mb-1">
                                                    <a class="nav-link" href="<?= $cardContent['url'][$i]; ?>">
                                                        <?php echo $cardContent['cardTitle'][$i]; ?>
                                                    </a>
                                                </div>
                
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <?php echo "US$".$cardContent['cantidad'][$i]; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    ?>
                </div>
                
                <!-- modal recuperar contraseña -->
                <div class="modal fade p-5" id="recuperar_contraseña" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post" action="./api/recuperar_contraseña">
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
                
            </div>
        </div>
        
        <?php include_once "view/inc/footer.php"; ?>
    </div>
</div>

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
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php 

                $query = modeloPrincipal::consultar("SELECT BIN_TO_UUID(id) AS id, name, price, images FROM productos"); 
                
                $i = 0;
                while ($mostrar = mysqli_fetch_array($query)) { 
                    // $idSecure = modeloPrincipal::encryptionId($mostrar["id"]);
                    $id = $mostrar["id"];
                    
                    $imgSrc = explode(',', $mostrar['images']);
                    $url = $mostrar["id"].'/'.$imgSrc[0];

                    ?>

                    <div class="group bg-slate-900/40 border border-slate-800 rounded-[2rem] overflow-hidden hover:border-purple-500/50 transition-all duration-500 animate-slide-up">
                        <div class="relative h-64 overflow-hidden cursor-pointer" onclick="openModal(<?= $mostrar['id'] ?>)">
                            <img src="storage/<?= $url ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute bottom-4 left-4 bg-black/60 backdrop-blur-md px-4 py-1 rounded-full border border-white/10">
                                <span class="text-sm font-bold text-white"><?= $mostrar['price'] ? '$' . $mostrar['price'] : 'Bajo pedido' ?></span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-white text-md font-semibold mb-1 truncate mb-4"><?= $mostrar['name'] ?></h3>
                            
                            <form action="./producto" method="get" data-type-form="load">
                                <input type="hidden" value="<?= $mostrar['id'] ?>" name="id" />
                                <button type="submit" class="w-full bg-slate-800 hover:bg-purple-600 text-white py-3 rounded-2xl transition-all flex items-center justify-center gap-2">
                                    <i class="fab fa-whatsapp text-lg"></i> <span class="text-sm font-bold">Consultar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php }
            ?>
        </div>
    </main>
</div>

