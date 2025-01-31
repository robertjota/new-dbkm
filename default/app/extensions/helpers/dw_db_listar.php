<?php

// dw_db_listar.php

class DwDbListar
{
    /**
     * Método privado para obtener la conexión a la base de datos
     *
     * @return DbBase La conexión a la base de datos
     */
    private static function _db()
    {
        // Obtener la conexión a la base de datos utilizando la clase Db
        return Db::factory();
    }

    /**
     * Método para extraer datos de una tabla ovista en la base de datos
     *
     * @param string $nombre Nombre de la tabla o la vista en la base de datos
     * @param array $campos Campos que se desean extraer (opcional)
     * @return array Datos extraídos de la vista como objetos, ideal para dbselect
     */
    public static function getDatos($nombre, $campos = null)
    {
        $query = "SELECT ";
        if ($campos) {
            $query .= implode(", ", $campos);
        } else {
            $query .= "*";
        }
        $query .= " FROM $nombre";

        $db = self::_db();
        $rs = $db->query($query);

        if ($rs) {
            $datos = array();
            while ($fila = $db->fetch_array($rs, MYSQLI_ASSOC)) {
                $objeto = new stdClass();
                foreach ($fila as $campo => $valor) {
                    $objeto->$campo = $valor;
                }
                $datos[] = $objeto;
            }
            return $datos;
        } else {
            return false;
        }
    }

    /**
     * Obtiene los datos de una tabla o vista con filtros y orden.
     *
     * @param string $nombre El nombre de la tabla o la vista.
     * @param array $campos Los campos a seleccionar (opcional).
     * @param array $filtros Los filtros a aplicar (opcional).
     * @param string $orden El orden de los resultados (opcional).
     * @return array Los datos de la vista con los filtros y orden aplicados.
     * @throws Exception Si los filtros no son válidos.
     *
     * Ejemplo:
     * $nombre = 'tabla_nombre';
     * $campos = ['nombre', 'edad', 'sexo'];
     * $filtros = ['edad', '>', 18]; si es un solo filtro
     * $filtros =[
     *   ['nombre', 'LIKE', '%Juan%'],
     *   ['edad', '>', 18'],
     *   ['sexo', '=', 'M'],
     *   ['direccion', 'IS NULL']
     * ]; si son varios filtros
     * $orden = 'nombre ASC';
     * $datos = DwDbVista::getDatosVistaConFiltro($vista, $campos, $filtros, $orden);
     */
    public static function getDatosConFiltro($nombre, $campos = null, $filtros = null, $orden = null)
    {
        $query = "SELECT ";
        if ($campos) {
            $query .= implode(", ", $campos);
        } else {
            $query .= "*";
        }
        $query .= " FROM $nombre";

        if ($filtros) {
            if (!is_array($filtros[0])) {
                $filtros = array($filtros);
            }
            $condiciones = array();
            foreach ($filtros as $filtro) {
                if (count($filtro) !== 3) {
                    throw new Exception('Filtro inválido. Debe tener 3 elementos: campo, operador y valor');
                }
                $campo = $filtro[0];
                $operador = $filtro[1];
                $valor = $filtro[2];

                if (in_array($operador, array('=', '<', '>', '<=', '>=', '<>'))) {
                    if (!is_scalar($valor)) {
                        throw new Exception('Filtro inválido. Debe tener un valor escalar');
                    }
                    $condiciones[] = $campo . ' ' . $operador . ' ' . $valor;
                } elseif ($operador == 'LIKE') {
                    if (!is_string($valor)) {
                        throw new Exception('Filtro inválido. Debe tener un valor de tipo string');
                    }
                    $condiciones[] = $campo . ' LIKE ' . $valor;
                } elseif ($operador == 'NOT LIKE') {
                    if (!is_string($valor)) {
                        throw new Exception('Filtro inválido. Debe tener un valor de tipo string');
                    }
                    $condiciones[] = $campo . ' NOT LIKE ' . $valor;
                } elseif ($operador == 'IS NULL') {
                    if ($valor !== null) {
                        throw new Exception('Filtro inválido. Debe tener un valor null');
                    }
                    $condiciones[] = $campo . ' IS NULL';
                } elseif ($operador == 'IS NOT NULL') {
                    if ($valor !== null) {
                        throw new Exception('Filtro inválido. Debe tener un valor null');
                    }
                    $condiciones[] = $campo . ' IS NOT NULL';
                } else {
                    throw new Exception('Operador inválido');
                }
            }
            $query .= " WHERE " . implode(" AND ", $condiciones);
        }

        if ($orden) {
            $query .= " ORDER BY $orden";
        }

        $db = self::_db();
        $rs = $db->query($query);

        if ($rs) {
            $datos = array();
            while ($fila = $db->fetch_array($rs, MYSQLI_ASSOC)) {
                $objeto = new stdClass();
                foreach ($fila as $campo => $valor) {
                    $objeto->$campo = $valor;
                }
                $datos[] = $objeto;
            }
            return $datos;
        } else {
            return false;
        }
    }
}
