<?php

/**
 *
 * Clase para el manejo de texto y otras cosas
 *
 * @package     Libs
 */

class DwUtils
{

    /**
     * Método para obtener la IP real del cliente
     *
     * @return string La dirección IP del cliente
     * version actualizada por RJR Sistemas
     */
    public static function getIp()
    {
        $client_ip = $_SERVER['REMOTE_ADDR'] ?? $_ENV['REMOTE_ADDR'] ?? "unknown";

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $entries = preg_split('/[,\s]+/', $_SERVER['HTTP_X_FORWARDED_FOR']);

            foreach ($entries as $entry) {
                $entry = trim($entry);
                if (preg_match("/^([0-9]+\\.[0-9]+\\.[0-9]+\\.[0-9]+)/", $entry, $ip_list)) {
                    $private_ip = array(
                        '/^0\./',
                        '/^127\.0\.0\.1/',
                        '/^192\.168\..*/',
                        '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
                        '/^10\..*/'
                    );
                    $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);

                    if ($client_ip != $found_ip) {
                        $client_ip = $found_ip;
                        break;
                    }
                }
            }
        }

        return $client_ip;
    }
    /*
     * Metodo para resaltar palabras de una cadena de texto
     */
    public static function resaltar($palabra, $texto)
    {
        $reemp  =   str_ireplace($palabra, '%s', $texto);
        $aux    =   $reemp;
        $veces  =   substr_count($reemp, '%s');
        if ($veces == 0) {
            return $texto;
        }
        $palabras_originales    =   array();
        for ($i = 0; $i < $veces; $i++) {
            $palabras_originales[] = '<b style="color: red;">' . substr($texto, strpos($aux, '%s'), strlen($palabra)) . '</b>';
            $aux = substr($aux, 0, strpos($aux, '%s')) . $palabra . substr($aux, strlen(substr($aux, 0, strpos($aux, '%s'))) + 2);
        }
        return vsprintf($reemp, $palabras_originales);
    }

    /**
     * Crea un slug a partir de una cadena.
     *
     * @param string $string La cadena de entrada para convertir en slug.
     * @param string $separator El separador a usar en el slug (por defecto '-').
     * @param int $length La longitud máxima del slug (por defecto 100).
     * @return string El slug generado.
     * version actualizada por RJR Sistemas
     */
    public static function getSlug(string $string, string $separator = '-', int $length = 100): string
    {
        // Tabla de transliteración
        $transliterationTable = [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'à' => 'a',
            'è' => 'e',
            'ì' => 'i',
            'ò' => 'o',
            'ù' => 'u',
            'ä' => 'ae',
            'ë' => 'e',
            'ï' => 'i',
            'ö' => 'oe',
            'ü' => 'ue',
            'ÿ' => 'y',
            'â' => 'a',
            'ê' => 'e',
            'î' => 'i',
            'ô' => 'o',
            'û' => 'u',
            'å' => 'a',
            'ø' => 'o',
            'Š' => 'S',
            'š' => 's',
            'Ž' => 'Z',
            'ž' => 'z',
            'Ÿ' => 'Y',
            'ç' => 'c',
            'Ç' => 'C',
            'ñ' => 'n',
            'Ñ' => 'N',
            'œ' => 'oe',
            'Œ' => 'OE',
            'æ' => 'ae',
            'Æ' => 'AE'
        ];

        // Convertir a minúsculas y transliterar
        $string = mb_strtolower($string, 'UTF-8');
        $string = strtr($string, $transliterationTable);

        // Reemplazar caracteres no alfanuméricos con el separador
        $string = preg_replace('/[^a-z0-9]/u', $separator, $string);

        // Eliminar separadores duplicados
        $string = preg_replace('/' . preg_quote($separator, '/') . '+/', $separator, $string);

        // Recortar al largo máximo
        $string = mb_substr($string, 0, $length, 'UTF-8');

        // Eliminar separadores al inicio y al final
        return trim($string, $separator);
    }

    /**
     * Ordena un array de datos por un campo específico.
     *
     * @param array $toOrderArray Array de datos a ordenar
     * @param string|callable $field Campo del array por el cual se va a ordenar, o una función de comparación
     * @param string $direction Dirección de ordenamiento: 'ASC' o 'DESC'
     * @param int $sortFlags Flags adicionales para la función de ordenamiento (e.g., SORT_NUMERIC, SORT_STRING)
     * @return array Array ordenado
     * @throws InvalidArgumentException Si los parámetros no son válidos
     * version actualizada por RJR Sistemas
     */
    public static function orderArray(array $toOrderArray, $field, string $direction = 'DESC', int $sortFlags = SORT_REGULAR): array
    {
        if (empty($toOrderArray)) {
            return $toOrderArray;
        }

        $direction = strtoupper($direction);
        if (!in_array($direction, ['ASC', 'DESC'])) {
            throw new InvalidArgumentException("La dirección de ordenamiento debe ser 'ASC' o 'DESC'");
        }

        if (is_callable($field)) {
            // Si $field es una función de comparación, usamos usort
            usort($toOrderArray, $field);
            if ($direction === 'DESC') {
                $toOrderArray = array_reverse($toOrderArray);
            }
        } else {
            // Si $field es una cadena, usamos array_column y array_multisort
            $sortColumn = array_column($toOrderArray, $field);
            if ($sortColumn === []) {
                throw new InvalidArgumentException("El campo '$field' no existe en el array");
            }

            $sortDirection = ($direction === 'DESC') ? SORT_DESC : SORT_ASC;
            array_multisort($sortColumn, $sortDirection, $sortFlags, $toOrderArray);
        }

        return $toOrderArray;
    }


    /**
     * Obtiene un array con las carpetas de un directorio.
     *
     * @param string $path Ruta del directorio a escanear
     * @param bool $fullPath Si es true, devuelve las rutas completas de las carpetas
     * @param bool $sort Si es true, ordena alfabéticamente las carpetas
     * @param array $exclude Array de nombres de carpetas a excluir
     * @return array Array asociativo de carpetas encontradas
     * @throws InvalidArgumentException Si la ruta no es válida o no es accesible
     * version actualizada por RJR Sistemas
     */
    public static function getFolders(string $path, bool $fullPath = false, bool $sort = true, array $exclude = []): array
    {
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        if (!is_dir($path) || !is_readable($path)) {
            throw new InvalidArgumentException("La ruta '$path' no es un directorio válido o no es accesible.");
        }

        $folders = [];
        $exclude = array_merge(['.', '..'], $exclude);

        try {
            $iterator = new DirectoryIterator($path);
            foreach ($iterator as $fileInfo) {
                if ($fileInfo->isDir() && !in_array($fileInfo->getFilename(), $exclude)) {
                    $folderName = $fileInfo->getFilename();
                    $folders[$folderName] = $fullPath ? $fileInfo->getPathname() : $folderName;
                }
            }

            if ($sort) {
                ksort($folders, SORT_NATURAL | SORT_FLAG_CASE);
            }
        } catch (UnexpectedValueException $e) {
            throw new InvalidArgumentException("Error al leer el directorio: " . $e->getMessage());
        }

        return $folders;
    }

    /**
     * Convierte parámetros en un array asociativo.
     *
     * Esta función puede manejar tanto un array de parámetros como un solo parámetro.
     * Los parámetros pueden ser pasados como strings en el formato "nombre: valor" o como valores simples.
     * Si se pasan múltiples parámetros con el mismo nombre, se mantendrá el último valor.
     *
     * @param array|string|mixed $params Parámetro(s) a procesar.
     *                                   Puede ser:
     *                                   - Un array de parámetros
     *                                   - Una cadena en formato "nombre: valor"
     *                                   - Un valor simple (string, int, bool, etc.)
     *
     * @return array Array asociativo donde:
     *               - Las claves son los nombres de los parámetros (si se proporcionaron)
     *               - Los valores son los valores de los parámetros
     *               - Los parámetros sin nombre se indexan numéricamente
     *
     * @example
     * // Múltiples parámetros
     * $result1 = Util::getParamsMultiple(['nombre: Juan', 'edad: 30', 'ciudad: Madrid']);
     * // Resultado: ['nombre' => 'Juan', 'edad' => '30', 'ciudad' => 'Madrid']
     *
     * // Un solo parámetro
     * $result2 = Util::getParamsMultiple('nombre: María');
     * // Resultado: ['nombre' => 'María']
     *
     * // Un solo valor simple
     * $result3 = Util::getParamsMultiple('Hola');
     * // Resultado: [0 => 'Hola']
     *
     * @throws \InvalidArgumentException Si $params es un array vacío.
     */
    public static function getParamsMultiple($params)
    {
        if (is_array($params)) {
            if (empty($params)) {
                throw new \InvalidArgumentException('El array de parámetros no puede estar vacío.');
            }
        } else {
            // Si no es un array, lo convertimos en uno
            $params = [$params];
        }

        $data = array();
        foreach ($params as $p) {
            if (is_string($p)) {
                $match = explode(': ', $p, 2);
                if (isset($match[1])) {
                    $data[$match[0]] = $match[1];
                } else {
                    $data[] = $p;
                }
            } else {
                $data[] = $p;
            }
        }
        return $data;
    }


    /**
     * Imprime múltiples variables de forma formateada y detiene la ejecución del script.
     *
     * @param mixed ...$variables Lista de variables a imprimir
     *
     * version actualizada por RJR Sistemas
     */
    public static function print_r(...$variables): void
    {
        echo '<pre style="background-color: #c4c4c4; padding: 15px; border: 1px solid #ddd; border-radius: 5px; font-family: monospace;">';

        foreach ($variables as $index => $variable) {
            $variableNumber = $index + 1;
            echo "<strong style='color: #333;'>Variable {$variableNumber}:</strong><br>";

            if (is_null($variable)) {
                echo "<em style='color: #169871;'>NULL</em>";
            } elseif (!isset($variable)) {
                echo "<em style='color: #169;'>INDEFINIDO</em>";
            } elseif (is_bool($variable)) {
                echo $variable ? "<span style='color: #008000;'>true</span>" : "<span style='color: #ff0000;'>false</span>";
            } elseif (is_array($variable) || is_object($variable)) {
                echo "<div style='margin-left: 20px;'>";
                print_r($variable);
                echo "</div>";
            } else {
                var_dump($variable);
            }

            echo "<br><br>";
        }

        echo '</pre>';
        exit();
    }

    private static $units = [
        '',
        'UN',
        'DOS',
        'TRES',
        'CUATRO',
        'CINCO',
        'SEIS',
        'SIETE',
        'OCHO',
        'NUEVE',
        'DIEZ',
        'ONCE',
        'DOCE',
        'TRECE',
        'CATORCE',
        'QUINCE',
        'DIECISEIS',
        'DIECISIETE',
        'DIECIOCHO',
        'DIECINUEVE',
        'VEINTE'
    ];

    private static $tens = [
        '',
        '',
        'VEINTI',
        'TREINTA',
        'CUARENTA',
        'CINCUENTA',
        'SESENTA',
        'SETENTA',
        'OCHENTA',
        'NOVENTA'
    ];

    private static $hundreds = [
        '',
        'CIENTO',
        'DOSCIENTOS',
        'TRESCIENTOS',
        'CUATROCIENTOS',
        'QUINIENTOS',
        'SEISCIENTOS',
        'SETECIENTOS',
        'OCHOCIENTOS',
        'NOVECIENTOS'
    ];

    /**
     * Convierte un valor monetario a su representación en palabras.
     *
     * @param float $valor Valor monetario a convertir
     * @param string $moneda Nombre de la moneda en plural (por defecto 'BOLIVARES')
     * @param string $monedaSingular Nombre de la moneda en singular (por defecto 'BOLIVAR')
     * @param string $centimos Nombre de los centavos en plural (por defecto 'CENTIMOS')
     * @param string $centimoSingular Nombre del centavo en singular (por defecto 'CENTIMO')
     * @return string Representación en palabras del valor monetario
     * version actualizada por RJR Sistemas
     */
    public static function moneyToLetter(
        float $valor,
        string $moneda = 'BOLIVARES',
        string $monedaSingular = 'BOLIVAR',
        string $centimos = 'CENTIMOS',
        string $centimoSingular = 'CENTIMO'
    ): string {
        $entero = floor($valor);
        $decimal = round(($valor - $entero) * 100);

        $resultado = self::numberToWords($entero);
        $resultado .= " " . ($entero == 1 ? $monedaSingular : $moneda);

        if ($decimal > 0) {
            $resultado .= " CON " . self::numberToWords($decimal);
            $resultado .= " " . ($decimal == 1 ? $centimoSingular : $centimos);
        }

        return trim($resultado);
    }

    /**
     * Convierte un número a palabras.
     *
     * @param int $number Número a convertir
     * @return string Representación en palabras del número
     */
    private static function numberToWords(int $number): string
    {
        if ($number === 0) {
            return 'CERO';
        }

        if ($number < 0) {
            return 'MENOS ' . self::numberToWords(abs($number));
        }

        $words = '';

        if (($millones = floor($number / 1000000)) > 0) {
            $words .= ($millones > 1) ? self::numberToWords($millones) . ' MILLONES DE ' : 'UN MILLON DE ';
            $number %= 1000000;
        }

        if (($miles = floor($number / 1000)) > 0) {
            $words .= ($miles > 1) ? self::numberToWords($miles) . ' MIL ' : 'MIL ';
            $number %= 1000;
        }

        if (($centenas = floor($number / 100)) > 0) {
            if ($number == 100) {
                $words .= 'CIEN ';
            } else {
                $words .= self::$hundreds[$centenas] . ' ';
            }
            $number %= 100;
        }

        if ($number >= 20) {
            $words .= self::$tens[floor($number / 10)];
            if ($number % 10 > 0) {
                $words .= ($number > 30 ? ' Y ' : '') . self::$units[$number % 10];
            }
        } elseif ($number > 0) {
            $words .= self::$units[$number];
        }

        return trim($words);
    }
}
