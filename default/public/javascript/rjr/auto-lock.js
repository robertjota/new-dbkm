(function() {
    'use strict';
    
    var sessionTimeoutSec = window.APP_SESSION_TIMEOUT || 1800; // 30 min in seconds
    var warningSec = 60; // 1 minute warning before timeout
    var logoutUrl = '/sistema/login/salir/sesion';
    var sessionTimer;
    var warningTimer;
    var timerInterval;
    
    function logout() {
        window.location.href = logoutUrl + '?reason=timeout';
    }
    
    function showWarning() {
        warningTimer = setTimeout(function() {
            var timeLeft = warningSec;
            
            var interval = setInterval(function() {
                timeLeft--;
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    logout();
                }
            }, 1000);
            
            Swal.fire({
                title: 'Sesión a punto de expirar',
                html: 'Tu sesión expirará en <b>' + warningSec + '</b> segundos. ¿Deseas mantenerla abierta?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, mantener abierta',
                cancelButtonText: 'No, cerrar sesión',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    resetTimer();
                    Swal.fire({
                        title: 'Sesión extendida',
                        text: 'Tu sesión ha sido extendida.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    logout();
                }
            });
        }, (sessionTimeoutSec - warningSec) * 1000);
    }
    
    function resetTimer() {
        clearTimeout(sessionTimer);
        clearTimeout(warningTimer);
        clearInterval(timerInterval);
        
        sessionTimer = setTimeout(showWarning, sessionTimeoutSec * 1000);
    }
    
    // Solo ejecutar en páginas del admin
    var path = window.location.pathname;
    var isAdminPage = path.indexOf('/sistema/') !== -1 || 
                      path.indexOf('/dashboard') !== -1 || 
                      path.indexOf('/admin') !== -1;
    
    if (isAdminPage && path.indexOf('/sistema/login') === -1) {
        resetTimer();
        
        // events que resetean el timer
        ['mousedown', 'keydown', 'scroll', 'touchstart', 'mousemove'].forEach(function(event) {
            document.addEventListener(event, resetTimer);
        });
    }
    
    // Verificar si la sesión expiró al cargar
    document.addEventListener('DOMContentLoaded', function() {
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('reason') === 'timeout') {
            Swal.fire({
                title: 'Sesión expirada',
                text: 'Tu sesión ha expirado por inactividad.',
                icon: 'info',
                confirmButtonText: 'Entendido'
            });
        }
    });
    
})();