
// TODO getInners Esta funcion crea los inners(imagenes dentro del carousel) en base a una url del array original y un estado
// @param {array} url - array con url de imagenes
// @param {status} active - estado de la imaagen
const getInners = (url, active) => `<div class="carousel-item ${active}"> <img src=".${url}" onerror="this.onerror=null; this.src='./img/404.png';" style="width: 35rem; height:35rem; " class="d-block" alt="..."> </div>`;

const getImgs = (url, active) => `<li class="carousel-slide ${active}">
<img src=".${url}" onerror="this.onerror=null; this.src='./img/404.png';" onerror="this.src='ruta/imagen-no-encontrada.jpg'"></li>`;


/**
 * getCarrusel Esta funcion crea un carousel en base a un array de url de imagenes
 * 
 * @param {array} items - array con url de imagenes
 * @param {iterador} i - iterador en caso de ser >= 1
 * @return {string} - retorna el html del carousel
 * 
 * El carousel se crea con una estructura de lista no ordenada, donde cada imagen es un elemento de la lista. 
 * Se agregan botones de navegación si hay más de una imagen. El carrusel es responsive y se adapta al tamaño del contenedor.
 */

const getCarrusel = (items, i) => `
    <div class="custom-carousel">
        <div class="carousel-track-container">
            <ul class="carousel-track" id="carouselTrack">
                ${items.map((imgUrl) => getImgs(imgUrl, i === 0 ? 'active' : ''))}
            </ul>
        </div>

        ${items.length > 1 ?
            `<button class="carousel-button prev-btn" id="prevBtn">&#10094;</button>
            <button class="carousel-button next-btn" id="nextBtn">&#10095;</button>` : ``
        }
    </div>
`;

function inicializarCarrusel() {
    const track = document.getElementById('carouselTrack');
    const slides = document.querySelectorAll('#carouselTrack li');
    const nextButton = document.getElementById('nextBtn') ?? null;
    const prevButton = document.getElementById('prevBtn') ?? null;
    
    let currentIndex = 0;

    // Función central que mueve el carrusel
    function moverAlSlide(index) {
        // Calculamos el desplazamiento exacto en porcentaje
        const desplazamiento = index * -100;
        track.style.transform = `translateX(${desplazamiento}%)`;
        currentIndex = index;
    }

    // Evento Botón Siguiente
    if (nextButton !== null) {
        nextButton.addEventListener('click', () => {
            let nextIndex = currentIndex + 1;
            // Si llega al final, vuelve al principio de forma infinita
            if (nextIndex >= slides.length) {
            nextIndex = 0;
            }
            moverAlSlide(nextIndex);
        });

    }

    // Evento Botón Anterior
    if (prevButton !== null) {
        prevButton.addEventListener('click', () => {
            let prevIndex = currentIndex - 1;
            // Si retrocede desde el principio, va al final
            if (prevIndex < 0) {
            prevIndex = slides.length - 1;
            }
            moverAlSlide(prevIndex);
        });
    }

    // Opcional: Ajustar el tamaño si la ventana cambia (resposivo nativo)
    window.addEventListener('resize', () => {
        moverAlSlide(currentIndex);
    });
}


// Inicializar cuando el DOM esté listo
// document.addEventListener('DOMContentLoaded', inicializarCarrusel);