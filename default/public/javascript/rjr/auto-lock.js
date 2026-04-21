(function() {
    'use strict';
    
    var sessionTimeoutSec = window.APP_SESSION_TIMEOUT || 300; // 5 min in seconds
    var warningSec = 30; // 30 segundos antes
    var logoutUrl = '/sistema/login/salir/sesion';
    var mainTimer;
    var warningTimer;
    var isShowingWarning = false;
    
    console.log('Auto-lock: sessionTimeoutSec =', sessionTimeoutSec);
    
    function logout() {
        console.log('Auto-lock: logout triggered');
        window.location.href = logoutUrl + '?reason=timeout';
    }
    
    function showWarning() {
        if (isShowingWarning) return;
        isShowingWarning = true;
        
        console.log('Auto-lock: showing warning after', sessionTimeoutSec - warningSec, 'seconds');
        
        var timeLeft = warningSec;
        var timerInterval = setInterval(function() {
            timeLeft--;
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                logout();
            }
        }, 1000);
        
        Swal.fire({
            title: 'Sesión a punto de expirar',
            text: 'Tu sesión expirará en ' + warningSec + ' segundos. ¿Deseas mantenerla abierta?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, mantener',
            cancelButtonText: 'No, cerrar',
            timer: warningSec * 1000,
            timerProgressBar: true,
            didOpen: function() {
                var timer = Swal.getPopup().querySelector('b');
                if (timer) {
                    var interval = setInterval(function() {
                        timer.textContent = timeLeft;
                    }, 1000);
                }
            }
        }).then(function(result) {
            clearInterval(timerInterval);
            if (result.isConfirmed) {
                console.log('Auto-lock: session extended');
                resetTimers();
                isShowingWarning = false;
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
    }
    
    function resetTimers() {
        console.log('Auto-lock: timers reset to', sessionTimeoutSec, 'seconds');
        clearTimeout(mainTimer);
        clearTimeout(warningTimer);
        isShowingWarning = false;
        
        mainTimer = setTimeout(showWarning, (sessionTimeoutSec - warningSec) * 1000);
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