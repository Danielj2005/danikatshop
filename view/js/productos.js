

let tableProductosActivos = ``;
let tableProductosInactivos = ``;

let estado = { productState: true, };


async function getProductos() {
    try {
        // Consultamos al PHP que trae los datos de MySQL
        const catizacion = await scrappCoin();
        
        const [active, inactive] = await Promise.all([
            fetch(`../controller/listaProductos.php`,{
                method: "POST", 
                body: JSON.stringify({ UID: 1, prices: catizacion })
            }),
            fetch(`../controller/listaProductos.php`,{
                method: "POST", 
                body: JSON.stringify({ UID: 0, prices: catizacion })
            })

        ]);
        
        const productosActivos = await active.text();
        const productosInactivos = await inactive.text();

        let tbodyProductosActivos = document.querySelector('#activos tbody');
        let tbodyProductosInactivos = document.querySelector('#inactivos tbody');

        tbodyProductosActivos.innerHTML = productosActivos;
        tbodyProductosInactivos.innerHTML = productosInactivos;

        tableProductosActivos = tbodyProductosActivos.innerHTML; // Guardamos el HTML original para futuras actualizaciones
        tableProductosInactivos = tbodyProductosInactivos.innerHTML; // Guardamos el HTML original para futuras actualizaciones

        dataTable("tableActivos");
        dataTable("tableInactivos");
        SendFormAjax();

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
