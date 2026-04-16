<?php

/**
 * Controlador Unidades
 *
 * @category    Controller
 * @package     Administracion
 */

Load::models('administracion/unidad');

class UnidadesController extends BackendController
{
    protected function before_filter()
    {
        $this->page_module = 'Unidades';
    }

    public function index()
    {
        return Redirect::toAction('listar');
    }

    public function listar()
    {
        $obj = new Unidad();
        $this->unidads = $obj->find();
        $this->page_title = 'Listado de Unidad';
    }

    public function agregar()
    {
        if (Input::hasPost('unidad')) {
            $obj = new Unidad(Input::post('unidad'));
            if ($obj->save()) {
                if ($this->ajax_mode) {
                    DwResponse::sendSuccess('El unidad se ha registrado correctamente!', null, PUBLIC_PATH . 'administracion/unidades/listar/');
                }
                Flash::valid('El unidad se ha registrado correctamente!');
                return Redirect::toAction('listar');
            }
            if ($this->ajax_mode) {
                DwResponse::sendValidation($obj->getError());
            }
            Flash::error($obj->getError());
        }
        $this->page_title = 'Agregar Unidad';
    }

    public function editar($key)
    {
        if (!$id = DwSecurity::getKey($key, 'upd_unidad', 'int')) {
            if ($this->ajax_mode) {
                DwResponse::sendError('Clave inválida');
            }
            return Redirect::toAction('listar');
        }

        $obj = new Unidad();
        if (!$obj->find_first($id)) {
            if ($this->ajax_mode) {
                DwResponse::sendError('No se pudo establecer la información del unidad');
            }
            Flash::error('Lo sentimos, no se pudo establecer la información del unidad');
            return Redirect::toAction('listar');
        }

        if (Input::hasPost('unidad')) {
            if (Input::post('unidad.id') != $id) {
                if ($this->ajax_mode) {
                    DwResponse::sendError('Ha ocurrido un error transformando la información');
                }
                Flash::error('Ha ocurrido un error transformando la información del unidad');
                return Redirect::toAction('listar');
            }
            $obj = new Unidad(Input::post('unidad'));
            if ($obj->update()) {
                if ($this->ajax_mode) {
                    DwResponse::sendSuccess('El unidad se ha actualizado correctamente!', null, PUBLIC_PATH . 'administracion/unidades/listar/');
                }
                Flash::valid('El unidad se ha actualizado correctamente!');
                return Redirect::toAction('listar');
            }
            if ($this->ajax_mode) {
                DwResponse::sendValidation($obj->getError());
            }
            Flash::error($obj->getError());
        }

        $this->unidad = $obj;
        $this->page_title = 'Actualizar Unidad';
    }

    public function eliminar($key)
    {
        if (!$id = DwSecurity::getKey($key, 'del_unidad', 'int')) {
            if ($this->ajax_mode) {
                DwResponse::sendError('Clave inválida');
            }
            return Redirect::toAction('listar');
        }

        $obj = new Unidad();
        if (!$obj->find_first($id)) {
            if ($this->ajax_mode) {
                DwResponse::sendError('No se pudo establecer la información del unidad');
            }
            Flash::error('Lo sentimos, no se pudo establecer la información del unidad');
            return Redirect::toAction('listar');
        }

        if (!$obj->delete()) {
            if ($this->ajax_mode) {
                DwResponse::sendError('Ha ocurrido un error al intentar eliminar el unidad');
            }
            Flash::error('Ha ocurrido un error al intentar eliminar el unidad');
            return Redirect::toAction('listar');
        }

        if ($this->ajax_mode) {
            DwResponse::sendSuccess('El unidad se ha eliminado correctamente!', null, PUBLIC_PATH . 'administracion/unidades/listar/');
        }
        Flash::valid('El unidad se ha eliminado correctamente!');
        return Redirect::toAction('listar');
    }
    
    public function ver($key)
    {
        if (!$id = DwSecurity::getKey($key, 'shw_unidad', 'int')) {
            return Redirect::toAction('listar');
        }

        $obj = new Unidad();
        if (!$obj->find_first($id)) {
            Flash::error('Lo sientenos, no se pudo establecer la información del unidad');
            return Redirect::toAction('listar');
        }

        $this->unidad = $obj;
        $this->page_title = 'Ver Unidad';
    }
}
