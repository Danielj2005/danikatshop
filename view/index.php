<?php 
session_start();

include_once "../model/mainModel.php"; // se incluye el model principal
include_once "../model/productModel.php"; // se incluye el model producto

                                    
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
                    <button onclick="navigate('admin')" class="d-none text-purple-400 text-sm font-bold">Gestionar</button>
                    <button onclick="logout()" class="d-none text-slate-500 text-xs"><i class="fas fa-sign-out-alt"></i></button>
                    <a href="login" class="text-slate-700 hover:text-purple-500 transition"><i class="fas fa-user-lock"></i></a> 
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
                            <p class="text-xs text-slate-500">Administrador</p>
                        </div>
                    </div>

                    <div class="row justify-content-around align-items-center">

                        <div class="text-center col-12 col-md-6 fs-4 bg-slate-900 p-6 rounded-3xl border border-slate-800 shadow-2xl">
                            <h3 class="text-lg font-bold text-slate-400">Categorías</h3>
    
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
                            </div>
                        </div> 

                    </div>


                    <div id="tableListProducts" class="text-white bg-slate-900 p-6 rounded-3xl border border-slate-800 shadow-2xl justify-content-between align-items-center">
                        
                        <div class="text-center col-12 fs-4">
                            <h3 class="text-lg font-bold text-slate-400">Productos en Línea (<?=  mysqli_num_rows($catalogo); ?>)</h3>

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
                            </div>
                        </div> 
                    
                        <table class="table example mb-3 table-hover" id="example">
                            <thead>
                                <tr class="text-white">
                                    <th class="col text-center" scope="col">N.º</th>
                                    <th class="col text-center" scope="col">Producto</th>
                                    <th class="col text-center" scope="col">Precio ($)</th>
                                    <th class="col text-center" scope="col">Editar</th>
                                    <th class="col text-center" scope="col">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-white">
                                    <td class="col text-center" scope="col"></td>
                                    <td class="col text-center" scope="col">Producto</td>
                                    <td class="col text-center" scope="col">Precio ($)</td>
                                    <td class="col text-center" scope="col">Editar</td>
                                    <td class="col text-center" scope="col">Eliminar</td>
                                </tr>
                                <tr class="text-white">
                                    <td class="col text-center" scope="col"></td>
                                    <td class="col text-center" scope="col">Producto</td>
                                    <td class="col text-center" scope="col">Precio ($)</td>
                                    <td class="col text-center" scope="col">Editar</td>
                                    <td class="col text-center" scope="col">Eliminar</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="grid lg:grid-cols-3 gap-8">
                        <section class="lg:col-span-1 bg-slate-900 p-6 rounded-3xl border border-slate-800 shadow-2xl h-fit sticky top-24">
                            
                            <div class="text-center col-12 fs-4">
                                <h3 class="text-center my-4 fs-3">Categorías</h3>

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
                                            data-bs-target="#modal"><i class="bi bi-list-columns-reverse"></i> Lista de Categorías
                                        </button>
                                    </div>
                                </div>
                            </div> 
                        </section>

                        <section class="lg:col-span-1 bg-slate-900 p-6 rounded-3xl border border-slate-800 shadow-2xl h-fit sticky top-24">
                            
                            <div class="text-center col-12 fs-4">
                                <h3 class="text-center my-4 fs-3">Marcas</h3>

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
                        </section>

                        <section class="lg:col-span-1 bg-slate-900 p-6 rounded-3xl border border-slate-800 shadow-2xl h-fit sticky top-24">
                            <h3 class="text-lg font-bold text-purple-400 mb-6 flex items-center gap-2">
                                <i class="fas ${state.editingId ? 'fa-edit' : 'fa-plus-circle'}"></i> 
                                ${state.editingId ? 'Editar Producto' : 'Publicar Nuevo'}
                            </h3>
                            <form id="product-form" onsubmit="handleProductSubmit(event)" class="space-y-4">
                                <input name="name" placeholder="Nombre" required class="w-full bg-slate-800 p-3 rounded-xl border-none text-white outline-none focus:ring-1 ring-purple-500">
                                <div class="grid grid-cols-2 gap-3">
                                    <input name="price" type="number" step="0.01" placeholder="Precio ($)" class="bg-slate-800 p-3 rounded-xl border-none text-white outline-none focus:ring-1 ring-purple-500">
                                    <input name="category" placeholder="Categoría" required class="bg-slate-800 p-3 rounded-xl border-none text-white outline-none focus:ring-1 ring-purple-500">
                                </div>
                                <textarea name="imgs" placeholder="URLs de imágenes (sep. por coma)" required class="w-full bg-slate-800 p-3 rounded-xl border-none text-white h-20 text-xs outline-none focus:ring-1 ring-purple-500"></textarea>
                                <textarea name="desc" placeholder="Descripción del pedido..." class="w-full bg-slate-800 p-3 rounded-xl border-none text-white h-24 text-sm outline-none focus:ring-1 ring-purple-500"></textarea>
                                
                                <button class="w-full ${state.editingId ? 'bg-fuchsia-600' : 'bg-purple-600'} py-3 rounded-xl font-bold hover:opacity-90 transition-all shadow-lg">
                                    ${state.editingId ? 'Guardar Cambios' : 'Subir al Catálogo'}
                                </button>
                                ${state.editingId ? `<button type="button" onclick="cancelEdit()" class="w-full text-slate-500 text-sm mt-2">Cancelar Edición</button>` : ''}
                            </form>
                        </section>

                        <section class="lg:col-span-2 space-y-4">
                            <h3 class="text-lg font-bold text-slate-400">Productos en Línea (<?=  mysqli_num_rows($catalogo); ?>)</h3>
                            <div class="grid gap-3">
                                <?php 
                                    while ($mostrar = mysqli_fetch_array($catalogo)) {  
                                        
                                        $imgSrc = explode(',', $mostrar['images']);
                                        $url = $mostrar["id"].'/'.$imgSrc[0];
                                    ?>

                                        <div class="bg-slate-900/40 border border-slate-800 p-3 rounded-2xl flex items-center gap-4 hover:bg-slate-800/40 transition">
                                            <img src="storage/<?= $url ?>" class="w-16 h-16 object-cover rounded-xl shadow-md">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-white font-medium truncate"><?= $mostrar['nombre'] ?></h4>
                                                <p class="text-slate-500 text-xs"><?= $mostrar['price'] ? '$' . $mostrar['price'] : 'Bajo pedido' ?></p>
                                            </div>
                                            <div class="flex gap-2">
                                                <button onclick="prepareEdit(<?= $mostrar['id']; ?>)" class="p-2 text-blue-400 hover:bg-blue-400/10 rounded-lg transition"><i class="fas fa-edit"></i></button>
                                                <button onclick="deleteProduct(<?= $mostrar['id']; ?>)" class="p-2 text-red-400 hover:bg-red-400/10 rounded-lg transition"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                <?php } ?>
                            </div>
                        </section>
                    </div>
                </div>
            </main>
        </div>

        <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div id="modal_tamano" class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button id="btnCloseModal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body row m-0" id="body_modal"> </div>

                    <div class="modal-footer">
                        <button id="btn_guardar_modal" type="submit" class="btn btn-success">Guardar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript" src="js/jquery-3.6.0.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <!-- Custom scripts for all pages-->
        <script src="js/SendForm.js"></script>
        <script src="js/sweetalert2.min.js"></script>
        <script src="js/app.js"></script>
        <script src="js/validator.js"></script>
        
        <!-- datatable js files -->
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/datatables.min.js"></script>
        <script src="js/dataTables.bootstrap5.min.js"></script>

        <script type="text/javascript">
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
    header('../index.php');
}
?>
