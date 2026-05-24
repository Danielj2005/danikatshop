

// TODO getIndicators Esta funcion crea los indicadores de un carousel en base a un iterador y un estado
// @param {iterador} i - iterador en caso de ser >= 1
// @param {status} active - estado del indicador
const getIndicators = (i, active) => `
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="${i}" class="${active}" aria-current="${i == 0 ? 'true' : 'false'}" aria-label="Slide ${i + 1}"></button>`;

// TODO getInners Esta funcion crea los inners(imagenes dentro del carousel) en base a una url del array original y un estado
// @param {array} url - array con url de imagenes
// @param {status} active - estado de la imaagen
const getInners = (url, active) => `<div class="carousel-item ${active}"> <img src=".${url}" style="width: 35rem; height:35rem; " class="d-block" alt="..."> </div>`;


// TODO getCarrusel Esta funcion crea un carousel en base a un array de url de imagenes
// @param {array} items - array con url de imagenes
// @param {iterador} i - iterador en caso de ser >= 1
const getCarrusel = (items, i) => `
    <div id="carouselExampleIndicators" class="carousel slide carousel-dark">
        <div class="carousel-inner" id="inner">
            ${items.map((imgUrl) => getInners(imgUrl, i === 0 ? 'active' : ''))}
        </div>
        ${items.length > 1 &&
            (`<button id="indicator_prev" class="text-purple-900 carousel-control-prev carousel-dark" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-primary p-4 rounded-2xl" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button id="indicator_next" class="carousel-control-next carousel-dark" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="bg-primary carousel-control-next-icon p-4 rounded-2xl" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>`)
        }
    </div>
`;