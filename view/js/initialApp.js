
function verImagen(url, producto) {
    let i = 0;

    const data = url.split(",");

    DanikatAlert.fire({
        title: `${producto}`,
        html: getCarrusel(data, i),
        confirmButtonText: 'Cerrar'
    });
    inicializarCarrusel();
}


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


function initCustomSelect() {
    const select = document.getElementById('categoryMultiSelect');
    const container = document.getElementById('tag-container');
    
    // 1. Generar etiquetas basadas en las opciones del select
    Array.from(select.options).forEach(option => {
        const tag = document.createElement('span');
        tag.textContent = option.text;
        tag.dataset.value = option.value;
        
        // Estilo base de la etiqueta (DanikatStyle)
        tag.className = 'cursor-pointer px-4 py-1 rounded-full border border-purple-500/50 text-slate-300 transition-all hover:bg-purple-500/20';
        
        // Evento al hacer clic
        tag.onclick = () => {
            option.selected = !option.selected; // Alternar selección en el select oculto
            
            // Alternar estilos visuales
            if (option.selected) {
                tag.classList.remove('border-purple-500/50', 'text-slate-300');
                tag.classList.add('bg-purple-600', 'text-white', 'border-purple-600', 'shadow-[0_0_10px_rgba(168,85,247,0.5)]');
            } else {
                tag.classList.add('border-purple-500/50', 'text-slate-300');
                tag.classList.remove('bg-purple-600', 'text-white', 'border-purple-600', 'shadow-[0_0_10px_rgba(168,85,247,0.5)]');
            }
        };
        
        container.appendChild(tag);
    });
}

/**
 * Copia cualquier texto al portapapeles en Desktop y Mobile.
 * @param {string} text - El texto que se desea copiar.
 * @returns {Promise<boolean>} - Devuelve true si se copió con éxito.
 */
async function copyToClipboard(text) {
    // Método 1: API Moderna (Navigator Clipboard)
    if (navigator.clipboard && window.isSecureContext) {
        try {
            await navigator.clipboard.writeText(text);
            Toastify({
                text: "Precio Copiado correctamente",
                duration: 3000,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                    background: "linear-gradient(to right, #370863, #870873)",
                }
            }).showToast();
            return true;
        } catch (err) {
            console.warn("Fallo con navigator.clipboard, usando fallback...", err);
        }
    }
}



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

// Ejecutar al cargar la página o el modal
document.addEventListener('DOMContentLoaded', () => {
    initCustomSelect();
    getProductos();

    
});
