<?php

/**
 *
 * Descripcion: Clase que gestiona los accesos al sistema
 *
 * @category
 * @package     Models
 * @subpackage
 */

class Acceso extends ActiveRecord
{

    /**
     * Constante para definir el acceso como entrada
     * @var int
     */
    const ENTRADA = 1;

    /**
     * Constante para definir el acceso como salida
     * @var int
     */
    const SALIDA = 2;


    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize()
    {
        $this->belongs_to('usuario');
    }

    /**
     * Método para registrar un acceso
     * @param string $tipo Tipo de acceso acceso/salida
     * @param int $usuario Usuario que accede
     * @param string $ip  Dirección ip
     */
    public static function setAcceso($tipo, $usuario)
    {
        $usuario            = Filter::get($usuario, 'numeric');
        $obj                = new Acceso();
        $obj->usuario_id    = $usuario;
        $obj->ip            = DwUtils::getIp();
        $obj->tipo_acceso   = ($tipo == Acceso::ENTRADA) ? 1 : 2;
        $obj->create();
    }

    /**
     *
     * Método para listar los accesos de los usuario
     *
     * @param int $usuario Identificador del usuario
     * @param string $tipo Tipo de acceso
     * @param string $order Método de ordenamiento
     * @param int $page Número de página
     * @return array ActiveRecord
     */
    public function getListadoAcceso($usuario = NULL, $tipo = 'todos', $order = '')
    {
        $columns    = 'acceso.*, usuario.login, usuario.nombre, usuario.apellido';
        $join       = 'INNER JOIN usuario ON usuario.id = acceso.usuario_id ';
        $conditions = (empty($usuario)) ? "usuario.id > '1'" : "usuario.id=$usuario ";
        $order = 'acceso.acceso_at DESC ';

        if ($tipo != 'todos') {
            $conditions .= ($tipo != self::ENTRADA) ? " AND acceso.tipo_acceso = " . self::ENTRADA : " AND acceso.tipo_acceso = " . self::SALIDA;
        }
        return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "order: $order");
    }

    public function getAjaxAcceso($field, $value, $order = '')
    {
        $value = Filter::get($value, 'string');
        if (strlen($value) <= 2 or ($value == 'none')) {
            return NULL;
        }

        $columns    = 'acceso.*, IF(acceso.tipo_acceso=' . self::ENTRADA . ', "Entrada", "Salida") AS new_tipo, usuario.login, usuario.nombre, usuario.apellido';
        $join       = 'INNER JOIN usuario ON usuario.id = acceso.usuario_id ';
        $conditions = "usuario.id > '1' ";
        $order      = "acceso.acceso_at DESC ";

        return $this->find_all_by_sql("SELECT $columns FROM $this->source $join WHERE $conditions ORDER BY $order", "order: $order");
    }
}
