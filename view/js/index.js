
const products = [];
let categorys = [];
let categoriasProductos = [];
const FILTERS = [];

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
window.filterByCategory = async (categoryName, ID, page = 1) => {
    const products = document.querySelectorAll('.product-card');
    const buttons = document.querySelectorAll('.category-btn');
    const filtersActive = document.getElementById('num_filter');

    // Agregar el filtro a la lista de filtros activos
    if (categoryName !== 'all') {

        if (!FILTERS.includes(categoryName)) {
            FILTERS.push(categoryName);
            document.getElementById(`dropdown-item-${ID}`).classList.add('bg-purple-600');

        }else{
            
            document.getElementById(`dropdown-item-${ID}`).classList.remove('bg-purple-600');
            let index = FILTERS.indexOf(categoryName);
            if (index > -1) {
                FILTERS.splice(index, 1); // Elimina el filtro del array
            }
        }

    }else{

        // Si se selecciona "Todos", limpiar todos los filtros
        FILTERS.length = 0; // Limpia el array de filtros
        // Actualizar estilos de los botones para reflejar que "Todos" está activo
        document.getElementById('dropdown-item-all').classList.add('bg-purple-600');

        document.querySelectorAll('#category-filters .dropdown-item').forEach(item => {
            if (item.id !== 'dropdown-item-all') {
                item.classList.remove('bg-purple-600');
            }
        });
    }

    if (FILTERS.length > 0) {

        filtersActive.textContent = FILTERS.length;
        filtersActive.classList.remove('d-none');

    } else {
        filtersActive.classList.add('d-none');
    }
    

    // Actualizar estilos visuales de los botones de filtro

    try {
        let response;
        const per_page = 16;
        if (FILTERS.length < 1) {
            response = await fetch(`./controller/catalogo.php?page=${page}&per_page=${per_page}`);
        
        }else {
            // Consultamos al PHP que trae los datos de MySQL
            response = await fetch('./controller/catalogo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ filter: FILTERS, page: page, per_page: per_page })
            });
        }

        if (!response.ok) throw new Error("Error en la petición");

        const data = await response.json();
        if (data.status === "success") {

            // data.productos ya viene como objeto si ajustaste el PHP
            const productos = typeof data.productos === 'string' ? JSON.parse(data.productos) : data.productos;
            
            renderCatalogo(productos);
            if (typeof renderPagination === 'function') {
                renderPagination(data.total, data.per_page, data.page);
            }

        }else{
            
            document.getElementById('main').innerHTML = ``;
        }

    } catch (error) {
        console.error("Error al cargar los productos:", error);
    }
};


/**
 * Procesa el texto recibido desde el chatbot de WhatsApp,
 * extrae los datos del producto y los envía a la API en InfinityFree.
 * 
 * @param {string} nombre - El texto crudo del mensaje de WhatsApp.
 * @param {float} precio - El texto crudo del mensaje de WhatsApp.
 * @param {number} numeroWhats - El texto crudo del mensaje de WhatsApp.
 */

// --- LÓGICA DE WHATSAPP ---
window.askWhatsApp = (nombre, precio, numeroWhats) => {

    const numeroLimpio = numeroWhats;
    
    const precioTxt = precio ? `por un valor de *$${precio}*` : "";
    const msg = `¡Hola DanikatShop! Me interesa su producto:\n\n*${nombre}*\n\n${precioTxt}\n\n¿Podrían darme más detalles?`;
    
    const url = `https://api.whatsapp.com/send?phone=${numeroLimpio}&text=${encodeURIComponent(msg)}`;

    // 2. Detectar si es móvil para usar una redirección más agresiva
    const isMobile = /iPhone|Android/i.test(navigator.userAgent);

    if (isMobile) {
        // En móviles, mejor cambiar la ubicación de la pestaña actual
        window.location.href = url;
    } else {
        // En PC, abrimos pestaña nueva
        window.open(url, '_blank');
    }
};


// inicializacion de funciones

document.addEventListener('DOMContentLoaded', () => {
    
    if (document.getElementById('loader')) {
        setTimeout(() => {
            document.getElementById('loader').style.display = 'none';
            if (document.getElementById('app')) {
                document.getElementById('app').style.display = 'block';
            }
        }, 1500);
    }
    getCatalogo()
});
