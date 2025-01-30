// Global SweetAlert2 configuration
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    customClass: {
        container: 'main-container-swal',
        popup: 'swal2-popup-custom',
        confirmButton: 'btn btn-alt-success m-1',
        cancelButton: 'btn btn-alt-danger m-1'
    }
});

// Default configuration for all SweetAlert2 dialogs
window.Swal = Swal.mixin({
    customClass: {
        container: 'main-container-swal',
        popup: 'swal2-popup-custom',
        confirmButton: 'btn btn-alt-success m-1',
        cancelButton: 'btn btn-alt-danger m-1'
    },
    buttonsStyling: false
});
