<?php

/**
 * Clase que se utiliza para editar los .php utilizados como archivos de configuración
 * de la carpeta config de las app y para cargar algunas variables
 *
 * @category    Sistema
 * @package     Libs
 */

class DwConfig
{

    /**
     * Método que se utiliza para leer un .php
     * @param type $file Nombre del archivo (sin el .php);
     * @param type $source
     */
    public static function read($file, $source = '', $force = FALSE)
    {
        $tmp = $file;
        $file = Config::read($file, $force);
        foreach ($file as $seccion => $filas) {
            foreach ($filas as $variable => $valor) {
                if ($valor == '1') {
                    $file[$seccion][$variable] = 'On';
                } else if (empty($valor)) {
                    $file[$seccion][$variable] = ($tmp == 'databases') ? NULL : 'Off';
                }
            }
        }
        if ($source) {
            if (is_array($source)) {
                $key = @array_shift(array_keys($source));
                $var = $source[$key];
                return (isset($file[$key][$var])) ? $file[$key][$var] : NULL;
            } else {
                return (isset($file[$source])) ? $file[$source] : NULL;
            }
        }
        return $file;
    }

    /**
     * Método que se utiliza para escribir un .php
     * Se eliminan las variables cuyo valor sea delete-var
     * @param type $file Nombre del archivo (sin el .php);
     * @param type $source
     */
    public static function write($file, $data, $source = '')
    {
        $vars = self::read($file, '', TRUE);
        $org = APP_PATH . "config/$file.php"; //Archivo actual
        @chmod(APP_PATH . "config/$file.php", 0777); //Le damos permisos para editar
        //Verifico si tiene copia del original, sino se crea
        if (!is_file(APP_PATH . "config/$file.org.php")) {
            //@TODO Verificar que funcione en windows
            $org = APP_PATH . "config/$file.php";
            $des = APP_PATH . "config/$file.org.php";
            copy($org, $des); //Copio el actual y lo paso a original
            @chmod($des, 0777); //Permisos
            unlink($org); //Elimino el original para crear el nuevo
            touch($des); //Creo el nuevo .php
        }
        //Armo el archivo
        $php = ";; Archivo de configuración" . PHP_EOL;
        $php .= PHP_EOL;
        $php .= "; Si desea conocer más acerca de este archivo" . PHP_EOL;
        $php .= "; puede abrir el archivo $file.org.php, el cual tendrá" . PHP_EOL;
        $php .= "; la descripción de los parámetros aplicados." . PHP_EOL;
        $php .= PHP_EOL;
        //Verifico si no está el source especificado para crearlo
        if (!array_key_exists($source, $vars)) {
            $vars[$source] = $data;
        }
        //Recorro el archivo para ver que variables cambian, se crean o se eliminan
        foreach ($vars as $seccion => $filas) {
            $php .= "[$seccion]" . PHP_EOL;
            if (is_array($filas)) {
                foreach ($filas as $variable => $valor) {
                    if ($source && $seccion == $source) {
                        if (array_key_exists($variable, $data)) {
                            if ($data[$variable] != 'delete-var') { //Verifico si es para eliminar la variable
                                $valor = $data[$variable];
                            } else {
                                continue;
                            }
                        }
                    }
                    $variable = Filter::get($variable, 'lower');
                    if (in_array($valor, array('On', 'Off')) || is_numeric($valor)) {
                        $php .= "$variable = $valor" . PHP_EOL;
                    } else {
                        $valor = Filter::get($valor, 'htmlspecialchars');
                        $php .= "$variable = $valor" . PHP_EOL;
                    }
                }
                if ($source && $seccion == $source) { //Verifico si está en el source correspondiente
                    foreach ($data as $variable => $valor) { //Verifico que variables se crean
                        if (!array_key_exists($variable, $filas)) {
                            $variable = DwUtils::getSlug($variable, '_');
                            if ($file == 'routes') {
                                $variable = "/$variable";
                                $valor = "/" . ltrim($valor, '/');
                            }
                            if (in_array($valor, array('On', 'Off')) || is_numeric($valor)) {
                                $php .= "$variable = $valor" . PHP_EOL;
                            } else {
                                $valor = Filter::get($valor, 'htmlspecialchars');
                                $php .= "$variable = $valor" . PHP_EOL;
                            }
                        }
                    }
                }
            }
            $php .= PHP_EOL;
        }
        $php .= PHP_EOL;
        $rs = file_put_contents(APP_PATH . "config/$file.php", $php);
        @chmod(APP_PATH . "config/$file.php", 0777);
        //Actualizo las variables de configuracion
        self::read($file, '', TRUE);
        return $rs;
    }

    /**
     * Método para crear variables tipo define del config.php
     */
    public static function load()
    {
        $config = self::read('config');
        // Nombre del aplicativo
        if (!defined('APP_NAME')) {
            define('APP_NAME', $config['application']['name']);
        }
        if (!defined('PRODUCTION')) {
            define('PRODUCTION', $config['application']['production']);
        }

        //Carga y define automáticamente las variables defphpdas en el config.php
        if (isset($config['custom'])) {
            foreach ($config['custom'] as $variable => $valor) {
                $variable = Filter::get($variable, 'upper');
                if (in_array($valor, array('On', 'Off'))) {
                    $valor = ($valor == 'On') ? TRUE : FALSE;
                }
                if ($variable == 'APP_AJAX') {
                    $valor = (Session::get('app_ajax') && ($valor)) ? TRUE : FALSE;
                } else if ($variable == 'DATAGRID') {
                    $valor = (Session::get('datagrid') > 0) ? Session::get('datagrid') : $valor;
                }
                define($variable, $valor);
            }
        }

        //Se verifica que tipo de dispositivo es
        Load::lib('Mobile_Detect');
        $detect = new Mobile_Detect();
        define('IS_MOBILE', $detect->isMobile());
        define('IS_TABLET', $detect->isTablet());
        define('IS_DESKTOP', (!IS_MOBILE && !IS_TABLET) ? TRUE : FALSE);
    }
}
