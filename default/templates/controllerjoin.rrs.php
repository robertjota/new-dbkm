<?php
/**
 *
 * Descripcion: Controlador de %ControllerSpace%
 *
 * @category
 * @package     Controllers
 */

Load::models('%ModelDir%/%lcaseModel%');

class %ControllerCamel%Controller extends %BaseController% {

    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Gestión para %ControllerSpace%';
    }

    /**
     * Método principal
     */
    public function index() {
        Redirect::toAction('listar');
    }

    /**
     * Método para listar
     */
    public function listar() {
        $this->page_title = 'Listado de %ControllerSpace%';
        $this->%lcaseModels% = (new %ModelCamel%())->getListado%ModelCamel%($%Relacion%);
    }

    /**
     * Método para agregar
     */
    public function agregar() {
        // Verificamos que recibimos los datos via post
        if (Input::hasPost('%lcaseModel%')) {
            // Pasamos los valores post a sus respectivas variables
            if (%ModelCamel%::set%ModelCamel%('create', Input::post('%lcaseModel%'))) {
                Flash::valid('El registro fue agregado exitosamente!');
            }
            return Redirect::toAction('listar');
        }
        $this->page_title = 'Agregar %ControllerSpace%';
    }

    /**
     * Método para editar
     */
    public function editar($key) {
        if (!$id = Security::getKey($key, 'upd_%lcaseModel%', 'int')) {
            Flash::error('Lo sentimos, error de seguridad');
            return Redirect::toAction('listar');
        }

        if (!$%lcaseModel% = (new %ModelCamel%())->find_first($id)) {
            Flash::error('Lo sentimos, no se pudo configurar la información de registro');
            return Redirect::toAction('listar');
        }

        if (Input::hasPost('%lcaseModel%')) {
            if ($%lcaseModel%->set%ModelCamel%('update', Input::post('%lcaseModel%'), array('id'=>$id))) {
                Flash::valid('¡El registro ha sido actualizado con éxito!');
            } else {
                Flash::error('Lo sentimos, la información de registro no se pudo actualizar');
            }
            return Redirect::toAction('listar');
        }

        $this->temas = DwUtils::getFolders(dirname(APP_PATH).'/public/css/backend/themes/');
        $this->%lcaseModel% = $%lcaseModel%;
        $this->page_title = 'Actualizar %ControllerSpace%';
    }

    /**
     * Método para ver
     */
    public function ver($key) {
        if (!$id = Security::getKey($key, 'shw_%lcaseModel%', 'int')) {
           return Redirect::toAction('listar');
        }

        if (!$%lcaseModel% = (new %ModelCamel%())->find_first($id)) {
            Flash::error('Lo sentimos, no se pudo configurar la información de registro');
            return Redirect::toAction('listar');
        }
        $this->%lcaseModel% = $%lcaseModel%;
        $this->page_title = 'Información del %ControllerSpace%';
    }

    /**
     * Método para eliminar
     */
    public function eliminar($key) {
        if (!$id = Security::getKey($key, 'del_%lcaseModel%', 'int')) {
            Flash::error('Lo sentimos, error de seguridad');
            return Redirect::toAction('listar');
        }

        if (!$%lcaseModel% = (new %ModelCamel%())->find_first($id)) {
            Flash::error('Lo sentimos, no se pudo configurar la información de registro');
            return Redirect::toAction('listar');
        }
        try {
            if ($%lcaseModel%->delete()) {
                DwAudit::debug("Se ha Eliminado en la tabla %Model% $%lcaseModel%->nombre en el sistema");
                Flash::valid('El registro se ha eliminado correctamente!');
            } else {
                Flash::warning('Lo sentimos, pero este registro no se puede eliminar.');
            }
        } catch (Exception $e) {
            DwAudit::error("Error al eliminar en el Sistema:" . $e->getMessage());
            Flash::error('Este registro no se puede eliminar porque se encuentra relacionado con otro registro.');
        }
        return Redirect::toAction('listar');
    }
}

