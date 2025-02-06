<?php
/**
 *
 * Descripcion: Modelo de %ModelSpace%
 *
 * @category
 * @package Models
 */


class %ModelCamel% extends %ModelExtends% {

    //Se desabilita el logger para no llenar el archivo de "basura"
    public $logger = false;

    // Constante para definir %ModelSpace% como activo
    const ACTIVO = 1;

    // Constante para definir %ModelSpace% como inactivo
    const INACTIVO = 2;

    // Constante para definir %ModelSpace% visible en el backend
    const BACKEND = 1;

    // Constante para definir %ModelSpace% visible en el frontend
    const FRONTEND = 2;

    // Método para definir las relaciones y validaciones
    protected function initialize() {
        // Método para definir las relaciones 1 a 1
        //$this->belongs_to('tabla');
        // Método para definir las relaciones 1 a Muchos
        //$this->has_many('tabla', 'tabla1');
    }

    /**
     * Método que listar todos los registros
     * @param  string  $order
     * @param  integer $page
     * @return object
     */
    public function getListado%ModelCamel%() {
        $columns = '%ModelMin%.*';
        $conditions = '%ModelMin%.id IS NOT NULL';
        $order = 'nombre ASC';
        //importante se debe dejar siempre un espacio despues de los dos puntos
        return $this->find("columns: $columns", "conditions: $conditions", "order: $order");
    }

    /**
     * Método para crear/modificar un objeto de base de datos
     *
     * @param string $method: create, update
     * @param array $data: Data para autocargar los registros
     * @param array $optData: Data adicional para autocargar
     *
     * return object ActiveRecord
     */
    public static function set%ModelCamel%($method, $data, $optData=null) {
        //Se carga los datos con los de las tablas
        $obj = new %ModelCamel%($data);
        //Se carga información adicional al objeto
        if ($optData) {
            $obj->dump_result_self($optData);
        }

        $rs = $obj->$method();
        if ($rs) {
            $objeto = json_encode($obj);
            ($method == 'create') ? DwAudit::debug("Se ha Registrado en la tabla %ModelMin% lo siguiente: $objeto en el sistema") : DwAudit::debug("Se ha Modificado en la tabla %ModelMin% lo siguiente: $objeto");
        }
        return ($rs) ? $obj : false;
    }

    /**
     * Método para verificar si existe un campo registrado
     */
    protected function _getRegisteredField($field, $value, $id=null) {
        $conditions = "$field = '$value'";
        $conditions.= (!empty($id)) ? " AND id != $id" : '';
        return $this->count("conditions: $conditions");
    }

    /**
     * Callback que se ejecuta antes de guardar/modificar
     */
    protected function before_save() {
        //Verifico si el id está disponible
        if ($this->_getRegisteredField('nombre', $this->nombre, $this->id)) {
            if (Input::isAjax()) {
                View::select(null);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'El Nombre ya existe'
                ]);
                exit();
            } else {
                Flash::error('El Nombre ya existe.');
                return 'cancel';
            }
        }
    }
}
