
/**
 * APP.JS - DANIKATSHOP
 * Carga dinámica desde archivos JSON externos
 */
// Definimos el estilo base para DanikatShop
const DanikatAlert = Swal.mixin({
    customClass: {
        popup: 'bg-slate-900 border border-slate-800 rounded-3xl',
        title: 'text-white font-bold',
        confirmButton: 'bg-purple-600 text-white px-8 py-3 rounded-2xl mx-2 hover:bg-purple-500 transition',
        cancelButton: 'bg-slate-700 text-slate-300 px-8 py-3 rounded-2xl mx-2 hover:bg-slate-600 transition'
    },
    buttonsStyling: false,
    background: '#0f172a',
    color: '#f8fafc'
});


let estado = {
    loading: true, // Empezamos cargando
    productState: true,
    selectedProduct: null,
    editingId: null
};



// --- EVENTOS Y NAVEGACIÓN ---

/**
 * Filtra en tiempo real. 
 * No necesita pegarle a la BD en cada tecla porque ya tenemos los datos en state.
 */
window.handleSearch = (val) => {
    
    const searchTerm = val.toLowerCase();
    const products = document.querySelectorAll('.product-card'); 

    products.forEach(product => {
        // Busca en todo el texto de la tarjeta (Nombre, descripción, precio, etc.)
        const text = product.innerText.toLowerCase();
        
        if (text.includes(searchTerm)) {
            product.style.display = ""; // Muestra el elemento (usa el display original)
        } else {
            product.style.display = "none"; // Oculta el elemento
        }
    });
};

/**
 * Filtra productos por categoría basándose en el texto del botón o data-attributes
 */
window.filterByCategory = (categoryName) => {
    const products = document.querySelectorAll('.product-card');
    const buttons = document.querySelectorAll('.category-btn');

    // Actualizar estilos visuales de los botones de filtro
    buttons.forEach(btn => {
        const isMatch = btn.innerText.trim() === categoryName || (categoryName === 'all' && btn.innerText.trim() === 'Todos');
        if (isMatch) {
            btn.classList.add('bg-purple-600', 'text-white', 'border-purple-600', 'shadow-[0_0_10px_rgba(168,85,247,0.5)]');
        } else {
            btn.classList.remove('bg-purple-600', 'text-white', 'border-purple-600', 'shadow-[0_0_10px_rgba(168,85,247,0.5)]');
        }
    });

    products.forEach(product => {
        const productCategories = product.getAttribute('data-categories')?.toLowerCase().split(',') || [];
        if (categoryName === 'all' || productCategories.includes(categoryName.toLowerCase())) {
            product.style.display = "";
        } else {
            product.style.display = "none";
        }
    });
};

const scrappCoin = async () => {
    try {
        const url = `../controller/dolarApi.php`;
    
        const response = await fetch(url);
        if (!response.ok) throw new Error("Error en la petición");

        const data = await response.json();

        if (data.status === "success") {
            // Ejemplo de uso:
            console.log(`Dólar BCV: ${data.USD} Bs.`);
            console.log(`USDT P2P: ${data.EURO} Bs.`);
            return data;
        }
    }catch (e){
        console.error(e);
    }
    
};

let tableActiveHtml = ``;
let tableInactiveHtml = ``;

async function getProductos() {
    try {
        // Consultamos al PHP que trae los datos de MySQL
        const catizacion = await scrappCoin();
        let content = {
            UID: 1,
            prices: catizacion
        };
        const active = await fetch(`../controller/listaProductos.php`,{
            method: "POST",
            body: JSON.stringify(content)
        });

        const data = await active.text();

        content.UID = 0;
        const inactive = await fetch(`../controller/listaProductos.php`, {
            method: "POST",
            body: JSON.stringify(content)
        });
        const inact = await inactive.text();

        // let tableActive = document.getElementById('activos');
        let tbodyActive = document.querySelector('#activos tbody');
        let tbodyInactive = document.querySelector('#inactivos tbody');

        tbodyActive.innerHTML = data;
        tbodyInactive.innerHTML = inact;

        tableActiveHtml = tbodyActive.innerHTML; // Guardamos el HTML original para futuras actualizaciones
        tableInactiveHtml = tbodyInactive.innerHTML; // Guardamos el HTML original para futuras actualizaciones

        dataTable("tableActivos");
        dataTable("tableInactivos");
        SendFormAjax();

        // document.getElementById('activos').innerHTML = data;
        // document.getElementById('inactivos').innerHTML = data;
        // dataTable("tableInactivos");

    } catch (error) {
        console.error("Fallo de conexión con BD:", error);
    }
}

function changeState () {
        
    const btn = document.getElementById('btnChangeState');
        
    if (estado.productState) {
        document.getElementById('inactivos').classList.remove('d-none');
        document.getElementById('activos').classList.add('d-none');
        btn.textContent = "Ver Productos activos";
        estado.productState = false;
    }else{
        btn.textContent = "Ver Productos inactivos";
        document.getElementById('activos').classList.remove('d-none');
        document.getElementById('inactivos').classList.add('d-none');
        estado.productState = true;
    }
    

}


document.addEventListener('DOMContentLoaded', () => {
    
    if (document.getElementById('loader')) {
        setTimeout(() => {
            document.getElementById('loader').style.display = 'none';
            if (document.getElementById('app')) {
                document.getElementById('app').style.display = 'block';
            }
        }, 1500);
    }

    if (index) {
        getProductos();
    } 
});

async function editingProduct(ID) {

    try {

        // Consultamos al PHP que trae los datos de MySQL
        const resp = await fetch('../controller/producto.php?UID=' + ID);
        const data = await resp.text();

        document.getElementById('tableModalEdit').innerHTML = data;

    } catch (error) {
        console.error("Fallo de conexión con BD:", error);
    }
}

const createList = (body) =>  `
    <div class="table table-responsive">
        <table class="table table-striped mb-3 tableListModal" id="tableList">
            ${body}
        </table>
    </div>`;


// --- CARGA DE DATOS EXTERNOS ---
async function getList() {
    try {

        // Consultamos al PHP que trae los datos de MySQL
        const resp = await fetch('../controller/api.php');
        const data = await resp.text();

        document.getElementById('bodyModalList').innerHTML = createList(data);
        dataTable("tableListModal");

    } catch (error) {
        console.error("Fallo de conexión con BD:", error);
    }
}
