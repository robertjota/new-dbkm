(function() {
    var inactivityTime = window.AUTO_LOGOUT_TIME || 5 * 60 * 1000; // 30 minutos por defecto
    var warningTime = 60 * 1000; // 1 minuto de advertencia
    var logoutUrl = '/sistema/login/salir/sesion';
    var loginUrl = '/sistema/login';
    var timer;

    function resetTimer() {
        clearTimeout(timer);
        timer = setTimeout(showWarning, inactivityTime - warningTime);
    }

    function showWarning() {
        Swal.fire({
            title: '¡Atención!',
            text: 'Su sesión está a punto de expirar en 1 minuto. ¿Desea mantenerla abierta?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, mantener abierta',
            cancelButtonText: 'No, cerrar sesión'
        }).then((result) => {
            if (result.isConfirmed) {
                resetTimer();
            } else {
                logout();
            }
        });

        timer = setTimeout(logout, warningTime);
    }

    function logout() {
        window.location.href = logoutUrl;
    }

    function checkSessionExpired() {
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('session_expired') === 'true' && window.location.pathname === loginUrl) {
            Swal.fire({
                title: 'Sesión expirada',
                text: 'Su sesión ha expirado por inactividad. Por favor, inicie sesión nuevamente.',
                icon: 'info',
                confirmButtonText: 'Entendido'
            });
        }
    }

    // Inicializar el temporizador
    resetTimer();

    // Eventos que reinician el temporizador
    ['mousedown', 'keydown', 'scroll', 'touchstart'].forEach(function(event) {
        document.addEventListener(event, resetTimer);
    });

    // Verificar si la sesión expiró al cargar la página
    document.addEventListener('DOMContentLoaded', checkSessionExpired);
})();
