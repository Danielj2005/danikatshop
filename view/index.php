<?php 
session_start();

require_once "../model/mainModel.php"; // se incluye el model principal
require_once "../model/productModel.php"; // se incluye el model producto
require_once "../model/categoryModel.php"; // se incluye el model de categorias

$catalogo = modeloPrincipal::consultar("SELECT id, nombre, precio, images FROM productos"); 


if ($_SESSION['logged_in'] === true) { ?>

    <!DOCTYPE html>
    <html lang="es" class="dark">

    <head>
        <?php include_once "inc/head.php"; ?>
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
                    <a class="text-slate-700 hover:text-purple-500 transition btn-exit-system" href="#!"><i class="fs-3 bi bi-arrow-bar-left"></i>&nbsp;Salir</a>
                </div>
            </div>
        </nav>

        <div id="" class=" min-h-screen">

            <main class="max-w-7xl mx-auto p-6">
                <div class="max-w-6xl mx-auto p-6 space-y-10 animate-fade-in">
                    <div class="flex justify-between items-center border-b border-slate-800 pb-6">
                        <h2 class="text-3xl font-bold text-white">Panel de <span class="text-purple-500">Gestión</span></h2>
                        <div class="text-right">
                            <p class="text-slate-200 font-bold"><?= $_SESSION['dataUser']['nombre']; ?></p>
                            <p class="text-xs text-slate-500"><?= $_SESSION['dataUser']['rol'] == 1 ? "Dev Master" : "Administrador" ?></p>
                        </div>
                    </div>

                    <div class="row justify-content-around align-items-center p-2">

                        <div class="mb-3 text-center col-11 col-md-4 bg-slate-900 p-4 rounded-3xl border border-slate-800 shadow-2xl">
                            <h3 class="border-bottom font-bold mb-3 fs-3 text-slate-400">Categorías</h3>
    
                            <div class="text-center mb-2 row ">
                                <div class="text-center mb-3 col-12">
                                    <button 
                                            modal="registrarCategoria" 
                                            type="button" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#registrar_categoria" 
                                            class="mb-2 btn_modal btn btn-success"><i class="bi bi-plus-circle"></i>&nbsp;Registrar Nueva
                                        </button>
                                </div>
                                <div class="text-center mb-1 col-12">
                                    <button 
                                        modal="listaCategoria" 
                                        id="btn_ver_listas_categoria" 
                                        type="button" 
                                        class="btn_modal btn btn btn-secondary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalList"><i class="bi bi-list-columns-reverse"></i>&nbsp;Ver Lista
                                    </button>
                                </div>
                            </div>
                        </div> 

                        <div class="mb-3 d-none text-center col-12 col-md-5 fs-4 bg-slate-900 p-6 rounded-3xl border border-slate-800 shadow-2xl">
                            <h3 class="border-bottom font-bold mb-3 text-lg text-slate-400">Marcas</h3>
    
                            <div class="text-center mb-2 row ">
                                <div class="text-center mb-2 col-12 col-md-6">
                                    <button 
                                        modal="registrarCategoria" 
                                        type="button" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modal" 
                                        class="mb-2 btn_modal btn btn-success"><i class="bi bi-plus-circle"></i> Registrar Nueva
                                    </button>
                                </div>
                                <div class="text-center mb-2 col-12 col-md-6">
                                    <button 
                                        modal="listaCategoria" 
                                        id="btn_ver_listas_categoria" 
                                        type="button" 
                                        class="btn_modal btn btn btn-secondary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modal"><i class="bi bi-list-columns-reverse"></i> Lista de Marcas
                                    </button>
                                </div>
                            </div>
                        </div> 
                    </div>


                    <div id="" class="text-black bg-white p-6 rounded-3xl border border-slate-800 shadow-2xl justify-content-between align-items-center">
                        
                        <div class="text-start col-12 fs-4">
                            <div class="text-center mb-2 d-flex justify-content-between">
                                <h3 class="text-lg font-bold text-black mb-4 ">Productos en Línea (<?=  mysqli_num_rows($catalogo); ?>)</h3>

                                <div class="text-center mb-2">
                                    <button 
                                        modal="registrarCategoria" 
                                        type="button" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#registrar_producto" 
                                        class="mb-2 btn_modal btn btn-success"><i class="bi bi-plus-circle"></i> Registrar Nuevo
                                    </button>
                                </div>
                            </div>
                        </div> 
                    
                        <div class="table-responsive overflow-hidden overflow-x-auto">
                            
                            <table class="table example mb-3 table-striped table-hover" id="example">
                                <thead>
                                    <tr class="text-black">
                                        <th class="col text-center" scope="col">N.º</th>
                                        <th class="col text-center" scope="col">Producto</th>
                                        <th class="col text-center" scope="col">Precio ($)</th>
                                        <th class="col text-center" scope="col">Editar</th>
                                        <th class="col text-center" scope="col">Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php producto_model::lista();  ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <div class="modal fade" id="registrar_categoria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-theme="dark">
            <div id="modal_tamano" class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content bg-slate-900 p-6 rounded-3xl border border-slate-800 shadow-2xl">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Registrar Categoría</h5>
                        <button id="btnCloseModal" type="button" class="text-white btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body row m-0" id="body_modal"> 
                        <form id="reg_categoria" action="../controller/categoria_controller.php" method="post" class="SendFormAjax" autocomplete="off" data-type-form="save">
                            <input type="hidden" name="modulo" value="Guardar">          
                            <div class="row mb-3 justify-content-center text-start">
                                <div class="col-12 mb-3">
                                    <label class="col-form-label">Nombre <span style="color:#f00;">*</span> </label>
                                    <input type="text" pattern="[A-Za-zñÑÁÉÍÚÓáéíóú ]{4,30}" required="" placeholder="Ejemplo: Lácteos y Refrigerados" class="form-control" id="input_añadir_categoria" name="nombre_categoria">
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label class="col-form-label">Descripción <span style="color:#f00;">*</span> </label>
                                    <textarea required placeholder="Ejemplo: Leche, yogur, queso, mantequilla, huevos, postres fríos." class="form-control" name="descripcion" pattern="[A-Za-zñÑÁÉÍÚÓáéíóú ]{4,200}"></textarea>
                                </div>

                                <div class="col-12 mb-3 text-start">
                                    <p class="form-p">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button id="btn_guardar_modal" form="reg_categoria" type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="registrar_producto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-theme="dark">
            <div id="modal_tamano" class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content bg-slate-900 p-6 rounded-3xl border border-slate-800 shadow-2xl">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Registrar Producto</h5>
                        <button id="btnCloseModal" type="button" class="text-white btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body row m-0" id="body_modal"> 
                        <form id="product-form" action="../controller/producto_controlador.php" method="post" class="SendFormAjax" autocomplete="off" data-type-form="save"  enctype="multipart/form-data" >
                            <input type="hidden" name="modulo" value="Guardar">
                            <input name="producto" placeholder="Nombre" required class="mb-3 w-full bg-slate-800 p-3 rounded-xl border-none text-white outline-none focus:ring-1 ring-purple-500">
                            
                            <input name="price" type="number" step="0.01" placeholder="Precio ($)" class="w-full mb-3 bg-slate-800 p-3 rounded-xl border-none text-white outline-none focus:ring-1 ring-purple-500">
                            
                            <select id="category" class="form-control form-select w-full mb-3 bg-slate-800 p-3 rounded-xl border-none text-white outline-none focus:ring-1 ring-purple-500" name="category[]">
                                <option selected disabled> Selecciona una opción</option>
                                <?php category_model::optionsId(); ?>
                            </select>

                            <input type="file" name="image[]" multiple accept="image/*" class="my-3 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 cursor-pointer"/>

                            <textarea name="desc" placeholder="Descripción del producto..." class="w-full bg-slate-800 p-3 rounded-xl border-none text-white h-24 text-sm outline-none focus:ring-1 ring-purple-500"></textarea>
                            
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button id="btn_guardar_modal" form="product-form" type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editar_producto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-theme="dark">
            <div id="modal_tamano" class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content bg-slate-900 p-6 rounded-3xl border border-slate-800 shadow-2xl">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modificar Producto</h5>
                        <button id="btnCloseModal" type="button" class="text-white btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body row m-0" id="body_modal"> 
                        <form id="product-form" action="../controller/producto_controlador.php" method="post" class="SendFormAjax" autocomplete="off" data-type-form="save">
                            <input type="hidden" name="modulo" value="Guardar">
                            <input name="name" placeholder="Nombre" required class="w-full bg-slate-800 p-3 rounded-xl border-none text-white outline-none focus:ring-1 ring-purple-500">
                            <div class="grid grid-cols-2 gap-3">
                                <input name="price" type="number" step="0.01" placeholder="Precio ($)" class="bg-slate-800 p-3 rounded-xl border-none text-white outline-none focus:ring-1 ring-purple-500">
                                <input name="category" placeholder="Categoría" required class="bg-slate-800 p-3 rounded-xl border-none text-white outline-none focus:ring-1 ring-purple-500">
                            </div>
                            <textarea name="imgs" placeholder="URLs de imágenes (sep. por coma)" required class="w-full bg-slate-800 p-3 rounded-xl border-none text-white h-20 text-xs outline-none focus:ring-1 ring-purple-500"></textarea>
                            <textarea name="desc" placeholder="Descripción del pedido..." class="w-full bg-slate-800 p-3 rounded-xl border-none text-white h-24 text-sm outline-none focus:ring-1 ring-purple-500"></textarea>
                            
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button id="btn_guardar_modal" form="editar_producto" type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>


        

        <div class="modal fade" id="modalList" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-theme="dark">
            <div id="modal_tamano" class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content bg-slate-900 p-6 rounded-3xl border border-slate-800 shadow-2xl">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Lista de Categorías</h5>
                        <button id="btnCloseModal" type="button" class="text-white btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body row m-0" id="body_modal"> </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>




        <div class="msjFormSend"></div>
        <script type="text/javascript" src="js/jquery-3.6.0.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <!-- Custom scripts for all pages-->
        <script src="js/SendForm.js"></script>
        <script src="js/sweetalert2.min.js"></script>
        <script src="js/app.js"></script>
        <script src="js/validator.js"></script>
        <script src="js/tiempo_inactividad.js"></script>
        <script src="js/cerrar_sesion.js"></script>
        
        <!-- datatable js files -->
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/datatables.min.js"></script>
        <script src="js/dataTables.bootstrap5.min.js"></script>

        <script type="text/javascript" src="./js/select2.min.js"></script> <!-- libreria selec2 -->
        <script type="text/javascript">
            // inicializar la libreria Select2 
            $('.select2').select2();


            $(document).ready(function() {
                var t = $('#example').DataTable( { 
                    language: {
                        url: 'js/dataTables-Español.json'
                    },
                    lengthMenu: [[5, 10, 15, 20, 25, 50, 100, -1], [5, 10, 15, 20, 25, 50, 100, "Todos"]],
                    responsive: true,
                } );

                t.on( 'order.dt search.dt', function () {
                    let i = 1;
            
                    t.cells(null, 0, {search:'applied', order:'applied'}).every( function (cell) {
                        this.data(i++);
                    } );
                } ).draw();
            } );
            
            function dataTable(classTable = "example"){
                var t = $(`.${classTable}`).DataTable( { 
                    language: {
                        url: 'js/dataTables-Español.json'
                    },
                    lengthMenu: [[5, 10, 15, 20, 25, 50, 100, -1], [5, 10, 15, 20, 25, 50, 100, "Todos"]],
                    responsive: true,
                } );

                t.on( 'order.dt search.dt', function () {
                    let i = 1;
                    t.cells(null, 0, {search:'applied', order:'applied'}).every( function (cell) {
                        this.data(i++);
                    } );
                } ).draw();
            }
            
        </script>

    </body>

    </html>
<?php }else{
    header("location: ../");
}
?>
