
<!-- Content Row -->
<div class="d-none row justify-content-around">
    <div class="col-12 mb-3">
        <div class=" shadow h-100 py-2">
            <div class="row align-items-center mb-5 justify-content-center text-center">
                <div id="carouselExampleIicators" class="carousel slide col-12 col-md-3 carousel-dark">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="<?= SERVERURL; ?>view/img/Products/monedero.jpg" style="max-height: 15rem; max-width: 15rem;" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="<?= SERVERURL; ?>view/img/Products/monedero2.jpg" style="max-height: 15rem; max-width: 15rem;" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="<?= SERVERURL; ?>view/img/Products/monedero3.jpg" style="max-height: 15rem; max-width: 15rem;" class="d-block w-100" alt="...">
                        </div>
                    </div>
                    <button class="carousel-control-prev carousel-dark" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next carousel-dark" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                
                <div class="col-12 text-center col-md-6">
                    <h6 class="text-gray-800">Monedero</h6>
                    <p class="text-gray-600">Cantidad Disponible: <span class="badge text-bg-warning fw-bold fst-italic">Solo por encargo!</span></p>
                    <p class="text-gray-600">Precio: <span class="badge text-bg-success fw-bold fst-italic">$10</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$id = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '=') + 1) ?? null;


// Ruta de la carpeta de imágenes
$directory = "storage/$id";
// Busca todos los archivos que terminen en .jpg, .png o .webp
// Devuelve un array con las rutas completas: ["storage/foto1.jpg", "storage/torta.png"]
$files = glob("$directory/*.{jpg,png,jpeg,webp,jfif}", GLOB_BRACE);


$query = modeloPrincipal::consultar("SELECT BIN_TO_UUID(id) AS id, name, price, images FROM productos WHERE id = UUID_TO_BIN('$id')"); 
$quety = mysqli_fetch_assoc($query);


?>

<div class="p-6">

    <div class="group bg-slate-900/40 border border-slate-800 rounded-[2rem] overflow-hidden hover:border-purple-500/50 transition-all duration-500 animate-slide-up">
        
        <div id="carouselExampleIndicators" class="carousel slide carousel-dark">
            <div class="carousel-indicators">
                <?php 
                    $i = 0;
                    $active = 'active';
                    foreach ($files as $file) {
                        echo '<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="'.$i.'" class="'.$active.'" aria-current="'.($i == 0 ? 'true' : 'false').'" aria-label="Slide '.($i + 1).'"></button>';
                        $i++;
                        $active = '';
                    }
                ?>
            </div>

            <div class="carousel-inner">

                <?php 
                    $active = 'active';
                    foreach ($files as $file) {
                        echo '<div class="carousel-item '.$active.' text-center">';
                        echo '<img src="'.$file.'" style="max-height: 30rem; max-width: 30rem;" class="d-block w-100" alt="...">';
                        echo '</div>';
                        $active = '';
                    }
                ?>

            </div>
            
            <button class="text-purple-900 carousel-control-prev carousel-dark" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-primary p-4 rounded-2xl" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next carousel-dark" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="bg-primary carousel-control-next-icon p-4 rounded-2xl" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="p-6">
            <h3 class="text-white font-semibold text-lg mb-2 truncate"><?= $quety['name'] ?></h3>
            <span class="text-sm font-bold text-white mb-4"><?= $id == null ? 'Bajo pedido' : $quety['price'] ?></span>
            <button onclick="askWhatsApp(1)" class="w-full bg-slate-800 hover:bg-purple-600 text-white py-3 rounded-2xl transition-all flex items-center justify-center gap-2">
                <i class="fab fa-whatsapp text-lg"></i> <span class="text-sm font-bold">Consultar</span>
            </button>
        </div>
    </div>
</div>