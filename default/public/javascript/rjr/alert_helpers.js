function alertSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Exito',
        text: message,
        showConfirmButton: false,
        timer: 2000
    });
}

function alertError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        html: message,
        showConfirmButton: false,
        timer: 2000
    });
}
function alertWarning(message) {
    Swal.fire({
        icon: 'warning',
        title: 'Advertencia',
        html: message,
        showConfirmButton: false,
        timer: 2000
    });
}

function alertConfirm(message, callback) {
    Swal.fire({
        title: '¿Está seguro?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}
