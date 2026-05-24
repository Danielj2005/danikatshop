
const PHONE = "04244189963";

const createCatalogo = (id, nombre, precio, urlImage) =>
    `<div data-categories="" class="product-card product_${id} group bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden hover:border-purple-500/50 transition-all duration-500 animate-slide-up">
        <div class="relative h-64 overflow-hidden cursor-pointer">
            <img src="${urlImage}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            <div class="absolute bottom-0 flex flex-wrap gap-2 items-center">
                <div class="backdrop-blur-md bg-black/60 border border-white/10 bottom-4 left-4 px-4 py-1 relative rounded-full">
                    <span class="text-sm font-bold text-white">${precio >= 1.00 ? "$ "+precio : 'Bajo pedido'}</span>
                </div>
            </div>
        </div>
        <div class="p-6">
            <h3 class="text-white text-md font-semibold mb-1 mb-4">${nombre}</h3>
            <div class="row justify-content-center align-items-center">
                <div class="col-12 mb-3">
                    <form action="./producto.php" method="post">
                        <input type="hidden" value="${id}" name="id" />
                    </form>
                    <button onclick="detallesProductoById(${id})" type="button" class="btn_details w-full bg-slate-800 hover:bg-purple-600 text-white py-3 rounded-2xl transition-all flex items-center justify-center gap-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="bi bi-eye text-lg"></i> <span class="text-sm font-bold">Ver Detalles</span>
                    </button> 
                </div>
                <div class="col-12 mb-3">
                    <button onclick="askWhatsApp('${nombre}', ${precio}, ${PHONE})" 
                        type="submit" class="w-full bg-emerald-800 hover:bg-purple-600 text-white py-3 rounded-3xl transition-all flex items-center justify-center gap-2">
                            <i class="bi bi-whatsapp text-lg fs-3"></i><span class="text-sm font-bold">Consultar por WhatsApp</span>
                    </button>
                </div>
            </div>
        </div>
    </div>`;
