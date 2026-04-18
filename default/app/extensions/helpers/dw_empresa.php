<?php

/**
 * Helper para gestionar los datos de la empresa
 * 
 * Lee los datos de la empresa desde config y los logos desde archivos
 */
class DwEmpresa
{

    private static $data = null;
    private static $logo = null;
    private static $logoMini = null;

    /**
     * Cargar los datos de la empresa
     */
    private static function load()
    {
        if (self::$data === null) {
            // Cargar desde config usando ruta correcta
            self::$data = Config::get('config.custom.empresa');
            if (!self::$data) {
                self::$data = array();
            }
            
            // Cargar logos desde archivos
            $logoExtensions = array('png', 'jpg', 'jpeg', 'svg');
            foreach ($logoExtensions as $ext) {
                if (file_exists(PUBLIC_PATH . 'empresa/logo-empresa.' . $ext)) {
                    self::$logo = PUBLIC_PATH . 'empresa/logo-empresa.' . $ext;
                    break;
                }
            }
            foreach ($logoExtensions as $ext) {
                if (file_exists(PUBLIC_PATH . 'empresa/logo-mini.' . $ext)) {
                    self::$logoMini = PUBLIC_PATH . 'empresa/logo-mini.' . $ext;
                    break;
                }
            }
        }
    }

    /**
     * Obtener un dato de la empresa
     * 
     * @param string $key Campo a obtener (nombre, rif, direccion, telefono, email, web)
     * @param string $default Valor por defecto si no existe
     * @return string
     */
    public static function get($key, $default = '')
    {
        self::load();
        return isset(self::$data[$key]) ? self::$data[$key] : $default;
    }

    /**
     * Obtener el nombre de la empresa
     * 
     * @return string
     */
    public static function nombre()
    {
        return self::get('nombre', 'DBKM');
    }

    /**
     * Obtener el RIF de la empresa
     * 
     * @return string
     */
    public static function rif()
    {
        return self::get('rif', '');
    }

    /**
     * Obtener la dirección de la empresa
     * 
     * @return string
     */
    public static function direccion()
    {
        return self::get('direccion', '');
    }

    /**
     * Obtener el teléfono de la empresa
     * 
     * @return string
     */
    public static function telefono()
    {
        return self::get('telefono', '');
    }

    /**
     * Obtener el email de la empresa
     * 
     * @return string
     */
    public static function email()
    {
        return self::get('email', '');
    }

    /**
     * Obtener el sitio web de la empresa
     * 
     * @return string
     */
    public static function web()
    {
        return self::get('web', '');
    }

    /**
     * Obtener la URL del logo
     * 
     * @return string|null
     */
    public static function logo()
    {
        self::load();
        return self::$logo;
    }

    /**
     * Obtener la URL del logo mini
     * 
     * @return string|null
     */
    public static function logoMini()
    {
        self::load();
        return self::$logoMini;
    }

    /**
     * Verificar si existe logo
     * 
     * @return bool
     */
    public static function hasLogo()
    {
        self::load();
        return self::$logo !== null;
    }

    /**
     * Obtener todos los datos como array
     * 
     * @return array
     */
    public static function datos()
    {
        self::load();
        return self::$data;
    }
}