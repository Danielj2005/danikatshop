
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
                'carrera', 
                'autores', 
                'libros', 
                'solicitantes', 
                'prestamo', 
                'usuarios'
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