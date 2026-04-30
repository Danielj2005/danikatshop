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





let state = {
    category: [],
    users: [],
    loading: true, // Empezamos cargando
    currentUser: null,
    currentPage: 1,
    itemsPerPage: 15,
    searchTerm: "",
    view: 'catalog',
    selectedProduct: null,
    editingId: null
};



const saveToStorage = async () => {
    // 1. Guardar localmente (Persistencia inmediata para el admin)
    localStorage.setItem('products', JSON.stringify(state.products));
    localStorage.setItem('users', JSON.stringify(state.users));

    // 2. Sincronizar con el servidor (Para que todos los clientes lo vean)
    await syncWithServer('products', state.products);
};

// Nueva función para hablar con PHP
async function syncWithServer(type, content) {
    try {
        const response = await fetch('./controller/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                type: type,
                content: content
            })
        });

        const result = await response.json();
        
        if (result.status === 'success') {
            console.log(`Servidor actualizado: ${type}`);
        } else {
            console.error(`Error de servidor: ${result.message}`);
            alert("Atención: Los cambios se guardaron en tu navegador pero NO en el servidor. Revisa permisos de carpeta.");
        }
    } catch (error) {
        console.error("Error en la petición fetch:", error);
    }
}


// --- LÓGICA DE WHATSAPP ---
window.askWhatsApp = (id) => {
    const p = state.products.find(item => item.id === id);
    const precioTxt = p.price ? `por un valor de *$${p.price}*` : "(Precio a convenir según pedido)";
    const msg = `¡Hola DanikatShop! Me interesa su producto:\n\n*${p.name}*\nCategoría: ${p.category}\n${precioTxt}\n\n¿Podrían darme más detalles?`;
    window.open(`https://wa.me/584244189963?text=${encodeURIComponent(msg)}`, '_blank');
};


// --- RENDERIZADO ---
function render() {
    const app = document.getElementById('app');

    // SI ESTÁ CARGANDO: Mostrar el Loader
    if (state.loading) {
        app.innerHTML = `
            <div class="flex flex-col items-center justify-center min-h-screen">
                <div class="w-12 h-12 border-4 border-purple-500/20 border-t-purple-500 rounded-full animate-spin"></div>
                <p class="mt-4 text-slate-500 animate-pulse">Cargando DanikatShop...</p>
            </div>
        `;
        return; // Detenemos el renderizado aquí hasta que loading sea false
    }

    // SI NO ESTÁ CARGANDO: Renderizar la App normal
    const filtered = state.products.filter(p => 
        p.name.toLowerCase().includes(state.searchTerm) || 
        p.category.toLowerCase().includes(state.searchTerm)
    );

    let content = '';

    if (state.view === 'login') {
        content += ViewLogin();
    } 
    else if (state.view === 'admin' && state.currentUser) {
        content += ViewAdminPanel();
    } 
    else {
        content += ViewCatalog(filtered);
    }

    if (state.selectedProduct) {
        content += ViewProductModal(state.selectedProduct);
    }

    app.innerHTML = content;
}


// --- EVENTOS Y NAVEGACIÓN ---

/**
 * Filtra en tiempo real. 
 * No necesita pegarle a la BD en cada tecla porque ya tenemos los datos en state.
 */
window.handleSearch = (val) => { 
    state.searchTerm = val.toLowerCase(); 
    state.currentPage = 1; // Siempre volvemos a la página 1 al buscar
    render(); 
};


document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('loader')) {
        setTimeout(() => {
            document.getElementById('loader').style.display = 'none';
            if (document.getElementById('app')) {
                document.getElementById('app').style.display = 'block';
            }
        }, 1500);
    }

});


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


// Iniciar app
// getList();
const createEditTable = (id, images) => 
    images.map((i) => (
        `<tr class="text-black">
            <th class="col text-center" scope="col">
                <div class="d-flex justify-content-center align-items-center">
                    <img src=".${i}" style="width: 5rem; height:5rem; " class="d-block" alt="...">
                </div>
            </th>
            <th class="col text-center" scope="col">
                <input type="file" name="image[]" multiple accept="image/*" class="rounded-3xl border my-3 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:bg-purple-600 hover:file:bg-purple-500 cursor-pointer text-white transition"/>
            </th>
            <th class="col text-center" scope="col">
                <button dataId="${id}" class="btn_modal btn btn-danger">
                    <i class="bi bi-trash"></i>
                </button>
            </th>
        </tr>`
    )).join("");


const createModalEditProduct = (data) => `
    <div class="col-12 col-md-6 mb-3">
        <label class="col-form-label">Nombre del producto <span style="color:#f00;">*</span> </label>
        <input name="producto" value="${data.nombre}" placeholder="Nombre" required class="mb-3 w-full bg-slate-800 p-3 rounded-xl border-none text-white outline-none focus:ring-1 ring-purple-500">
    </div>
    <div class="col-12 col-md-6 mb-3">
        <label class="col-form-label">Precio (opcional)</label>
        <input name="price" value="${data.precio}" type="number" step="0.01" placeholder="Precio ($)" class="w-full mb-3 bg-slate-800 p-3 rounded-xl border-none text-white outline-none focus:ring-1 ring-purple-500">
    </div>
    
    <div class="rounded-3xl mb-4 bg-white col-12 table-responsive overflow-hidden overflow-x-auto">

        <table class="mb-3 no-footer table table-borderless table-group-divider table-hover table-striped">
            <thead>
                <tr class="text-black">
                    <th class="col text-center" scope="col">Imagen</th>
                    <th class="col text-center" scope="col">Editar</th>
                    <th class="col text-center" scope="col">Eliminar</th>
                </tr>
            </thead>
            <tbody>
                ${ createEditTable(data.id, data.imgs) }
            </tbody>
        </table>
    </div>

    <div class="col-12 mb-3">
        <label class="col-form-label">Descripción <span style="color:#f00;">*</span> </label>
        <textarea name="desc" value="${data.descripcion}" placeholder="Descripción del producto..." class="w-full bg-slate-800 p-3 rounded-xl border-none text-white h-24 text-sm outline-none focus:ring-1 ring-purple-500"></textarea>
    </div>
    `;


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

const editProduct = document.querySelectorAll('.btn_edit_produto');

editProduct.forEach((btn) => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();

        let ID = btn.getAttribute('dataId');
        editingProduct(ID);

    });
});



