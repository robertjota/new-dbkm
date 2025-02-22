<?php

/**
 * KumbiaPHP web & app Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 *
 * @category   Kumbia
 * @package    Db
 * @subpackage Adapters
 *
 * @copyright  Copyright (c) 2005 - 2023 KumbiaPHP Team (http://www.kumbiaphp.com)
 * @license    https://github.com/KumbiaPHP/KumbiaPHP/blob/master/LICENSE   New BSD License
 */
/**
 * @see DbPDOInterface
 */
require_once CORE_PATH . 'libs/db/adapters/pdo/interface.php';

/**
 * PHP Data Objects Support.
 *
 * @category   Kumbia
 * @package    Db
 * @subpackage Adapters
 */
abstract class DbPDO extends DbBase implements DbPDOInterface
{
    /**
     * Instancia PDO.
     *
     * @var PDO
     */
    protected $pdo;
    /**
     * Último Resultado de una Query.
     *
     * @var PDOStament
     */
    public $pdo_statement;
    /**
     * Última sentencia SQL enviada.
     *
     * @var string
     */
    protected $last_query;
    /**
     * Último error generado.
     *
     * @var string
     */
    protected $last_error;
    /**
     * Número de filas afectadas.
     */
    protected $affected_rows;
    /**
     * Nombre del Driver RBDM.
     */
    protected $db_rbdm;

    /**
     * Resultado de Array Asociativo.
     */
    const DB_ASSOC = PDO::FETCH_ASSOC;

    /**
     * Resultado de Array Asociativo y Númerico.
     */
    const DB_BOTH = PDO::FETCH_BOTH;

    /**
     * Resultado de Array Númerico.
     */
    const DB_NUM = PDO::FETCH_NUM;

    /**
     * Constructor de la Clase.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->connect($config);
    }

    /**
     * Hace una conexión a la base de datos.
     *
     * @param array $config
     *
     * @return bool
     */
    public function connect(array $config)
    {
        if (!extension_loaded('pdo')) {
            throw new KumbiaException('Debe cargar la extensión de PHP llamada php_pdo');
        }

        try {
            $this->pdo = new PDO($config['type'] . ':' . $config['dsn'], $config['username'], $config['password']);
            if ($this->db_rbdm != 'odbc') {
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
                $this->pdo->setAttribute(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY);
            }
            //Selecciona charset
            if ($config['type'] === 'mysql' && isset($config['charset'])) {
                $this->pdo->exec('set character set ' . $config['charset']);
            }
            $this->initialize();

            return true;
        } catch (PDOException $e) {
            throw new KumbiaException($this->error($e->getMessage()));
        }
    }

    /**
     * Efectua operaciones SQL sobre la base de datos.
     *
     * @param string $sql_query
     *
     * @return resource or false
     */
    public function query($sql_query)
    {
        $this->debug($sql_query);
        if ($this->logger) {
            Logger::debug($sql_query);
        }
        if (!$this->pdo) {
            throw new KumbiaException('No hay conexión a la base de datos para realizar esta consulta.');
        }
        $this->last_query = $sql_query;
        $this->pdo_statement = null;
        try {
            if ($pdo_statement = $this->pdo->query($sql_query)) {
                return $this->pdo_statement = $pdo_statement;
            }
        } catch (PDOException $e) {
            throw new KumbiaException($this->error($e->getMessage() . " al ejecutar <em>\"$sql_query\"</em>"));
        }
    }

    /**
     * Efectua operaciones SQL sobre la base de datos y devuelve el numero de filas afectadas.
     *
     * @param string $sql_query
     *
     * @return int
     */
    public function exec($sql_query)
    {
        $this->debug('>' . $sql_query);
        if ($this->logger) {
            Logger::debug($sql_query);
        }
        if (!$this->pdo) {
            throw new KumbiaException('No hay conexión para realizar esta acción');
        }
        $this->last_query = $sql_query;
        $this->pdo_statement = null;
        try {
            $result = $this->pdo->exec($sql_query);
            $this->affected_rows = $result;
            if ($result === false) {
                throw new KumbiaException($this->error(" al ejecutar <em>\"$sql_query\"</em>"));
            }

            return $result;
        } catch (PDOException $e) {
            throw new KumbiaException($this->error(" al ejecutar <em>\"$sql_query\"</em>"));
        }
    }

    /**
     * Cierra la Conexión al Motor de Base de datos.
     */
    public function close()
    {
        if ($this->pdo) {
            unset($this->pdo);

            return true;
        }

        return false;
    }

    /**
     * Devuelve fila por fila el contenido de un select.
     *
     * @param resource $pdo_statement
     * @param int      $opt
     *
     * @return array
     */
    public function fetch_array($pdo_statement = null, $opt = '')
    {
        if ($opt === '') {
            $opt = self::DB_BOTH;
        }

        if (!$pdo_statement) {
            $pdo_statement = $this->pdo_statement;
            if (!$pdo_statement) {
                return false;
            }
        }
        try {
            $pdo_statement->setFetchMode($opt);

            return $pdo_statement->fetch();
        } catch (PDOException $e) {
            throw new KumbiaException($this->error($e->getMessage()));
        }
    }

    /**
     * Devuelve el número de filas de un select.
     *
     * @param string $pdo_statement
     *
     * @return int
     */
    public function num_rows($pdo_statement = null)
    {
        if (!$pdo_statement) {
            $pdo_statement = $this->pdo_statement;
        }
        return $pdo_statement instanceof PDOStatement ? $pdo_statement->columnCount() : 0;
    }

    /**
     * Devuelve el nombre de un campo en el resultado de un select.
     *
     * @param int      $number
     * @param resource $pdo_statement
     *
     * @return string
     */
    public function field_name($number, $pdo_statement = null)
    {
        if (!$this->pdo) {
            throw new KumbiaException('No hay conexión para realizar esta acción');
        }
        if (!$pdo_statement) {
            $pdo_statement = $this->pdo_statement;
            if (!$pdo_statement) {
                return false;
            }
        }
        try {
            $meta = $pdo_statement->getColumnMeta($number);

            return $meta['name'];
        } catch (PDOException $e) {
            throw new KumbiaException($this->error($e->getMessage()));
        }
    }

    /**
     * Se Mueve al resultado indicado por $number en un select (No soportado por PDO).
     *
     * @param int          $number
     * @param PDOStatement $pdo_statement
     *
     * @return bool
     */
    public function data_seek($number, $pdo_statement = null)
    {
        return false;
    }

    /**
     * Número de Filas afectadas en un insert, update o delete.
     *
     * @param resource $pdo_statement
     *
     * @return int
     */
    public function affected_rows($pdo_statement = null)
    {
        if (!$this->pdo) {
            throw new KumbiaException('No hay conexión para realizar esta acción');
        }

        if ($pdo_statement instanceof PDOStatement) {
            try {
                $row_count = $pdo_statement->rowCount();
                if ($row_count === false) {
                    throw new KumbiaException($this->error(" al ejecutar SQL"));
                }
                return $row_count;
            } catch (PDOException $e) {
                throw new KumbiaException($this->error($e->getMessage()));
            }
        }
        return $this->affected_rows;
    }

    /**
     * Devuelve el error de PDO.
     *
     * @return string
     */
    public function error($err = '')
    {
        $error = '';
        if ($this->pdo) {
            $error = $this->pdo->errorInfo()[2];
        }
        $this->last_error .= $error . ' [' . $err . ']';
        if ($this->logger) {
            Logger::error($this->last_error);
        }

        return $this->last_error;
    }

    /**
     * Devuelve el número de error PDO.
     *
     * @return int
     */
    public function no_error($number = 0)
    {
        if ($this->pdo) {
            $number = $this->pdo->errorInfo()[1];
        }

        return $number;
    }

    /**
     * Devuelve el último id autonumérico generado en la BD.
     *
     * @return string
     */
    public function last_insert_id($table = '', $primary_key = '')
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Inicia una transacción si es posible.
     */
    public function begin()
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Cancela una transacción si es posible.
     */
    public function rollback()
    {
        return $this->pdo->rollBack();
    }

    /**
     * Hace commit sobre una transacción si es posible.
     */
    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * Agrega comillas o simples segun soporte el RBDM.
     *
     * @return string
     */
    public static function add_quotes($value)
    {
        return "'" . addslashes($value) . "'";
    }

    /**
     * Realiza una inserción.
     *
     * @param string $table
     * @param array  $values
     * @param array  $fields
     *
     * @return int
     */
    public function insert($table, array $values, $fields = null)
    {
        if (!count($values)) {
            throw new KumbiaException("Imposible realizar inserción en $table sin datos");
        }
        if (is_array($fields)) {
            $insert_sql = "INSERT INTO $table (" . join(',', $fields) . ') VALUES (' . join(',', $values) . ')';
        } else {
            $insert_sql = "INSERT INTO $table VALUES (" . join(',', $values) . ')';
        }

        return $this->exec($insert_sql);
    }

    /**
     * Actualiza registros en una tabla.
     *
     * @param string $table
     * @param array  $fields
     * @param array  $values
     * @param string $where_condition
     *
     * @return int
     */
    public function update($table, array $fields, array $values, $where_condition = null)
    {
        $update_sql = "UPDATE $table SET ";
        if (count($fields) !== count($values)) {
            throw new KumbiaException('El número de valores a actualizar no es el mismo de los campos');
        }
        $i = 0;
        $update_values = array();
        foreach ($fields as $field) {
            $update_values[] = $field . ' = ' . $values[$i];
            ++$i;
        }
        $update_sql .= join(',', $update_values);
        if ($where_condition != null) {
            $update_sql .= " WHERE $where_condition";
        }

        return $this->exec($update_sql);
    }

    /**
     * Borra registros de una tabla!
     *
     * @param string $table
     * @param string $where_condition
     */
    public function delete($table, $where_condition)
    {
        if ($where_condition) {
            return $this->exec("DELETE FROM $table WHERE $where_condition");
        }

        return $this->exec("DELETE FROM $table");
    }

    /**
     * Borra una tabla de la base de datos.
     *
     * @param string $table
     *
     * @return bool
     */
    public function drop_table($table, $if_exists = true)
    {
        if ($if_exists) {
            return $this->query("DROP TABLE IF EXISTS $table");
        }

        return $this->query("DROP TABLE $table");
    }

    /**
     * Devuelve un LIMIT para un SELECT del RBDM.
     *
     * @param string $sql consulta sql
     *
     * @return string
     */
    public function limit($sql)
    {
        $params = Util::getParams(func_get_args());

        if (isset($params['limit']) && is_numeric($params['limit'])) {
            $sql .= " LIMIT $params[limit]";
        }

        if (isset($params['offset']) && is_numeric($params['offset'])) {
            $sql .= " OFFSET $params[offset]";
        }

        return $sql;
    }

    /**
     * Devuelve la ultima sentencia sql ejecutada por el Adaptador.
     *
     * @return string
     */
    public function last_sql_query()
    {
        return $this->last_query;
    }
}
