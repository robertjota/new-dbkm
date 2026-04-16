<?php

/**
 * Clase que permite crear llave de seguridad en las URL. Además, provee un algoritmo de criptografía
 *
 * @package     Libs
 */
class DwSecurity
{
    /**
     * Constante de llave de seguridad
     */
    const TEXT_KEY = 'XTaiE$4Y2M~DBc{)wK-|LI+cPwr=x_Dpf';

    /**
     * Método para generar las llaves de seguridad
     *
     * @param mixed $id Identificador o valor de la llave primaria
     * @param string $action Texto o acción para la llave
     * @return string
     */
    public static function setKey($id, $action = '')
    {
        $key = (defined('TEXT_KEY')) ? self::TEXT_KEY : self::TEXT_KEY . date("Y-m-d");
        $key = md5($id . $key . $action);
        $tam = strlen($key);
        return $id . '+' . substr($key, 0, 6) . substr($key, $tam - 6, $tam);
    }

    /**
     * Método para verificar si la llave es válida
     *
     * @param string $valueKey
     * @param string $action
     * @param string $filter Filtro a aplicar al id devuelto
     * @param bool $popup
     * @return mixed
     */
    public static function getKey($valueKey = '', $action = '', $filter = '', $popup = FALSE)
    {
        $key = explode('+', $valueKey);
        $id = empty($key[0]) ? null : $key[0];
        $validKey = self::setKey($id, $action);
        $valid = ($validKey === $valueKey);

        if (!$valid) {
            Flash::error('Acceso denegado. La llave de seguridad es incorrecta.');
            if ($popup) {
                View::error();
            }
            return false;
        }
        return ($filter) ? Filter::get($id, $filter) : $id;
    }

    /**
     * Método para cifrar texto o palabras
     * @param string $value Texto a cifrar
     * @param string|null $pass Clave del cifrado, puede estar definida o se toma un valor por defecto. Esta clave se debe utilizar para descifrar
     * @return string|false
     */
    public static function encrypt($value, $pass = null)
    {
        if (!$value) {
            return false;
        }
        $pass = md5($pass ?? self::getTextKey());
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-ecb'));
        $crypttext = openssl_encrypt($value, 'aes-128-ecb', $pass, 0, $iv);
        return self::safe_b64encode($crypttext);
    }

    /**
     * Método para descifrar texto o palabras
     * @param string $value Texto a descifrar
     * @param string|null $pass Clave utilizada en el cifrado, puede estar definida o se toma un valor por defecto.
     * @param string $filter
     * @return string|false
     */
    public static function decrypt($value, $pass = null, $filter = '')
    {
        if (!$value) {
            return false;
        }
        $pass = md5($pass ?? self::getTextKey());
        $crypttext = self::safe_b64decode($value);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-ecb'));
        $decrypttext = openssl_decrypt($crypttext, 'aes-128-ecb', $pass, 0, $iv);
        return ($filter) ? Filter::get($decrypttext, $filter) : $decrypttext;
    }

    /**
     * Método para codificar en base64 de manera segura
     * @param string $string
     * @return string
     */
    protected static function safe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(['+', '/', '='], ['-', '_', ''], $data);
        return $data;
    }

    /**
     * Método para decodificar en base64 de manera segura
     * @param string $string
     * @return string
     */
    protected static function safe_b64decode($string)
    {
        $data = str_replace(['-', '_'], ['+', '/'], $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    /**
     * Obtiene la clave de texto
     * @return string
     */
    protected static function getTextKey()
    {
        return self::TEXT_KEY;
    }
}
