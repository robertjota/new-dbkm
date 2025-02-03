<?php
class UpperFilter implements FilterInterface
{
    /**
     * Ejecuta el filtro para convertir a mayúsculas incluyendo la Ñ y las tildes
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

        $string = mb_strtoupper($s, 'UTF-8');
        return trim(ucfirst($string));
    }
}
