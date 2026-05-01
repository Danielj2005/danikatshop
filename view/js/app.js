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





let estado = {
    loading: true, // Empezamos cargando
    productState: true,
    selectedProduct: null,
    editingId: null
};



const saveToStorage = async () => {
    // 1. Guardar localmente (Persistencia inmediata para el admin)
    localStorage.setItem('products', JSON.stringify(state.products));
    localStorage.setItem('users', JSON.stringify(state.users));

};


// --- LÓGICA DE WHATSAPP ---
window.askWhatsApp = (nombre, precio, numeroWhats) => {

    const numeroLimpio = numeroWhats.replace(/\D/g, '');
    
    const precioTxt = precio ? `por un valor de *$${precio}*` : "(Precio a convenir según pedido)";
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

let tableActiveHtml = ``;
let tableInactiveHtml = ``;


async function getProductos() {
    try {
        // Consultamos al PHP que trae los datos de MySQL
        const active = await fetch(`../controller/listaProductos.php?UID=${1}`);
        const data = await active.text();

        const inactive = await fetch(`../controller/listaProductos.php?UID=${0}`);
        const inact = await inactive.text();

        // let tableActive = document.getElementById('activos');
        let tbodyActive = document.querySelector('#activos tbody');
        let tbodyInactive = document.querySelector('#inactivos tbody');

        tbodyActive.innerHTML = data;
        tbodyInactive.innerHTML = inact;

        tableActiveHtml = tbodyActive.innerHTML; // Guardamos el HTML original para futuras actualizaciones
        tableInactiveHtml = tbodyInactive.innerHTML; // Guardamos el HTML original para futuras actualizaciones

        dataTable("tableActivos");
        dataTable("tableInactivos");
        SendFormAjax();

        // document.getElementById('activos').innerHTML = data;
        // document.getElementById('inactivos').innerHTML = data;
        // dataTable("tableInactivos");

    } catch (error) {
        console.error("Fallo de conexión con BD:", error);
    }
}

function changeState () {
        
    const btn = document.getElementById('btnChangeState');
        
    if (estado.productState) {
        document.getElementById('inactivos').classList.remove('d-none');
        document.getElementById('activos').classList.add('d-none');
        btn.textContent = "Productos inactivos";
        estado.productState = false;
    }else{
        btn.textContent = "Productos activos";
        document.getElementById('activos').classList.remove('d-none');
        document.getElementById('inactivos').classList.add('d-none');
        estado.productState = true;
    }
    

}


document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('loader')) {
        setTimeout(() => {
            document.getElementById('loader').style.display = 'none';
            if (document.getElementById('app')) {
                document.getElementById('app').style.display = 'block';
            }
        }, 1500);
    }

    if (index) {
        getProductos();
    } 
});






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


function handleLogin() {
    
    try {
        
        const usuario = document.getElementById('user').value;
        const contraseña = document.getElementById('pass').value;
        // Consultamos al PHP que trae los datos de MySQL
        $.ajax({
            type: "POST",
            url: "./controller/login.php",
            data: {user: usuario, pass: contraseña}, // Usa el objeto FormData en lugar de $(this).serialize(),
            error: function () {
                Swal.fire("¡Ocurrio un error inesperado", "No se pudo realizar la operación.", "error");
            },
            success: function (data) {
                $('.msjFormSend').html(data);
            }
        });


    } catch (error) {
        console.error("Fallo de conexión con BD:", error);
    }
}
