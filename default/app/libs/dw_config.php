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
        $config = Config::read($file, $force);

        if (is_array($config)) {
            foreach ($config as $seccion => &$filas) {
                if (is_array($filas)) {
                    foreach ($filas as $variable => &$valor) {
                        if ($valor === '1' || $valor === 1) {
                            $valor = 'On';
                        } else if (empty($valor) && $valor !== '0') {
                            $valor = ($file == 'databases') ? NULL : 'Off';
                        }
                    }
                }
            }
        }

        if ($source) {
            if (is_array($source)) {
                $key = array_key_first($source);
                $var = $source[$key];
                return isset($config[$key][$var]) ? $config[$key][$var] : NULL;
            }
            return isset($config[$source]) ? $config[$source] : NULL;
        }

        return $config;
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
        $org = APP_PATH . "config/$file.php";

        @chmod($org, 0777);

        // Backup original if not exists
        if (!is_file(APP_PATH . "config/$file.org.php")) {
            copy($org, APP_PATH . "config/$file.org.php");
            @chmod(APP_PATH . "config/$file.org.php", 0777);
        }

        // Update config array with new data
        if (!empty($source)) {
            $vars[$source] = array_merge($vars[$source] ?? [], $data);
        }

        // Generate PHP config content
        $php = "<?php\n\n";
        $php .= "return [\n";

        foreach ($vars as $section => $values) {
            $php .= "    '$section' => [\n";
            foreach ($values as $key => $value) {
                if ($value !== 'delete-var') {
                    $value = is_numeric($value) ? $value : "'$value'";
                    $php .= "        '$key' => $value,\n";
                }
            }
            $php .= "    ],\n";
        }

        $php .= "];\n";

        // Write new config
        $rs = file_put_contents($org, $php);
        @chmod($org, 0777);

        return $rs;
    }


    /**
     * Método para crear variables tipo define del config.php
     */
    public static function load()
    {
        $config = self::read('config');

        if (!defined('APP_NAME') && isset($config['application']['name'])) {
            define('APP_NAME', $config['application']['name']);
        }

        if (!defined('PRODUCTION') && isset($config['application']['production'])) {
            define('PRODUCTION', $config['application']['production'] === 'On');
        }

        if (isset($config['custom'])) {
            foreach ($config['custom'] as $variable => $valor) {
                $variable = Filter::get($variable, 'upper');

                if (in_array($valor, ['On', 'Off'])) {
                    $valor = ($valor == 'On');
                }

                if (!empty($_SESSION)) {
                    if ($variable == 'APP_AJAX') {
                        $valor = (Session::get('app_ajax') && $valor);
                    } else if ($variable == 'DATAGRID') {
                        $valor = (Session::get('datagrid') > 0) ? Session::get('datagrid') : $valor;
                    }
                }

                if (!defined($variable)) {
                    define($variable, $valor);
                }
            }
        }

        Load::lib('Mobile_Detect');
        $detect = new Mobile_Detect();

        if (!defined('IS_MOBILE')) define('IS_MOBILE', $detect->isMobile());
        if (!defined('IS_TABLET')) define('IS_TABLET', $detect->isTablet());
        if (!defined('IS_DESKTOP')) define('IS_DESKTOP', (!IS_MOBILE && !IS_TABLET));
    }
}
