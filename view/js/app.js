/**
 * APP.JS - DANIKATSHOP
 * Carga dinámica desde archivos JSON externos
 */

let state = {
    products: [],
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

// --- CARGA DE DATOS EXTERNOS ---
async function initApp() {
    try {

        // Consultamos al PHP que trae los datos de MySQL
        const resp = await fetch('./controller/api.php');
        const data = await resp.json();

        if (Array.isArray(data)) {
            state.products = data;
        } else {
            console.error("Error en formato de datos de BD:", data);
        }

        state.loading = false;
        setTimeout(() => {

            document.getElementById('loader').style.display = 'none';

        }, 3000);

    } catch (error) {
        console.error("Fallo de conexión con BD:", error);
        state.loading = false;
        render();
    }
}




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

// --- COMPONENTES UI ---

const Navbar = () => `
    <nav class="sticky top-0 z-40 bg-slate-950 border-b border-purple-900/20 p-4">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-4 justify-between items-center">
            <div onclick="goHome()" class="cursor-pointer text-center md:text-left">
                <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-fuchsia-500 bg-clip-text text-transparent">DanikatShop</h1>
                <p class="text-[10px] text-slate-500 uppercase tracking-widest">Todo lo que buscas en un solo lugar</p>
            </div>

            <div class="relative w-full md:w-1/2">
                <input type="text" placeholder="Buscar tortas, arreglos, manualidades..." 
                       oninput="handleSearch(this.value)" value="${state.searchTerm}"
                       class="w-full bg-slate-900 border border-slate-700 rounded-full px-5 py-2 text-sm focus:ring-2 ring-purple-500 outline-none">
                <i class="fas fa-search absolute right-4 top-2.5 text-slate-500"></i>
            </div>

            <div class="flex gap-4 items-center">
                ${state.currentUser 
                    ? `<button onclick="navigate('admin')" class="text-purple-400 text-sm font-bold">Gestionar</button>
                       <button onclick="logout()" class="text-slate-500 text-xs"><i class="fas fa-sign-out-alt"></i></button>`
                    : `<button onclick="navigate('login')" class="text-slate-700 hover:text-purple-500 transition"><i class="fas fa-user-lock"></i></button>`
                }
            </div>
        </div>
    </nav>
`;




const ProductCard = (p) => `
    <div class="group bg-slate-900/40 border border-slate-800 rounded-[2rem] overflow-hidden hover:border-purple-500/50 transition-all duration-500 animate-slide-up">
        <div class="relative h-64 overflow-hidden cursor-pointer" onclick="openModal(${p.id})">
            <img src="${p.imgs[0]}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            <div class="absolute bottom-4 left-4 bg-black/60 backdrop-blur-md px-4 py-1 rounded-full border border-white/10">
                <span class="text-sm font-bold text-white">${p.price ? `$${p.price}` : 'Bajo pedido'}</span>
            </div>
        </div>
        <div class="p-6">
            <h3 class="text-white font-semibold text-lg mb-1 truncate">${p.name}</h3>
            <p class="text-purple-400 text-xs font-bold uppercase mb-4 tracking-wider">${p.category}</p>
            <form action="./producto" method="get">
                <input type="hidden" value="2" name="id" />
                <button type="submit" class="w-full bg-slate-800 hover:bg-purple-600 text-white py-3 rounded-2xl transition-all flex items-center justify-center gap-2">
                    <i class="fab fa-whatsapp text-lg"></i> <span class="text-sm font-bold">Consultar</span>
                </button>
            </form>
        </div>
    </div>
`;

// --- LÓGICA DE WHATSAPP ---
window.askWhatsApp = (id) => {
    const p = state.products.find(item => item.id === id);
    const precioTxt = p.price ? `por un valor de *$${p.price}*` : "(Precio a convenir según pedido)";
    const msg = `¡Hola DanikatShop! Me interesa su producto:\n\n*${p.name}*\nCategoría: ${p.category}\n${precioTxt}\n\n¿Podrían darme más detalles?`;
    window.open(`https://wa.me/584244189963?text=${encodeURIComponent(msg)}`, '_blank');
};


// --- VISTAS (FUNCIONES QUE RETORNAN HTML) ---

const ViewCatalog = (productsList) => `
    <header class="py-12 px-6 text-center animate-fade-in">
        <h2 class="text-4xl font-bold italic text-white mb-2">Todo lo que buscas en un solo lugar</h2>
    </header>
    <main class="max-w-7xl mx-auto p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            ${productsList.map(p => ProductCard(p)).join('')}
        </div>
    </main>
`;

const ViewLogin = () => `
    <div class="flex items-center justify-center min-h-[60vh] p-4">
        <form onsubmit="handleLogin(event)" class="bg-slate-900 border border-slate-800 p-8 rounded-[2.5rem] w-full max-w-sm shadow-2xl">
            <h2 class="text-2xl font-bold text-center mb-8 text-purple-400">Acceso Administrativo</h2>
            <input name="user" type="text" placeholder="Cédula" required class="w-full bg-slate-800 p-4 rounded-2xl mb-4 text-white outline-none ring-purple-500 focus:ring-2">
            <input name="pass" type="password" placeholder="Contraseña" required class="w-full bg-slate-800 p-4 rounded-2xl mb-6 text-white outline-none ring-purple-500 focus:ring-2">
            <button class="w-full bg-purple-600 py-4 rounded-2xl font-bold hover:bg-purple-500 transition shadow-lg shadow-purple-500/20">Entrar</button>
            <button type="button" onclick="goHome()" class="w-full text-slate-500 mt-4 text-sm underline">Volver al catálogo</button>
        </form>
    </div>
`;

const ViewAdminPanel = () => `
    <div class="max-w-6xl mx-auto p-6 space-y-10 animate-fade-in">
        <div class="flex justify-between items-center border-b border-slate-800 pb-6">
            <h2 class="text-3xl font-bold text-white">Panel de <span class="text-purple-500">Gestión</span></h2>
            <div class="text-right">
                <p class="text-slate-200 font-bold">${state.currentUser.name}</p>
                <p class="text-xs text-slate-500">Administrador</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
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
                <h3 class="text-lg font-bold text-slate-400">Productos en Línea (${state.products.length})</h3>
                <div class="grid gap-3">
                    ${state.products.map(p => `
                        <div class="bg-slate-900/40 border border-slate-800 p-3 rounded-2xl flex items-center gap-4 hover:bg-slate-800/40 transition">
                            <img src="${p.imgs[0]}" class="w-16 h-16 object-cover rounded-xl shadow-md">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-white font-medium truncate">${p.name}</h4>
                                <p class="text-slate-500 text-xs">${p.category} | ${p.price ? '$'+p.price : 'Bajo pedido'}</p>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="prepareEdit(${p.id})" class="p-2 text-blue-400 hover:bg-blue-400/10 rounded-lg transition"><i class="fas fa-edit"></i></button>
                                <button onclick="deleteProduct(${p.id})" class="p-2 text-red-400 hover:bg-red-400/10 rounded-lg transition"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </section>
        </div>
    </div>
`;




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
 * Cambia la vista actual (Catalog, Login, Admin).
 * Al cambiar a admin, reseteamos cualquier edición pendiente por seguridad.
 */
window.navigate = (view) => { 
    state.view = view; 
    state.editingId = null; 
    state.searchTerm = ""; // Limpiamos búsqueda al navegar para evitar vistas vacías
    window.scrollTo({ top: 0, behavior: 'smooth' });
    render(); 
};

/**
 * Regresa a la vista principal y limpia filtros.
 * Útil para el logo de DanikatShop.
 */
window.goHome = () => { 
    state.view = 'catalog'; 
    state.searchTerm = ""; 
    state.selectedProduct = null;
    window.scrollTo({ top: 0, behavior: 'smooth' });
    render(); 
};

/**
 * Abre el detalle del producto. 
 * IMPORTANTE: Usamos "==" para comparar ID de BD (number) con posibles strings.
 */
window.openModal = (id) => { 
    const product = state.products.find(p => p.id == id);
    if (product) {
        state.selectedProduct = product;
        // Bloqueamos el scroll del body para que no se mueva el fondo al ver el detalle
        document.body.style.overflow = 'hidden'; 
        render(); 
    }
};

/**
 * Cierra el modal y restaura el scroll.
 */
window.closeModal = () => { 
    state.selectedProduct = null; 
    document.body.style.overflow = 'auto'; 
    render(); 
};

/**
 * Filtra en tiempo real. 
 * No necesita pegarle a la BD en cada tecla porque ya tenemos los datos en state.
 */
window.handleSearch = (val) => { 
    state.searchTerm = val.toLowerCase(); 
    state.currentPage = 1; // Siempre volvemos a la página 1 al buscar
    render(); 
};

/**
 * Cierre de sesión (Extra para mejorar UX de admin)
 */
window.logout = () => {
    if(confirm("¿Cerrar sesión de DanikatShop?")) {
        state.currentUser = null;
        state.view = 'catalog';
        localStorage.removeItem('user_session'); // Si decides persistir el login
        render();
    }
};
// --- MANEJO DE LOGIN CONTRA BD ---
window.handleLogin = async (e) => {
    e.preventDefault();
    const u = e.target.user.value;
    const p = e.target.pass.value;

    try {
        const resp = await fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'login',
                user: u,
                pass: p
            })
        });
        const result = await resp.json();

        if (result.status === 'success') {
            state.currentUser = result.user; // El PHP devuelve nombre y rol
            state.view = 'admin';
        } else {
            alert("Usuario o contraseña incorrectos");
        }
    } catch (error) {
        console.error("Error en login:", error);
        alert("Error de conexión con el servidor");
    }
    render();
};

// --- GUARDAR O ACTUALIZAR EN BD ---
window.handleProductSubmit = async (e) => {
    e.preventDefault();
    
    const productData = {
        name: e.target.name.value,
        price: e.target.price.value || null,
        category: e.target.category.value,
        description: e.target.desc.value,
        images: e.target.imgs.value // Se envía como string de comas
    };

    // Si estamos editando, añadimos el ID para que el PHP sepa qué actualizar
    if (state.editingId) {
        productData.id = state.editingId;
    }

    try {
        const resp = await fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: state.editingId ? 'update_product' : 'add_product',
                content: productData
            })
        });
        const result = await resp.json();

        if (result.status === 'success') {
            alert(state.editingId ? "Producto actualizado" : "Producto agregado");
            state.editingId = null;
            e.target.reset();
            await initApp(); // Recargamos los datos frescos de la BD
        } else {
            alert("Error al guardar: " + result.message);
        }
    } catch (error) {
        console.error("Error:", error);
    }
};

// --- PREPARAR EDICIÓN (Local) ---
window.prepareEdit = (id) => {
    // Buscamos en el estado local el producto para llenar el formulario rápido
    const p = state.products.find(x => x.id == id);
    if (!p) return;

    state.editingId = id;
    render(); // Renderizamos para que el formulario cambie a modo edición

    const f = document.getElementById('product-form');
    f.name.value = p.name;
    f.price.value = p.price || "";
    f.category.value = p.category;
    // Unimos el array de imágenes de nuevo a string para el textarea
    f.imgs.value = Array.isArray(p.imgs) ? p.imgs.join(', ') : p.images;
    f.desc.value = p.description;
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

// --- ELIMINAR DE LA BD ---
window.deleteProduct = async (id) => {
    if (confirm("¿Estás seguro de eliminar este producto permanentemente?")) {
        try {
            const resp = await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'delete_product',
                    id: id
                })
            });
            const result = await resp.json();

            if (result.status === 'success') {
                await initApp(); // Refrescar lista
            } else {
                alert("Error al eliminar");
            }
        } catch (error) {
            console.error("Error:", error);
        }
    }
};

// Iniciar app
// initApp();
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