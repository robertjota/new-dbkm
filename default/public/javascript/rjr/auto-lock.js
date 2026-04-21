(function() {
    'use strict';
    
    var sessionTimeoutSec = window.APP_SESSION_TIMEOUT || 1800; // 30 min in seconds
    var logoutUrl = '/sistema/login/salir/sesion';
    var sessionTimer;
    
    function logout() {
        window.location.href = logoutUrl + '?reason=timeout';
    }
    
    function resetTimer() {
        clearTimeout(sessionTimer);
        sessionTimer = setTimeout(logout, sessionTimeoutSec * 1000);
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
            // Mostrar mensaje solo si viene de timeout
        }
    });
    
})();