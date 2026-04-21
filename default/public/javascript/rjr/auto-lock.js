(function() {
    'use strict';
    
    var sessionTimeoutMs = window.APP_SESSION_TIMEOUT || 300000; // 5 min default
    var warningMs = 30000; // 30 segundos antes
    var logoutUrl = '/sistema/login/salir/sesion';
    var mainTimer;
    var warningTimer;
    var isShowingWarning = false;
    
    console.log('Auto-lock: sessionTimeoutMs =', sessionTimeoutMs);
    
    function logout() {
        console.log('Auto-lock: logout triggered');
        window.location.href = logoutUrl + '?reason=timeout';
    }
    
    function showWarning() {
        if (isShowingWarning) return;
        isShowingWarning = true;
        
        console.log('Auto-lock: showing warning');
        
        Swal.fire({
            title: 'Sesión a punto de expirar',
            text: 'Tu sesión expirará en 10 segundos. ¿Deseas mantenerla abierta?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, mantener',
            cancelButtonText: 'No, cerrar',
            timer: 10000,
            timerProgressBar: true
        }).then(function(result) {
            if (result.isConfirmed) {
                console.log('Auto-lock: session extended');
                resetTimers();
                isShowingWarning = false;
                Swal.fire({
                    title: 'Sesión extendida',
                    text: 'Tu sesión ha sido extendida por 1 minuto más.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                logout();
            }
        });
    }
    
    function resetTimers() {
        console.log('Auto-lock: timers reset');
        clearTimeout(mainTimer);
        clearTimeout(warningTimer);
        isShowingWarning = false;
        
        mainTimer = setTimeout(showWarning, sessionTimeoutMs - warningMs);
    }
    
    // Solo ejecutar en páginas del admin
    var path = window.location.pathname;
    var isAdminPage = path.indexOf('/sistema/') !== -1 || 
                      path.indexOf('/dashboard') !== -1 || 
                      path.indexOf('/admin') !== -1;
    
    if (isAdminPage && path.indexOf('/sistema/login') === -1) {
        resetTimers();
        
        // events que resetean el timer
        ['mousedown', 'keydown', 'scroll', 'touchstart', 'mousemove'].forEach(function(event) {
            document.addEventListener(event, function() {
                if (!isShowingWarning) {
                    resetTimers();
                }
            });
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