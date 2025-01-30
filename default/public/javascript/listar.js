$(document).ready(function() {
        $('.status-toggle').on('click', function() {
            let button = $(this);
            let userId = button.data('user-id');
            let currentStatus = button.data('status');
            let securityKey = button.data('key');
            let newStatus = currentStatus == 1 ? 2 : 1;

            // Confirm dialog with OneUI style
            Swal.fire({
                title: '¿Estás seguro?',
                text: newStatus == 1 ? "¿Deseas activar este usuario?" : "¿Deseas bloquear este usuario?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'btn btn-alt-success m-1',
                    cancelButton: 'btn btn-alt-danger m-1'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/sistema/usuarios/cambiarEstado/' + securityKey + '/',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            estado: newStatus
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                if (newStatus == 1) {
                                    button.html('<span class="badge bg-success">Activo</span>');
                                } else {
                                    button.html('<span class="badge bg-danger">Bloqueado</span>');
                                }
                                button.data('status', newStatus);

                                // Success message with OneUI style
                                Swal.fire({
                                    title: '¡Completado!',
                                    text: response.message,
                                    icon: 'success',
                                    customClass: {
                                        confirmButton: 'btn btn-alt-success m-1'
                                    },
                                    buttonsStyling: false,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function() {
                            // Error message with OneUI style
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al cambiar el estado',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-alt-danger m-1'
                                },
                                buttonsStyling: false
                            });
                        }
                    });
                }
            });
        });
    });
