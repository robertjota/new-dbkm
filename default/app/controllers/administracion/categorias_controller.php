<?php

/**
 * Controlador Categorias
 *
 * @category    Controller
 * @package     Admin
 */

Load::models('administracion/categoria');

class CategoriasController extends BackendController
{
    protected function before_filter()
    {
        $this->page_module = 'Categorias';
    }

    public function index()
    {
        return Redirect::toAction('listar');
    }

    public function listar()
    {
        $obj = new Categoria();
        $this->categorias = $obj->find();
        $this->page_title = 'Listado de Categoria';
    }

    public function agregar()
    {
        if (Input::hasPost('categoria')) {
            $obj = new Categoria(Input::post('categoria'));
            if ($obj->save()) {
                if ($this->ajax_mode) {
                    DwResponse::sendSuccess('La categoria se ha registrado correctamente!', null, '/administracion/categorias/listar');
                }
                Flash::valid('El categoria se ha registrado correctamente!');
                return Redirect::toAction('listar');
            }
            if ($this->ajax_mode) {
                DwResponse::sendValidation($obj->getError());
            }
            Flash::error($obj->getError());
        }
        $this->page_title = 'Agregar Categoria';
    }

    public function editar($key)
    {
        if (!$id = DwSecurity::getKey($key, 'upd_categoria', 'int')) {
            if ($this->ajax_mode) {
                DwResponse::sendError('Clave inválida');
            }
            return Redirect::toAction('listar');
        }

        $obj = new Categoria();
        if (!$obj->find_first($id)) {
            if ($this->ajax_mode) {
                DwResponse::sendError('No se pudo establecer la información de la categoria');
            }
            Flash::error('Lo sentimos, no se pudo establecer la información del categoria');
            return Redirect::toAction('listar');
        }

        if (Input::hasPost('categoria')) {
            if (Input::post('categoria.id') != $id) {
                if ($this->ajax_mode) {
                    DwResponse::sendError('Ha ocurrido un error transformando la información');
                }
                Flash::error('Ha ocurrido un error transformando la información del categoria');
                return Redirect::toAction('listar');
            }
            $obj = new Categoria(Input::post('categoria'));
            if ($obj->update()) {
                if ($this->ajax_mode) {
                    DwResponse::sendSuccess('La categoria se ha actualizado correctamente!', null, '/administracion/categorias/listar');
                }
                Flash::valid('El categoria se ha actualizado correctamente!');
                return Redirect::toAction('listar');
            }
            if ($this->ajax_mode) {
                DwResponse::sendValidation($obj->getError());
            }
            Flash::error($obj->getError());
        }

        $this->categoria = $obj;
        $this->page_title = 'Actualizar Categoria';
    }

    public function eliminar($key)
    {
        if (!$id = DwSecurity::getKey($key, 'del_categoria', 'int')) {
            if ($this->ajax_mode) {
                DwResponse::sendError('Clave inválida');
            }
            return Redirect::toAction('listar');
        }

        $obj = new Categoria();
        if (!$obj->find_first($id)) {
            if ($this->ajax_mode) {
                DwResponse::sendError('No se pudo establecer la información de la categoria');
            }
            Flash::error('Lo sentimos, no se pudo establecer la información del categoria');
            return Redirect::toAction('listar');
        }

        if (!$obj->delete()) {
            if ($this->ajax_mode) {
                DwResponse::sendError('Ha ocurrido un error al intentar eliminar la categoria');
            }
            Flash::error('Ha ocurrido un error al intentar eliminar el categoria');
            return Redirect::toAction('listar');
        }

        if ($this->ajax_mode) {
            DwResponse::sendSuccess('La categoria se ha eliminado correctamente!', null, '/administracion/categorias/listar');
        }
        Flash::valid('El categoria se ha eliminado correctamente!');
        return Redirect::toAction('listar');
    }
    
    public function ver($key)
    {
        if (!$id = DwSecurity::getKey($key, 'shw_categoria', 'int')) {
            if ($this->ajax_mode) {
                DwResponse::sendError('Clave inválida');
            }
            return Redirect::toAction('listar');
        }

        $obj = new Categoria();
        if (!$obj->find_first($id)) {
            if ($this->ajax_mode) {
                DwResponse::sendError('No se pudo establecer la información de la categoria');
            }
            Flash::error('Lo sentimos, no se pudo establecer la información del categoria');
            return Redirect::toAction('listar');
        }

        $this->categoria = $obj;
        $this->page_title = 'Ver Categoria';
    }
}
