
const products = [];
let categorys = [];
let categoriasProductos = [];

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
