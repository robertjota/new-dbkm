<?php

/**
 * Controlador Productos
 *
 * @category    Controller
 * @package     Admin
 */

Load::models('administracion/producto', 'administracion/categoria', 'administracion/unidad');

class ProductosController extends BackendController
{
    protected function before_filter()
    {
        $this->page_module = 'Productos';
    }

    public function index()
    {
        return Redirect::toAction('listar');
    }

    public function listar()
    {
        $producto = new Producto();
        $this->productos = $producto->find();
        $this->page_title = 'Listado de Productos';
    }

    public function agregar()
    {
        if (Input::hasPost('producto')) {
            $producto = new Producto(Input::post('producto'));
            if ($producto->save()) {
                if ($this->ajax_mode) {
                    DwResponse::sendSuccess('El producto se ha registrado correctamente!', null, '/administracion/productos/listar');
                }
                Flash::valid('El producto se ha registrado correctamente!');
                return Redirect::toAction('listar');
            }
            if ($this->ajax_mode) {
                DwResponse::sendValidation($producto->getError());
            }
            Flash::error($producto->getError());
        }
        $this->page_title = 'Agregar Producto';
    }

    public function editar($key)
    {
        if (!$id = DwSecurity::getKey($key, 'upd_producto', 'int')) {
            if ($this->ajax_mode) {
                DwResponse::sendError('Clave inválida');
            }
            return Redirect::toAction('listar');
        }

        $producto = new Producto();
        if (!$producto->find_first($id)) {
            if ($this->ajax_mode) {
                DwResponse::sendError('No se pudo establecer la información del producto');
            }
            Flash::error('Lo sentimos, no se pudo establecer la información del producto');
            return Redirect::toAction('listar');
        }

        if (Input::hasPost('producto')) {
            if (Input::post('producto.id') != $id) {
                if ($this->ajax_mode) {
                    DwResponse::sendError('Ha ocurrido un error transformando la información');
                }
                Flash::error('Ha ocurrido un error transformando la información del producto');
                return Redirect::toAction('listar');
            }
            $producto = new Producto(Input::post('producto'));
            if ($producto->update()) {
                if ($this->ajax_mode) {
                    DwResponse::sendSuccess('El producto se ha actualizado correctamente!', null, '/administracion/productos/listar');
                }
                Flash::valid('El producto se ha actualizado correctamente!');
                return Redirect::toAction('listar');
            }
            if ($this->ajax_mode) {
                DwResponse::sendValidation($producto->getError());
            }
            Flash::error($producto->getError());
        }

        $this->producto = $producto;
        $this->page_title = 'Actualizar Producto';
    }

    public function eliminar($key)
    {
        if (!$id = DwSecurity::getKey($key, 'del_producto', 'int')) {
            if ($this->ajax_mode) {
                DwResponse::sendError('Clave inválida');
            }
            return Redirect::toAction('listar');
        }

        $producto = new Producto();
        if (!$producto->find_first($id)) {
            if ($this->ajax_mode) {
                DwResponse::sendError('No se pudo establecer la información del producto');
            }
            Flash::error('Lo sentimos, no se pudo establecer la información del producto');
            return Redirect::toAction('listar');
        }

        if (!$producto->delete()) {
            if ($this->ajax_mode) {
                DwResponse::sendError('Ha ocurrido un error al intentar eliminar el producto');
            }
            Flash::error('Ha ocurrido un error al intentar eliminar el producto');
            return Redirect::toAction('listar');
        }

        if ($this->ajax_mode) {
            DwResponse::sendSuccess('El producto se ha eliminado correctamente!', null, '/administracion/productos/listar');
        }
        Flash::valid('El producto se ha eliminado correctamente!');
        return Redirect::toAction('listar');
    }
    
    public function ver($key)
    {
        if (!$id = DwSecurity::getKey($key, 'shw_producto', 'int')) {
            if ($this->ajax_mode) {
                DwResponse::sendError('Clave inválida');
            }
            return Redirect::toAction('listar');
        }

        $producto = new Producto();
        if (!$producto->find_first($id)) {
            if ($this->ajax_mode) {
                DwResponse::sendError('No se pudo establecer la información del producto');
            }
            Flash::error('Lo sentimos, no se pudo establecer la información del producto');
            return Redirect::toAction('listar');
        }

        $this->producto = $producto;
        $this->page_title = 'Ver Producto';
    }
}
