<?php

/**
 *
 * Clase que se utiliza para autenticar los usuarios
 *
 * @category    Sistema
 * @package     Libs
 */

Load::lib('auth2');
Load::models('sistema/usuario');

//Cambiar la key por una propia
//Se puede utilizar las de wordpress: https://api.wordpress.org/secret-key/1.1/salt/
define('SESSION_KEY', 'MXKocr!!GVeO{ocb$%[7QK=8{]oozs]5D;ngQ^yz+0-O9H#>d9BD!&u.<Yg3$u~=');

class DwAuth
{

    /**
     * Mensaje de Error
     *
     * @var String
     */
    protected static $_error = null;

    /**
     * Método para iniciar Sesion
     *
     * @param $username mixed Array con el nombre del campo en la bd del usuario y el valor
     * @param $password mixed Array con el nombre del campo en la bd de la contraseña y el valor
     * @return true/false
     */
    public static function login($fieldUser, $fieldPass)
    {
        //Verifico si tiene una sesión válida
        if (self::isLogged()) {
            return true;
        } else {

//Verifico si envía el array array('usuario'=>'admin') o string 'usuario'
            $keyUser = (is_array($fieldUser)) ? @array_shift(array_keys($fieldUser)) : NULL;
            $keyPass = (is_array($fieldPass)) ? @array_shift(array_keys($fieldPass)) : NULL;
            $valUser = ($keyUser) ? $fieldUser[$keyUser] : NULL;
            $valPass = ($keyPass) ? $fieldPass[$keyPass] : NULL;

            if (empty($valUser) or empty($valPass)) {
                self::setError("Ingresa el usuario y contraseña");
                return false;
            }

            $usuario = new Usuario();
            // Check both 'A' and 1 (numeric) for active state
            $result = $usuario->find_first("login = '$valUser' AND (estado = 'A' OR estado = 1)");
            if (!$result) {
                $result = $usuario->find_first("login = '$valUser'");
            }
            
            if (!$result) {
                // Try without estado filter
                $result2 = $usuario->find_first("login = '$valUser'");
                self::setError("DEBUG: found WITHOUT estado filter=$result2");
                if ($result2) {
                    self::setError("DEBUG: usuario existe pero estado no es 'A' - estado actual=" . $usuario->estado);
                }
            }
            
            if ($result) {
                $hash = $usuario->password;
                $valid = false;
                
                // Check if it's bcrypt (starts with $2a$, $2b$, $2y$ or $2x$) - 60 chars
                if (strlen($hash) === 60 && strpos($hash, '$2') === 0) {
                    $valid = password_verify($valPass, $hash);
                } 
                // Check if it's SHA1 (40 hex chars) - legacy
                elseif (strlen($hash) === 40 && ctype_xdigit($hash)) {
                    $sha = sha1($valPass);
                    if ($sha === $hash) {
                        $usuario->password = password_hash($valPass, PASSWORD_BCRYPT);
                        $usuario->update();
                        $valid = true;
                    }
                }
                // Check for argon2 or other modern hashes
                elseif (strpos($hash, '$argon') !== false) {
                    $valid = password_verify($valPass, $hash);
                }
                
                if ($valid) {
                    Session::set('id', $usuario->id);
                    Session::set('nombre', $usuario->nombre);
                    Session::set('apellido', $usuario->apellido);
                    Session::set('login', $usuario->login);
                    Session::set('tema', $usuario->tema);
                    Session::set('dark_mode', $usuario->dark_mode ?? 'off');
                    Session::set('email', $usuario->email);
                    Session::set('estado', $usuario->estado);
                    Session::set('app_ajax', $usuario->app_ajax);
                    Session::set('datagrid', $usuario->datagrid);
                    Session::set('perfil_id', $usuario->perfil_id);
                    Session::set('pool', $usuario->pool);
                    Session::set('fotografia', $usuario->fotografia);
                    Session::set(SESSION_KEY, true);
                    return true;
                }
            }
            self::setError('El usuario y/o la contraseña son incorrectos.');
            Session::set(SESSION_KEY, false);
            return false;
        }
    }

    /**
     * Método para cerrar sesión
     *
     * @param void
     * @return void
     */
    public static function logout()
    {
        //Verifico si tiene sesión
        if (!self::isLogged()) {
            self::setError("No has iniciado sesión o ha caducado. <br /> Por favor identifícate nuevamente.");
            return false;
        } else {
            $auth = Auth2::factory('model');
            $auth->logout();
            Session::set(SESSION_KEY, false);
            unset($_SESSION['KUMBIA_SESSION'][APP_PATH]);
            return true;
        }
    }

    /**
     * Método para verificar si tiene una sesión válida
     *
     * @param void
     * @return ture/false
     */
    public static function isLogged()
    {
        // Simplified: just check session key set by our login
        return Session::get(SESSION_KEY) === true;
    }

    /**
     * @return string
     */
    public static function getError()
    {
        return self::$_error;
    }

    /**
     * @param string $_error
     */
    public static function setError($error)
    {
        self::$_error = $error;
    }
}
