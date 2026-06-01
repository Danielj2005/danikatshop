

let tableProductosActivos = ``;
let tableProductosInactivos = ``;

let estado = { productState: true, };



const createCatalogo = (id, nombre, precio, urlImage) =>
    `<div data-categories="" class="product-card product_${id} group bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden hover:border-purple-500/50 transition-all duration-500 animate-slide-up">
        <div class="relative overflow-hidden cursor-pointer" style="height: 15rem;">
            <img src="${urlImage}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            <div class="absolute bottom-0 flex flex-wrap gap-2 items-center">
                <span class="badge text-bg-danger text-sm fw-bold">Bajo pedido</span>
            </div>
        </div>

        <div class="p-3">
            <div class="">
                <button onclick="detallesProductoById()" class="text-sm mb-3 text-white font-semibold" data-bs-toggle="modal" data-bs-target="#exampleModal"> producto </button>
            </div>

            <div class="flex flex-wrap justify-between">
                <div class="mb-3">
                    <button onclick="detallesProductoById()" type="button" class="btn_details btn btn-outline-warning rounded-2xl transition-all gap-2 flex items-center justify-center " data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="bi bi-pencil-square text-lg"></i>
                        <span class="d-none d-md-block text-sm font-bold"> Editar</span>
                    </button> 
                </div>

                <div class="mb-3">
                    <button onclick="detallesProductoById()" type="button" class="btn_details btn btn-outline-secondary rounded-2xl transition-all gap-2 flex items-center justify-center " data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="bi bi-eye text-lg"></i> 
                        <span class="d-none d-md-block text-sm font-bold"> Ver Detalles</span>
                    </button> 
                </div>

                <div class="mb-2">
                    <button onclick="detallesProductoById(2)" type="button" class="btn_details btn btn-outline-danger rounded-2xl transition-all gap-2 flex items-center justify-center " data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="bi bi-x-circle text-lg"></i> 
                        <span class="d-none d-md-block text-sm font-bold"> Desactivar</span>
                    </button> 
                </div>
            </div>
        </div>
    </div>`;




async function getProductos() {
    try {
        
        const isMobile = /iPhone|Android/i.test(navigator.userAgent);
        // Consultamos al PHP que trae los datos de MySQL
        const catizacion = await scrappCoin();
        
        if (isMobile) {
            // En móviles, mejor cambiar la ubicación de la pestaña actual
            const [active, inactive] = await Promise.all([
                fetch(`../controller/listaProductos.php`,{
                    method: "POST", 
                    body: JSON.stringify({ UID: 2, state: 1, prices: catizacion })
                }),
                fetch(`../controller/listaProductos.php`,{
                    method: "POST", 
                    body: JSON.stringify({ UID: 2, state: 0, prices: catizacion })
                })

            ]);

            const productosActivos = await active.text();
            const productosInactivos = await inactive.text();

            let cardsProductosActivos = document.getElementById('#cards_activos');
            let cardsProductosInactivos = document.getElementById('#cards_inactivos');
    
            cardsProductosActivos.innerHTML = productosActivos;
            cardsProductosInactivos.innerHTML = productosInactivos;

        } else {
            // En PC, abrimos pestaña nueva
            const [active, inactive] = await Promise.all([
                fetch(`../controller/listaProductos.php`,{
                    method: "POST", 
                    body: JSON.stringify({ UID: 1, state: 1, prices: catizacion })
                }),
                fetch(`../controller/listaProductos.php`,{
                    method: "POST", 
                    body: JSON.stringify({ UID: 1, state: 0, prices: catizacion })
                })
            ]);
            
            const productosActivos = await active.text();
            const productosInactivos = await inactive.text();
        
            // En PC, abrimos pestaña nueva
    
            let tbodyProductosActivos = document.querySelector('#activos tbody');
            let tbodyProductosInactivos = document.querySelector('#inactivos tbody');
    
            tbodyProductosActivos.innerHTML = productosActivos;
            tbodyProductosInactivos.innerHTML = productosInactivos;

            tableProductosActivos = tbodyProductosActivos.innerHTML; // Guardamos el HTML original para futuras actualizaciones
            tableProductosInactivos = tbodyProductosInactivos.innerHTML; // Guardamos el HTML original para futuras actualizaciones
    
            dataTable("tableActivos");
            dataTable("tableInactivos");
            SendFormAjax();
        }


    } catch (error) {
        console.error("Fallo de conexión con BD:", error);
    }
}

async function editingProduct(ID) {

    try {
        // Consultamos al PHP que trae los datos de MySQL
        const resp = await fetch('../controller/producto.php?UID=' + ID);
        const dataProductToEdit = await resp.text();

        document.getElementById('tableModalEdit').innerHTML = dataProductToEdit;

    } catch (error) {
        console.error("Fallo de conexión con BD:", error);
    }
}



function changeState () {
        
    const btn = document.getElementById('btnChangeState');

    const list_activos = document.getElementById('activos');
    const list_inactivos = document.getElementById('inactivos');

    if (estado.productState) {
        btn.textContent = "Ver Productos activos";

        list_activos.classList.add('d-none');
        list_inactivos.classList.remove('d-none');

        estado.productState = false;
    }else{
        btn.textContent = "Ver Productos inactivos";

        list_activos.classList.remove('d-none');
        list_inactivos.classList.add('d-none');

        estado.productState = true;
    }
    

}


// --- CARGA DE Lista de Categorias ---

async function getList_category() {
    try {

        // Consultamos al PHP que trae los datos de MySQL
        const resp = await fetch('../controller/lista_categorias.php');
        const tbody = await resp.text();

        // creamos un element div
        const div = document.createElement('div');
        div.className = "table table-responsive";
        
        // creamos un element table
        const table = document.createElement('table');
        table.className = "table table-striped mb-3 tableListModal";
        table.id = "tableList";

        table.appendChild(tbody); // insertamos el body de la table
        div.appendChild(table); // insertamos la table en su contenedor

        // insertamos el contenedor en el modal de listas
        document.getElementById('bodyModalList').innerHTML = div; 

        // inializamos la funcion dataTable
        dataTable("tableListModal");

    } catch (error) {
        console.error("No se encontraron categorías:");
    }
}

