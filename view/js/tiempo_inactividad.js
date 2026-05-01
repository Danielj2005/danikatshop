

const tiempo_config = 10 * 60 * 1000; // Convertimos minutos a milisegundos

function detectar_actividad() {
    let tiempo_id; // Almacena el ID del temporizador de inactividad
    let advertencia_tiempo_id; // Almacena el ID del temporizador de advertencia

    function resetear_temporizador() {
        clearTimeout(tiempo_id);
        clearTimeout(advertencia_tiempo_id); // Limpia también el temporizador de advertencia si existía
        tiempo_id = setTimeout( mostrar_advertencia, tiempo_config);
    }

    function mostrar_advertencia() {

        const tiempo_advertencia = 30000;
        
        DanikatAlert.fire({
            title: '¡Sesión por expirar!',
            html: `Se cerrará tu sesión por inactividad en <b></b> segundos.`,
            icon: 'warning',
			showCancelButton: true,
			confirmButtonText: "Seguir aquí!",
            showCancelButton: true,
			animation: "slide-from-top",
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cerrar sesión ahora',
            allowOutsideClick: false,
            allowEscapeKey: false, 
            timer: tiempo_advertencia, 
            timerProgressBar: true,
            didOpen: () => {
                const b = Swal.getHtmlContainer().querySelector('b');
                // Actualizamos el número cada 100ms para que sea fluido
                timerInterval = setInterval(() => {
                    // Math.ceil convierte los ms restantes en segundos
                    b.textContent = Math.ceil(Swal.getTimerLeft() / 1000);
                }, 1000);

                advertencia_tiempo_id = setTimeout(() => {
                    location.href = "../controller/logout.php"; 
                }, tiempo_advertencia);
            },

            willClose: () => {
                clearInterval(timerInterval);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                resetear_temporizador();
            }  else if (result.dismiss === Swal.DismissReason.timer || result.isDenied || result.dismiss === Swal.DismissReason.cancel){
                location.href = "../controller/logout.php"; 
            }
        });

    }

    // Eventos que reinician el temporizador de inactividad
    const events = ['mousemove', 'mousedown', 'keypress', 'scroll', 'touchstart'];
    events.forEach(event => {
        document.addEventListener(event, resetear_temporizador);
    });
    // Inicia el temporizador cuando se carga la página
    resetear_temporizador();
}

document.addEventListener('DOMContentLoaded', () => {
    detectar_actividad(); 
});