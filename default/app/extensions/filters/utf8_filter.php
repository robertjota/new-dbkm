<?php

/**
 * Filtro para usar tildes en los reportes de pdf
 *
 * @category    Extensions
 * @package     Filters
 * @version     1.0
 */
class Utf8Filter implements FilterInterface
{
    /**
     * Ejecuta el filtro
     *
     * @param string $s
     * @param array $options
     * @return string
     */
    public static function execute($s, $options)
    {
        if ($s === null) {
            return '';
        }

        // Usando mb_convert_encoding en lugar de utf8_decode
        $string = mb_convert_encoding(
            html_entity_decode($s, ENT_QUOTES, 'UTF-8'),
            'ISO-8859-1',
            'UTF-8'
        );

        return $string;
    }
}
