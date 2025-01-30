<?php

/**
 *
 * Descripcion: Controlador que se encarga de la gestión de los usuarios del sistema
 *
 * @category
 * @package     Controllers
 */

Load::models('sistema/usuario');

class UsuariosController extends BackendController
{

    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter()
    {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Gestión de usuarios';
    }

    /**
     * Método principal
     */
    public function index()
    {
        Redirect::toAction('listar');
    }

    /**
     * Método para buscar
     */
    public function buscar($field = 'nombre', $value = 'none', $order = 'order.id.asc', $page = 1)
    {
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $field = (Input::hasPost('field')) ? Input::post('field') : $field;
        $value = (Input::hasPost('field')) ? Input::post('value') : $value;

        $usuario = new Usuario();
        $usuarios = $usuario->getAjaxUsuario($field, $value, $order, $page);
        if (empty($usuarios->items)) {
            Flash::info('No se han encontrado registros');
        }
        $this->usuarios = $usuarios;
        $this->order = $order;
        $this->field = $field;
        $this->value = $value;
        $this->page_title = 'Búsqueda de usuarios del sistema';
    }

    /**
     * Método para listar
     */
    public function listar()
    {
        $usuario = new Usuario();
        $this->usuarios = $usuario->getListadoUsuario();
        $this->page_title = 'Listado de usuarios del sistema';
    }

    /**
     * Método para agregar
     */
    public function agregar($enlace = NULL)
    {
        if (Input::hasPost('usuario')) {

            //DwUtils::print_r(Input::post('usuario'));
            Usuario::setUsuario('create', Input::post('usuario'), array('repassword' => Input::post('usuario.repassword'), 'tema' => 'default'));

            Flash::valid('El usuario se ha creado correctamente.');
            return Redirect::toAction('listar');
        }
        $this->page_title = 'Agregar usuario';
    }

    /**
     * Método para editar
     */
    public function editar($key)
    {
        if (!$id = Security::getKey($key, 'upd_usuario', 'int')) {
            Flash::error('Error de Seguridad para Editar el Usuario');
            return Redirect::toAction('listar');
        }

        $usuario = new Usuario();
        if (!$usuario->getInformacionUsuario($id)) {
            Flash::error('Lo sentimos, no se ha podido establecer la información del usuario');
            return Redirect::toAction('listar');
        }

        if (Input::hasPost('usuario')) {
            ActiveRecord::beginTrans();
            if (Usuario::setUsuario('update', Input::post('usuario'), array('repassword' => Input::post('usuario.repassword'), 'id' => $id, 'login' => $usuario->login))) {
                ActiveRecord::commitTrans();
                Flash::valid('El usuario se ha actualizado correctamente.');

                return Redirect::toAction("editar/$key/");
            } else {
                ActiveRecord::rollbackTrans();
            }
        }
        $this->temas = DwUtils::getFolders(dirname(APP_PATH) . '/public/css/backend/themes/');
        $this->usuario = $usuario;
        $this->page_title = 'Actualizar usuario';
    }

    /**
     * Método para inactivar/reactivar estado
     */
    public function estado($tipo, $key)
    {
        if (!$id = Security::getKey($key, $tipo . '_usuario', 'int')) {
            return Redirect::toAction('listar');
        }

        $usuario = new Usuario();
        if (!$usuario->getInformacionUsuario($id)) {
            Flash::error('Lo sentimos, no se ha podido establecer la información del usuario');
            return Redirect::toAction('listar');
        }

        // No pueden inactivar al usuario admin
        if ($usuario->login == "admin") {
            Flash::info('¡No puedés bloquear al Admin!');
            return Redirect::toAction('listar');
        }

        if ($tipo == 'reactivar' && $usuario->estado == Usuario::ACTIVO) {
            Flash::info('El usuario ya se encuentra activo.');
            return Redirect::toAction('listar');
        } else if ($tipo == 'bloquear' && $usuario->usuario == Usuario::BLOQUEADO) {
            Flash::info('El usuario ya se encuentra bloqueado.');
            return Redirect::toAction('listar');
        }

        if (Input::hasPost('estado_usuario')) {
            if (Usuario::setEstadoUsuario($tipo, Input::post('estado_usuario'), array('usuario_id' => $usuario->id))) {
                ($tipo == 'reactivar') ? Flash::valid('El usuario se ha reactivado correctamente!') : Flash::valid('El usuario se ha bloqueado correctamente!');
                return Redirect::toAction('listar');
            }
        }

        $this->page_title = ($tipo == 'reactivar') ? 'Reactivación de usuario' : 'Bloqueo de usuario';
        $this->usuario = $usuario;
    }

    /**
     * Método para ver
     */
    public function ver($key)
    {
        if (!$id = Security::getKey($key, 'shw_usuario', 'int')) {
            return Redirect::toAction('listar');
        }

        $usuario = new Usuario();
        if (!$usuario->getInformacionUsuario($id)) {
            Flash::error('Lo sentimos, no se ha podido establecer la información del usuario');
            return Redirect::toAction('listar');
        }

        $estado = new Usuario();
        $this->estados = $estado->getListadoUsuario($usuario->id);

        $acceso = new Acceso();
        $this->accesos = $acceso->getListadoAcceso($usuario->id, 'todos', 'order.fecha.desc');

        $this->key = $key;
        $this->usuario = $usuario;
        $this->page_title = 'Información del usuario';
    }


    /**
     * Método para ver los accesos
     */
    public function accesos($key, $page = 'page.1')
    {
        if (!$id = Security::getKey($key, 'shw_accesos', 'int')) {
            return Redirect::toAction('listar');
        }

        $usuario = new Usuario();
        if (!$usuario->getInformacionUsuario($id)) {
            Flash::error('Lo sentimos, no se ha podido establecer la información del usuario');
            return Redirect::toAction('listar');
        }

        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;

        $acceso = new Acceso();
        $this->accesos = $acceso->getListadoAcceso($usuario->id, 'todos', 'order.fecha.desc', $page);
        $this->key = $key;
        $this->usuario = $usuario;

        $this->page_title = 'Seguimiento a accesos del usuario';
    }

    /**
     * Método para subir imágenes
     */
    public function upload()
    {
        $upload = new DwUpload('fotografia', 'img/upload/personas/');
        $upload->setAllowedTypes('png|jpg|gif|jpeg');
        $upload->setEncryptName(TRUE);
        $upload->setSize('3MB', 170, 200, TRUE);
        if (!$data = $upload->save()) { //retorna un array('path'=>'ruta', 'name'=>'nombre.ext');
            $data = array('error' => TRUE, 'message' => $upload->getError());
        }
        sleep(1); //Por la velocidad del script no permite que se actualize el archivo
        $this->data = $data;
        View::json_especial();
    }

    public function cambiarEstado($key)
    {
        View::select(null);

        if (Input::isAjax()) {
            $id = Security::getKey($key, 'change_status');
            $estado = Input::post('estado');

            $usuario = new Usuario();
            if ($usuario->find_first($id)) {
                $usuario->estado = $estado;
                if ($usuario->update()) {
                    $response = array(
                        'status' => 'success',
                        'message' => 'Estado actualizado correctamente'
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Error al actualizar el estado'
                    );
                }
            }

            echo json_encode($response);
        }
    }
}
