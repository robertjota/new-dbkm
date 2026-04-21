(function() {
    'use strict';
    
    // Tiempos en minutos desde config (convertidos a ms)
    var lockTime = (window.APP_LOCK_TIME || 5) * 60 * 1000;
    var sessionTimeout = (window.APP_SESSION_TIMEOUT || 30) * 60 * 1000;
    
    var logoutUrl = '/sistema/login/salir/sesion';
    var lockUrl = '/sistema/login/bloquear';
    var loginUrl = '/sistema/login';
    var lockTimer;
    var sessionTimer;
    var isLocked = false;
    var lockOverlay = null;
    
    function createLockOverlay() {
        if (lockOverlay) return lockOverlay;
        
        lockOverlay = document.createElement('div');
        lockOverlay.id = 'lock-screen';
        lockOverlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        `;
        
        lockOverlay.innerHTML = `
            <div style="text-align: center; color: white; padding: 20px;">
                <i class="fa fa-lock fa-3x mb-4" style="color: #4c78dd;"></i>
                <h2 class="mb-3" style="font-weight: 600;">Sesión Bloqueada</h2>
                <p class="mb-4 text-white-75">Ingresa tu contraseña para continuar</p>
                <form id="unlock-form" method="post" action="/sistema/login/desbloquear/" style="max-width: 300px; margin: 0 auto;">
                    <input type="password" name="password" class="form-control form-control-lg mb-3" 
                           placeholder="Contraseña" required autocomplete="current-password" style="text-align: center;">
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="submit" class="btn btn-lg btn-alt-primary">
                            <i class="fa fa-fw fa-unlock me-1"></i> Desbloquear
                        </button>
                        <a href="${logoutUrl}" class="btn btn-lg btn-alt-danger">
                            <i class="fa fa-fw fa-sign-out-alt me-1"></i> Cerrar
                        </a>
                    </div>
                </form>
            </div>
        `;
        
        document.body.appendChild(lockOverlay);
        return lockOverlay;
    }
    
    function showLockScreen() {
        console.log('Auto-lock: Mostrando pantalla de bloqueo');
        // Mostrar también un alert para debugging
        alert('¡Pantalla de bloqueo activada! LockTime: ' + lockTime + 'ms');
        isLocked = true;
        clearTimeout(lockTimer);
        clearTimeout(sessionTimer);
        createLockOverlay();
        lockOverlay.style.display = 'flex';
    }
    
    function hideLockScreen() {
        isLocked = false;
        if (lockOverlay) {
            lockOverlay.style.display = 'none';
        }
        resetTimers();
    }
    
    function resetTimers() {
        clearTimeout(lockTimer);
        clearTimeout(sessionTimer);
        
        // Timer para bloqueo
        lockTimer = setTimeout(showLockScreen, lockTime);
        
        // Timer para cierre de sesión (después del lock)
        sessionTimer = setTimeout(function() {
            window.location.href = logoutUrl + '?reason=timeout';
        }, sessionTimeout);
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
        
        if (urlParams.get('locked') === 'true') {
            showLockScreen();
        }
    }
    
    // Solo ejecutar en páginas del admin (no en login)
    var path = window.location.pathname;
    var isAdminPage = path.indexOf('/sistema/') !== -1 || 
                  path.indexOf('/dashboard') !== -1 || 
                  path.indexOf('/admin') !== -1 ||
                  path.indexOf('/backend') !== -1;
    
    if (isAdminPage && path.indexOf('/sistema/login') === -1) {
        console.log('Auto-lock: Activado para', path);
        resetTimers();
        
        // Events that reset the timers (actividad del usuario)
        ['mousedown', 'keydown', 'scroll', 'touchstart', 'mousemove'].forEach(function(event) {
            document.addEventListener(event, function() {
                if (!isLocked) {
                    resetTimers();
                }
            });
        });
        
        // Interceptar el formulario de unlock
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var unlockForm = document.getElementById('unlock-form');
                if (unlockForm) {
                    unlockForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        var password = unlockForm.querySelector('input[name="password"]').value;
                        
                        fetch('/sistema/login/desbloquear/', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'password=' + encodeURIComponent(password)
                        })
                        .then(function(response) {
                            if (response.ok) {
                                hideLockScreen();
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Contraseña incorrecta',
                                    icon: 'error',
                                    confirmButtonText: 'Intentar de nuevo'
                                });
                            }
                        })
                        .catch(function() {
                            // Si falla el fetch, hacer submit normal
                            unlockForm.submit();
                        });
                    });
                }
            }, 100);
        });
    }
    
    // Verificar si la sesión expiró al cargar la página
    document.addEventListener('DOMContentLoaded', checkSessionExpired);
    
})();