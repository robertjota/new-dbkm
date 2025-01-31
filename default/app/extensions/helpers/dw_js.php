<?php

/**
 *
 * Extension para el manejo de javascript
 *
 * @category    Helpers
 * @package     Helpers
 */

class DwJs
{

    /**
     * Contador de mensajes
     * @var int
     */
    protected static $_counter = 1;

    /**
     * Método para generar un mensaje de alerta con SweetAlert2
     * @param string $text
     * @param string $params
     * @return string
     */
    public static function alert($text, $params = '')
    {
        $params = Util::getParams(func_get_args());

        $icon = isset($params['icon']) ? $params['icon'] : 'warning';
        $title = isset($params['title']) ? $params['title'] : '';
        $subtext = isset($params['subtext']) ? $params['subtext'] : '';
        $name = isset($params['name']) ? trim($params['name'], '()') : "sweetAlert" . rand(10, 5000);
        $autoOpen = isset($params['autoOpen']) ? true : false;

        $js = self::open();
        $js .= "function $name() {
            Swal.fire({
                icon: '$icon',
                title: '$title',
                html: '$text' + ('" . $subtext . "' ? '<br><small class=\"text-muted\">" . $subtext . "</small>' : ''),
                confirmButtonText: 'Ok',
                confirmButtonColor: '#3085d6'
            });
        }";

        if ($autoOpen) {
            $js .= "$(function(){ $name(); });";
        }
        $js .= self::close();

        return $js;
    }

    public static function open()
    {
        return '<script>' . PHP_EOL;
    }

    public static function close()
    {
        return '</script>' . PHP_EOL;
    }

    public static function updateUrl($url)
    {
        $url = trim($url, '/') . '/';
        return self::open() . "updateUrl('$url');" . self::close();
    }
}
