
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
                    <button onclick="detallesProductoById(${id})" type="button" class="btn_details w-full bg-slate-800 hover:bg-purple-600 text-white p-2 rounded-2xl transition-all flex items-center justify-center " data-bs-toggle="modal" data-bs-target="#exampleModal">
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


async function getCatalogo() {
    try {
        const withoutProducts = `<div class="grid grid-cols-1 gap-4"> 
        <div class="bg-red-700 border border-slate-800 rounded-[2rem] transition-all duration-500 animate-slide-up">
            <div class="p-4 text-center"> 
                <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                <h3 class="h1 text-center text-white font-semibold mb-1 truncate mb-4">En este momento no hay productos disponibles.</h3> 
            </div> </div> </div>`;

        // Consultamos al PHP que trae los datos de MySQL
        const response = await fetch('./controller/catalogo.php');
        if (!response.ok) throw new Error("Error en la petición");

        const data = await response.json();
        if (data.status === "success") {

            // data.productos ya viene como objeto si ajustaste el PHP
            let productos = typeof data.productos === 'string' ? JSON.parse(data.productos) : data.productos;
            categoriasProductos.push(JSON.parse(data.productosCategorias));

            products.push(productos);
            categorys = data.categorias;

            const filtroCategorias = document.getElementById('category-filters');
            categorys.forEach(categoria => {
                const li = document.createElement('li');
                li.className = "dropdown-item";

                const btn = document.createElement('button');
                btn.className = "category-btn px-4 py-1.5 rounded-full border border-purple-500/50 text-slate-300 text-sm transition-all hover:bg-purple-500/20";
                btn.textContent = categoria;
                btn.addEventListener('click', () => filterByCategory(categoria));

                li.appendChild(btn);
                filtroCategorias.appendChild(li);
            });

            // .join('') elimina las comas entre las cards
            document.getElementById('cards').innerHTML = productos.map((p) => createCatalogo(p.id, p.nombre, p.precio, p.images, p.categorias)).join('');
            
        }else{
            
            document.getElementById('main').innerHTML = withoutProducts;
        }

    } catch (error) {
        console.error("Error al cargar los productos:", error);
    }
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
