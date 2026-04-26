function SendFormAjax() {

    var MsjErrorSending = `<div class="responseProcess text-white"> <div class="container-loader"> <div class="loader"> <i class="zmdi zmdi-alert-triangle zmdi-hc-5x"></i> </div> <p class="text-center lead text-white">Ocurrio un problema, recargue la página e intente nuevamente o presione F5</p>  </div> </div>`;

    var MsjSending = `<div class="responseProcess text-white">
                        <div class="container-loader">
                            <div class="loader">
                                <svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg>
                            </div>
                            <p class="text-center lead text-white">Procesando... Un momento por favor</p>
                        </div>
                    </div>`;
    
    var MjProcesando= `<div class="responseProcess text-white bg-dark">
                            <div class="container-loader p-5 d-flex justify-content-center align-items-center">
                                <div class="loader"></div>
                            </div>
                            <p class="text-center lead text-white">Procesando... Un momento por favor</p>
                        </div>`;

    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.SendFormAjax');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const metodo = this.getAttribute('method');
                const peticion = this.getAttribute('action');
                const type_form = this.getAttribute('data-type-form');

                const title_alerta = {
                    "save": "¿Quieres almacenar los datos?",
                    "delete": "¿Quieres eliminar los datos?",
                    "update": "¿Quieres actualizar los datos?",
                    "update_estate": "¿Quieres realizar el cambio?"
                };

                const text_alerta = {
                    "save": "Los datos se almacenarán en el sistema",
                    "delete": "Al eliminar estos datos no podrás recuperarlos después",
                    "update": "Los datos se actualizarán y no podrás recuperar los datos anteriores",
                    "update_estate": "Puedes cambiar el estado en cualquier momento"
                };

                const type_alerta = {
                    "save": "info",
                    "delete": "warning",
                    "update": "warning",
                    "update_estate": "warning"
                };

                if(type_form === "load"){
                    fetch(peticion, {
                        method: metodo,
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(data => {
                        document.querySelector('.msjFormSend').innerHTML = data;
                        const loader = document.querySelector('.loader');
                        if (loader) {
                            loader.classList.remove('spinner-border');
                        }
                    })
                    .catch(() => {
                        document.querySelector('.msjFormSend').innerHTML = MsjErrorSending;
                    });
                } else {
                    if (!title_alerta[type_form] || !text_alerta[type_form] || !type_alerta[type_form]) {
                        Swal.fire("¡Ocurrio un error inesperado", "No se reconoce el tipo de formulario: '"+ type_form +"'", "error");
                        return;
                    }

                    Swal.fire({
                        title: title_alerta[type_form],
                        text: text_alerta[type_form],
                        icon: type_alerta[type_form],
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "Sí, continuar",
                        cancelButtonText: "No, cancelar",
                        animation: "slide-from-top"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.querySelector('.msjFormSend').innerHTML = MjProcesando;
                            fetch(peticion, {
                                method: metodo,
                                body: formData
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.text();
                            })
                            .then(data => {
                                document.querySelector('.msjFormSend').innerHTML = data;
                            })
                            .catch(() => {
                                document.querySelector('.msjFormSend').innerHTML = MsjErrorSending;
                            });
                        }
                    });
                }
            });
        });
    });
}

SendFormAjax();
