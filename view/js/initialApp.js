// inicializar la libreria Select2 
$('.select2').select2();



const getIndicators = (i, active) => `
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="${i}" class="${active}" aria-current="${i == 0 ? 'true' : 'false'}" aria-label="Slide ${i + 1}"></button>
`;

const getInners = (file, active) => `
    <div class="carousel-item ${active}">
        <img src=".${file}" style="width: 35rem; height:35rem; " class="d-block" alt="...">
    </div>
`;

const getCarrusel = (items, i) => `
    <div id="carouselExampleIndicators" class="carousel slide carousel-dark">

        <div class="carousel-inner" id="inner">
            ${items.map((img) => getInners(img, i === 0 ? 'active' : ''))}
        </div>
        ${items.length > 1 
        ?
            `<button id="indicator_prev" class="text-purple-900 carousel-control-prev carousel-dark" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-primary p-4 rounded-2xl" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button id="indicator_next" class="carousel-control-next carousel-dark" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="bg-primary carousel-control-next-icon p-4 rounded-2xl" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>` 
        : 
            ``
        }
    </div>
`;

function verImagen(url, producto) {
    let i = 0;

    const data = url.split(",");

    DanikatAlert.fire({
        title: `${producto}`,
        html: getCarrusel(data, i),
        confirmButtonText: 'Cerrar'
    });
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

// Ejecutar al cargar la página o el modal
document.addEventListener('DOMContentLoaded', initCustomSelect);