<?php
/**
 *
 * Extension para el manejo de mensajes sin hacer uso del "echo" en los controladores o modelos
 *
 * @category    Flash
 * @package     Helpers
 *
 * Se utiliza en el método content de la clase view.php
 *
 * Flash::output();
 *
 */

class Fecha {

    /**
     * Calcula la cantidad de meses de diferencia entre 2 fechas
     *
     * @category    Fecha
     * @package     Helpers
     * @param  mixed $fechaIni
     * @param  mixed $fechaFin
     * @return int
     */

    public static function diferencia (string $fechaIni, string $fechaFin) {

        $fechainicial = new DateTime($fechaIni);
        $fechafinal = new DateTime($fechaFin);

        // El método diff nos devuelve un objeto del tipo DateInterval,
        // que almacena la información sobre la diferencia de tiempo
        // entre fechas (años, meses, días, etc.).
        $diferencia = $fechainicial->diff($fechafinal);

        // Para calcular los meses tendremos que multiplicar el atributo “y” por 12
        // (número de meses que contiene un año). Luego le sumamos el valor que hay
        // en el atributo “m“, quien contiene el número de meses en nuestro intervalo de tiempo.

        $meses = ( $diferencia->y * 12 ) + $diferencia->m;
        return $meses;
    }

}
