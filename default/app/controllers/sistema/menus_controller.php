<?php

/**
 * Descripcion: Controlador que se encarga de la gestión de los menús del sistema
 *
 * @category
 * @package     Controllers
 */

Load::models('sistema/menu');
class MenusController extends BackendController
{

    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter()
    {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Gestión de menús';
    }

    /**
     * Método principal
     */
    public function index()
    {
        Redirect::toAction('listar');
    }

    /**
     * Método para listar
     */
    public function listar($order = 'order.posicion.asc', $page = 'page.1')
    {
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $menu = new Menu();
        $this->menus = $menu->getListadoEdicion(Menu::BACKEND);
        $this->front = $menu->getListadoEdicion(Menu::FRONTEND);
        $this->order = $order;
        $this->page_title = 'Listado de menús del sistema';
    }

    /**
     * Método para agregar
     */
    public function agregar()
    {
        if (Input::hasPost('menu')) {
            if (Menu::setMenu('create', Input::post('menu'), array('activo' => Menu::ACTIVO))) {
                if (APP_AJAX) {
                    DwResponse::sendSuccess('El menú se ha creado correctamente!', null, '/sistema/menus/listar', true);
                } else {
                    Flash::valid('El menú se ha creado correctamente!');
                    return Redirect::toAction('listar');
                }
            } else {
                if (APP_AJAX) {
                    DwResponse::sendError('Error al crear el menú');
                }
            }
        }
        $this->page_title = 'Agregar menú';
    }

    /**
     * Método para editar
     */
    public function editar($key)
    {
        if (!$id = DwSecurity::getKey($key, 'upd_menu', 'int')) {
            return Redirect::toAction('listar');
        }

        $menu = new Menu();
        if (!$menu->find_first($id)) {
            Flash::error('Lo sentimos, pero no se ha podido establecer la información del menú');
            return Redirect::toAction('listar');
        }

        if ($menu->id <= 2) {
            Flash::warning('Lo sentimos, pero este menú no se puede editar.');
            return Redirect::toAction('listar');
        }

        if (Input::hasPost('menu')) {
            if (Menu::setMenu('update', Input::post('menu'), array('id' => $id))) {
                if (APP_AJAX) {
                    DwResponse::sendSuccess('El menú se ha actualizado correctamente!', null, '/sistema/menus/listar', true);
                } else {
                    Flash::valid('El menú se ha actualizado correctamente!');
                    return Redirect::toAction('listar');
                }
            } else {
                if (APP_AJAX) {
                    DwResponse::sendError('Error al actualizar el menú');
                }
            }
        }

        $this->menu = $menu;
        $this->page_title = 'Actualizar menú';
    }

    /**
     * Método para inactivar/reactivar
     */
    public function estado($tipo, $key)
    {
        if (!$id = DwSecurity::getKey($key, $tipo . '_menu', 'int')) {
            return Redirect::toAction('listar');
        }

        $menu = new Menu();
        if (!$menu->find_first($id)) {
            if (APP_AJAX) {
                DwResponse::sendError('No se pudo establecer la información del menú', null, '/sistema/menus/listar', true);
            } else {
                Flash::error('Lo sentimos, pero no se ha podido establecer la información del menú');
                return Redirect::toAction('listar');
            }
        } else {
            if ($menu->id <= 2) {
                if (APP_AJAX) {
                    DwResponse::sendError('Este menú no se puede editar', null, '/sistema/menus/listar', true);
                } else {
                    Flash::warning('Lo sentimos, pero este menú no se puede editar.');
                    return Redirect::toAction('listar');
                }
            } else {
                // Determinar el nuevo estado
                $nuevo_estado = ($tipo == 'inactivar') ? Menu::INACTIVO : Menu::ACTIVO;
                error_log("DEBUG: menu_id={$menu->id}, tipo=$tipo, activo_actual={$menu->activo}, nuevo_estado=$nuevo_estado");
                
                // Cambiar el estado
                $menu->activo = $nuevo_estado;
                if ($menu->save()) {
                    if (APP_AJAX) {
                        $msg = ($nuevo_estado == Menu::ACTIVO) ? 'El menú se ha reactivado correctamente!' : 'El menú se ha inactivado correctamente!';
                        DwResponse::sendSuccess($msg, null, '/sistema/menus/listar', true);
                    } else {
                        ($nuevo_estado == Menu::ACTIVO) ? Flash::valid('El menú se ha reactivado correctamente!') : Flash::valid('El menú se ha inactivado correctamente!');
                        return Redirect::toAction('listar');
                    }
                } else {
                    if (APP_AJAX) {
                        DwResponse::sendError('Error al cambiar el estado del menú');
                    } else {
                        Flash::error('Error al cambiar el estado del menú');
                        return Redirect::toAction('listar');
                    }
                }
            }
        }
    }

    /**
     * Método para eliminar
     */
    public function eliminar($key)
    {
        if (!$id = DwSecurity::getKey($key, 'eliminar_menu', 'int')) {
            return Redirect::toAction('listar');
        }

        $menu = new Menu();
        if (!$menu->find_first($id)) {
            Flash::error('Lo sentimos, pero no se ha podido establecer la información del menú');
            return Redirect::toAction('listar');
        }
        if ($menu->id <= 2) {
            Flash::warning('Lo sentimos, pero este menú no se puede eliminar.');
            return Redirect::toAction('listar');
        }
        try {
            if ($menu->delete()) {
                Flash::valid('El menú se ha eliminado correctamente!');
            } else {
                Flash::warning('Lo sentimos, pero este menú no se puede eliminar.');
            }
        } catch (KumbiaException $e) {
            Flash::error('Este menú no se puede eliminar porque se encuentra relacionado con otro registro.');
        }

        return Redirect::toAction('listar');
    }
}
