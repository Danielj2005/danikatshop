function SendFormAjax() {

    
    $('.SendFormAjax').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this); // Crea un objeto FormData con los datos del formulario

        var metodo = $(this).attr('method');
        var peticion = $(this).attr('action');
        var type_form = $(this).attr('data-type-form');
        var procesando = $(this).attr('procesando');

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
            // No modification needed for "load" type
            $.ajax({
                type: metodo,
                url: peticion,
                data: formData, // Usa el objeto FormData en lugar de $(this).serialize(),
                processData: false, // Evita que jQuery procese los datos
                contentType: false, // Evita que jQuery establezca el tipo de contenido
                beforeSend: function(){
                    Swal.fire({
                        title: "Procesando...",
                        text: "",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                error: function() {
                    Swal.fire({
                        title: "¡Ocurrio un problema!",
                        text: "Recargue la página e intente nuevamente o presione F5",
                        icon: "error"
                    });
                }, 
                success: function (data) {
                    $('.msjFormSend').html(data);
                }
            });
            return false;
        } else {

            if (title_alerta[`${type_form}`] == false && text_alerta[`${type_form}`] == false && type_alerta[`${type_form}`] == false) {
                Swal.fire("¡Ocurrio un error inesperado", "No se reconoce el tipo de formulario: '"+ type_form +"'", "error");
                return;
            }

            DanikatAlert.fire({
                title: title_alerta[`${type_form}`],
                text: text_alerta[`${type_form}`],
                icon: type_alerta[`${type_form}`],
                showCancelButton: true,
                confirmButtonColor: "#3085d6", // Default SweetAlert2 blue
                confirmButtonText: "Sí, continuar",
                cancelButtonText: "No, cancelar",
                animation: "slide-from-top"
            }).then((result) => {
                if (result.isConfirmed) {
                    // el usuario confirme la acción
                    DanikatAlert.fire({
                        title: "Procesando...",
                        text: "",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        type: metodo,
                        url: peticion,
                        data: formData, // Usa el objeto FormData en lugar de $(this).serialize(),
                        processData: false, // Evita que jQuery procese los datos
                        contentType: false, // Evita que jQuery establezca el tipo de contenido
                        error: function () {
                            Swal.fire({
                                title: "¡Ocurrio un problema!",
                                text: "Recargue la página e intente nuevamente o presione F5",
                                icon: "error"
                            });
                        },
                        success: function (data) {
                            Swal.close();
                            $('.msjFormSend').html(data);
                        }
                    });
                }
            });
        }
    });
}

$(document).ready(function () {
    // SendFormAjax();
});
