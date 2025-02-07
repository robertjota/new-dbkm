(function() {
    var inactivityTime = window.AUTO_LOGOUT_TIME || 5 * 60 * 1000; // 5 minutos default
    var warningTime = 60 * 1000; // 1 minuto aviso
    var logoutUrl = '/sistema/login/salir/sesion';
    var loginUrl = '/sistema/login';
    var timer;

    function resetTimer() {
        clearTimeout(timer);
        timer = setTimeout(showWarning, inactivityTime - warningTime);
    }

    function formatTime(milliseconds) {
        const totalSeconds = Math.floor(milliseconds / 1000);
        const minutes = Math.floor(totalSeconds / 60);
        const seconds = totalSeconds % 60;
        return `${minutes}:${seconds.toString().padStart(2, '0')}`;
    }

    function showWarning() {
    Swal.fire({
        title: '¡Atención!',
        html: "Su sesión está a punto de expirar en <b></b>. ¿Desea mantenerla abierta?",
        icon: 'warning',
        timer: 60*1000,
        timerProgressBar: true,
        didOpen: () => {
            const timer = Swal.getPopup().querySelector("b");
            timerInterval = setInterval(() => {
                const timeLeft = Swal.getTimerLeft();
                timer.textContent = formatTime(timeLeft);
            }, 100);
        },
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, mantener abierta',
        cancelButtonText: 'No, cerrar sesión',
        willClose: () => {
            clearInterval(timerInterval);
        },
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

    // Initialize timer
    resetTimer();

    // Events that reset the timer
    ['mousedown', 'keydown', 'scroll', 'touchstart'].forEach(function(event) {
        document.addEventListener(event, resetTimer);
    });

    // Check if session expired when loading the page
    document.addEventListener('DOMContentLoaded', checkSessionExpired);
})();
