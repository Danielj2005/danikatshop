
const PHONE = "04244189963";

const createCatalogo = (id, nombre, precio, urlImage) =>
    `<div data-categories="" class="product-card product_${id} group bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden hover:border-purple-500/50 transition-all duration-500 animate-slide-up">
        <div class="relative overflow-hidden cursor-pointer" style="height: 15rem;">
            <img src="${urlImage}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            <div class="absolute bottom-0 flex flex-wrap gap-2 items-center">
                <div class="backdrop-blur-md bg-black/60 border border-white/10 bottom-4 left-4 px-4 py-1 relative rounded-full">
                    <span class="text-sm font-bold text-white">${precio >= 1.00 ? "$ "+ precio : 'Bajo pedido'}</span>
                </div>
            </div>
        </div>
        <div class="p-3">
            <div class="">
                <button onclick="detallesProductoById(${id})" class="text-sm mb-3 text-white font-semibold" data-bs-toggle="modal" data-bs-target="#exampleModal">${nombre}</button>
            </div>
            <div class="row justify-content-center align-items-center">
                <div class="col-12 mb-3">
                    <button onclick="detallesProductoById(${id})" type="button" class="btn_details w-full bg-slate-800 hover:bg-purple-600 text-white p-2 rounded-2xl transition-all gap-2 flex items-center justify-center " data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="bi bi-eye text-lg"></i> <span class="d-none d-md-block text-sm font-bold">Ver Detalles</span>
                    </button> 
                </div>
                <div class="col-12 mb-2">
                    <button onclick="askWhatsApp('${nombre}', ${precio}, ${PHONE})" 
                        type="submit" class="w-full bg-emerald-800 hover:bg-purple-600 text-white p-2 rounded-3xl transition-all flex items-center justify-center gap-2">
                            <i class="bi bi-whatsapp text-lg"></i> <span class="d-none d-md-block text-sm font-bold">Consultar por WhatsApp</span>
                    </button>
                </div>
            </div>
        </div>
    </div>`;


async function getCatalogo(page = 1) {
    try {
        const withoutProducts = `<div class="grid grid-cols-1 gap-4"> 
        <div class="bg-red-700 border border-slate-800 rounded-[2rem] transition-all duration-500 animate-slide-up">
            <div class="p-4 text-center"> 
                <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                <h3 class="h1 text-center text-white font-semibold mb-1 truncate mb-4">En este momento no hay productos disponibles.</h3> 
            </div> </div> </div>`;

        const per_page = 16;

        // Consultamos al PHP que trae los datos de MySQL
        const response = await fetch(`./controller/catalogo.php?page=${page}&per_page=${per_page}`);
        if (!response.ok) throw new Error("Error en la petición");

        const data = await response.json();
        if (data.status === "success") {

            // data.productos ya viene como objeto si ajustaste el PHP
            let productos = typeof data.productos === 'string' ? JSON.parse(data.productos) : data.productos;

            products.push(productos);
            categorys = data.categorias;

            const filtroCategorias = document.getElementById('category-filters');
            // Evitar duplicar opciones si ya se cargaron
            if (!filtroCategorias.dataset.inited) {
                categorys.forEach(categoria => {
                    const li = document.createElement('li');
                    li.className = "btn dropdown-item transition-all hover:bg-purple-500/20";
                    li.id = `dropdown-item-${categorys.indexOf(categoria)+1}`;

                    const btn = document.createElement('button');
                    btn.className = "category-btn btn";
                    btn.textContent = categoria;
                    btn.addEventListener('click', () => filterByCategory(categoria, categorys.indexOf(categoria)+1, 1));

                    li.appendChild(btn);
                    filtroCategorias.appendChild(li);
                });
                filtroCategorias.dataset.inited = '1';
            }

            // render
            renderCatalogo(productos);

            // render pagination
            renderPagination(data.total, data.per_page, data.page);
            
        }else{
            
            document.getElementById('main').innerHTML = withoutProducts;
        }

    } catch (error) {
        console.error("Error al cargar los productos:", error);
    }
}


function renderPagination(total, per_page, page) {
    const totalPages = Math.max(1, Math.ceil(total / per_page));

    const existing = document.getElementById('catalog-pagination');
    if (existing) existing.remove();

    const container = document.createElement('div');
    container.id = 'catalog-pagination';
    container.className = 'w-full flex justify-center items-center gap-2 my-6';

    const prev = document.createElement('button');
    prev.className = 'btn btn-secondary';
    prev.textContent = 'Anterior';
    prev.disabled = page <= 1;
    prev.addEventListener('click', () => getCatalogo(page - 1));
    container.appendChild(prev);

    // crear botones de páginas (limitar rango)
    const maxButtons = 7;
    let start = Math.max(1, page - Math.floor(maxButtons / 2));
    let end = Math.min(totalPages, start + maxButtons - 1);
    if (end - start < maxButtons - 1) {
        start = Math.max(1, end - maxButtons + 1);
    }

    for (let p = start; p <= end; p++) {
        const btn = document.createElement('button');
        btn.className = p === page ? 'btn btn-primary' : 'btn btn-outline-primary';
        btn.textContent = p;
        btn.addEventListener('click', () => getCatalogo(p));
        container.appendChild(btn);
    }

    const next = document.createElement('button');
    next.className = 'btn btn-secondary';
    next.textContent = 'Siguiente';
    next.disabled = page >= totalPages;
    next.addEventListener('click', () => getCatalogo(page + 1));
    container.appendChild(next);

    document.getElementById('main').appendChild(container);
}


const detallesProductoById = async (id) => {
    try {
        const modalBody = document.getElementById('modalBody');

        modalBody.innerHTML = ``;

        const resp = await fetch(`./controller/producto.php?UID=${id}&details=true`);
        const detallesProducto = await resp.text();
        
        modalBody.innerHTML = detallesProducto;

    } catch (error) {
        console.error("No se pudo obtener los detallse del producto:", error);
    }
};
