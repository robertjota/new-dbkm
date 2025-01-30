<?php

/**
 *
 * Librería para el manejo de auditorías y registro de acciones de usuarios
 *
 * @category
 * @package     Libs
 */

class DwAudit extends Logger
{

    /**
     * Usuario que realiza la acción
     * @var string
     */
    protected static $_login;
    /**
     * Dirección ip donde realiza la acción
     * @var string
     */
    protected static $_ip;
    /**
     * Url en donde se produce la acción
     * @var string
     */
    protected static $_route;
    /**
     * Nombre del archivo
     * @var string
     */
    protected static $_logName = 'audit.txt';

    /**
     * Inicializa el Logger
     */
    public static function initialize($name): void
    {
        if (empty($name)) {
            self::$_logName = 'audit' . date('Y-m-d') . '.txt';
        }
        self::$_login = Session::get('login');
        self::$_ip = (Session::get('ip')) ? Session::get('ip') : DwUtils::getIp();
        self::$_route = Router::get('route');
    }

    /**
     * Almacena un mensaje en el log
     *
     * @param string $type
     * @param string $msg
     * @param string $name_log
     */
    public static function log($type, $msg, $name_log): void
    {
        if ($type == '') {
            $type == 'DEBUG';
        }
        self::initialize($name_log);
        $msg = trim(trim($msg), '.') . '.';
        parent::log($type, '[' . self::$_route . '][' . self::$_login . '][' . self::$_ip . '] ' . $msg, self::$_logName);
    }

    /**
     * Genera un log de tipo WARNING
     *
     * @return
     * @param string $msg
     * @param string $name_log
     */
    public static function warning($msg, $name_log = ''): void
    {
        self::log('WARNING', $msg, $name_log);
    }

    /**
     * Genera un log de tipo ERROR
     *
     * @return
     * @param string $msg
     * @param string $name_log
     */
    public static function error($msg, $name_log = ''): void
    {
        self::log('ERROR', $msg, $name_log);
    }

    /**
     * Genera un log de tipo DEBUG
     *
     * @return
     * @param string $msg
     * @param string $name_log
     */
    public static function debug($msg, $name_log = '', $NoDebug = ''): void
    {
        self::log('DEBUG', $msg, $name_log, $NoDebug);
    }

    /**
     * Genera un log de tipo INFO
     *
     * @return
     * @param string $msg
     * @param string $name_log
     */
    public static function info($msg, $name_log = ''): void
    {
        self::log('INFO', $msg, $name_log);
    }
}
