
/**
 * APP.JS - DANIKATSHOP
 * Carga dinámica desde archivos JSON externos
 */

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
