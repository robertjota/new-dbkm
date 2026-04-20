<?php

/**
 *
 * Descripcion: Controlador que se encarga del logueo de los usuarios del sistema
 *
 * @category
 * @package     Controllers
 * @author      argordmel
 */

Load::lib('security');

class LoginController extends BackendController
{

    /**
     * Limite de parámetros por acción
     * @var boolean
     */
    public $limit_params = FALSE;

    /**
     * Nombre de la página
     * @var string
     */
    public $page_title = 'Entrar';

    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter()
    {
        View::template('backend/login');
    }

    /**
     * Método principal
     */
    public function index()
    {
        return Redirect::toAction('entrar/');
    }

    /**
     * Método para iniciar sesión
     */
    public function entrar()
    {
        if (Input::hasPost('login') && Input::hasPost('password') && Input::hasPost('mode')) {

            if (Usuario::setSession('open', Input::post('login'), Input::post('password'))) {
                return Redirect::to('dashboard/');
            } else {
                //Se soluciona lo de la llave de seguridad
                Flash::error('Los datos introducidos no son correctos.');
                return Redirect::toAction('entrar/', 3);
            }
        } else if (DwAuth::isLogged()) {
            return Redirect::to('dashboard/');
        }
    }

    /**
     * Método para cerrar sesión
     * @param string $js Indica si está deshabilitado el js en el navegador o no
     * @return type
     */
    public function salir($js = '')
    {
        View::select('entrar');
        
        // Guardar tiempo de actividad para estadísticas
        $last_activity = Session::get('last_activity');
        if ($last_activity) {
            $activity_time = time() - $last_activity;
            Flash::info('Tiempo de actividad: ' . round($activity_time/60) . ' minutos');
        }
        
        if (Usuario::setSession('close')) {
            Flash::valid("La sesión ha sido cerrada correctamente.");
        }
        
        if (!empty($js)) {
            Flash::info('Activa el uso de JavaScript en su navegador para poder continuar.');
        }
        
        return Redirect::toAction('entrar/', 3);
    }

    /**
     * Método para bloquear la sesión
     */
    public function bloquear()
    {
        // Guardar que la sesión está bloqueada
        Session::set('session_locked', true);
        Session::set('lock_time', time());
        
        return Redirect::to('dashboard/?locked=true');
    }

    /**
     * Método para desbloquear la sesión
     */
    public function desbloquear()
    {
        if (!Session::get('session_locked')) {
            return Redirect::to('dashboard/');
        }
        
        $password = Input::post('password');
        
        if (empty($password)) {
            Flash::error('Ingrese la contraseña');
            return Redirect::to('dashboard/?locked=true');
        }
        
        // Verificar la contraseña del usuario actual
        $login = Session::get('login');
        
        if ($login && Usuario::setSession('open', $login, $password)) {
            Session::set('session_locked', false);
            Session::delete('lock_time');
            return Redirect::to('dashboard/');
        } else {
            Flash::error('Contraseña incorrecta');
            return Redirect::to('dashboard/?locked=true&error=1');
        }
    }
    
    /**
     * Heartbeat para mantener la sesión activa
     */
    public function heartbeat()
    {
        if (DwAuth::isLogged()) {
            Session::set('last_activity', time());
            Session::set('session_locked', false);
        }
        View::json(['success' => true]);
    }
}