<?php

/**
 *
 * Descripcion: Modelo para el manejo de usuarios
 *
 * @category
 * @package     Models
 */

Load::models('sistema/perfil', 'sistema/recurso', 'sistema/recurso_perfil', 'sistema/acceso');

class Usuario extends ActiveRecord
{

    //Se desabilita el logger para no llenar el archivo de "basura"
    public $logger = FALSE;

    /**
     * Constante para definir el perfil de Super Usuario
     */
    const SUPER_USUARIO = 1;

    /**
     * Constante para definir un usuario como activo
     */
    const ACTIVO = 1;

    /**
     * Constante para definir un usuario como inactivo
     */
    const BLOQUEADO = 2;

    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize()
    {
        $this->belongs_to('perfil');
    }

    /**
     * Método que devuelve el inner join con el estado_usuario
     * @return string
     */
    public static function getInnerEstado()
    {
        return "INNER JOIN (SELECT usuario_id, estado_usuario, descripcion, estado_usuario_at FROM (SELECT * FROM estado_usuario ORDER BY estado_usuario.id DESC ) AS estado_usuario GROUP BY estado_usuario.usuario_id ) AS estado_usuario ON estado_usuario.usuario_id = usuario.id ";
    }

    /**
     * Método para abrir y cerrar sesión
     * @param type $opt
     * @return boolean
     */
    public static function setSession($opt = 'open', $user = NULL, $pass = NULL, $mode = NULL)
    {
        if ($opt == 'close') { //Cerrar Sesión
            $usuario = Session::get('id');
            if (DwAuth::logout()) {
                //Registro la salida
                Acceso::setAcceso(Acceso::SALIDA, $usuario);
                return TRUE;
            }
            Flash::error(DwAuth::getError());
        } else if ($opt == 'open') { //Abrir Sesión
            if (DwAuth::isLogged()) {
                return TRUE;
            } else {
                if (DwForm::isValidToken()) { //Si el formulario es válido

                    if (DwAuth::login(array('login' => $user), array('password' => $pass), $mode)) {
                        $usuario = self::getUsuarioLogueado();
                        if ($usuario->perfil_id != Perfil::SUPER_USUARIO && ($usuario->estado != Usuario::ACTIVO)) {
                            DwAuth::logout();
                            Flash::error('Lo sentimos pero tu cuenta se encuentra inactiva. <br />Si esta información es incorrecta contacta al administrador del sistema.');
                            return false;
                        }

                        Session::set("ip", DwUtils::getIp());
                        Session::set('perfil', $usuario->perfil);
                        Session::set('email', $usuario->email);
                        Session::set('nombre', $usuario->nombre);
                        Session::set('apellido', $usuario->apellido ?? '');
                        Session::set('usuario', $usuario->login);
                        Session::set('foto', $usuario->fotografia);
                        Session::set('perfil_id', $usuario->perfil_id);
                        Session::set('estado_usuario', $usuario->estado ?? 1);
                        Session::set('usuario_id', $usuario->id);
                        //Registro el acceso
                        Acceso::setAcceso(Acceso::ENTRADA, $usuario->id);
                        if ($usuario->apellido != '') {
                            Flash::info("¡ Bienvenido <strong>$usuario->nombre" . ' ' . "$usuario->apellido</strong> !.");
                        } else {
                            Flash::info("¡ Bienvenido <strong>$usuario->nombre</strong> !.");
                        }
                        return TRUE;
                    } else {

                        Flash::error(DwAuth::getError());
                    }
                } else {
                    Flash::info('La llave de acceso ha caducado. <br>Por favor recarga la página');
                }
            }
        } else {
            Flash::error('No se ha podido establecer la sesión actual.');
        }
        return FALSE;
    }

    /**
     * Método para obtener la información de un usuario logueado
     * @return object Usuario
     */
    public static function getUsuarioLogueado()
    {
        $columns = 'usuario.*, perfil.perfil, perfil.plantilla';
        $join = "INNER JOIN perfil ON perfil.id = usuario.perfil_id ";
        $conditions = "usuario.id = '" . Session::get('id') . "' ";
        $obj = new Usuario();
        return $obj->find_first("columns: $columns", "join: $join", "conditions: $conditions");
    }


    /**
     * Método para listar los usuarios por perfil
     */
    public function getUsuarioPorPerfil($perfil, $order = 'order.nombre.asc')
    {
        $perfil = Filter::get($perfil, 'int');
        if (empty($perfil)) {
            return NULL;
        }
        $columns = 'usuario.*, perfil.perfil';
        $join = 'INNER JOIN perfil ON perfil.id = usuario.perfil_id ';
        $conditions = "perfil.id = $perfil";

        $order = $this->get_order($order, 'nombre', array(
            'login' => array(
                'ASC' => 'usuario.login ASC, usuario.nombre ASC',
                'DESC' => 'usuario.login DESC, usuario.nombre DESC'
            ),
            'nombre' => array(
                'ASC' => 'usuario.nombre ASC',
                'DESC' => 'usuario.nombre DESC'
            ),
            'email' => array(
                'ASC' => 'usuario.email ASC,  usuario.nombre ASC',
                'DESC' => 'usuario.email DESC,  usuario.nombre DESC'
            ),
            'estado' => array(
                'ASC' => 'usuario.estado ASC,  usuario.nombre ASC',
                'DESC' => 'usuario.estado DESC,  usuario.nombre DESC'
            )
        ));

        return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "order: $order");
    }

    /**
     * Método para buscar usuarios
     */
    public function getAjaxUsuario($field, $value, $order = '')
    {
        $value = Filter::get($value, 'string');
        if (strlen($value) <= 2 or ($value == 'none')) {
            return NULL;
        }
        $columns = 'usuario.*, perfil.perfil';
        $join = 'INNER JOIN perfil ON perfil.id = usuario.perfil_id ';
        $conditions = "usuario.perfil_id != " . Perfil::SUPER_USUARIO; //Por el super usuario

        $order = $this->get_order($order, 'nombre', array(
            'login' => array(
                'ASC' => 'usuario.login ASC, usuario.nombre ASC',
                'DESC' => 'usuario.login DESC, usuario.nombre DESC'
            ),
            'nombre' => array(
                'ASC' => 'usuario.nombre ASC',
                'DESC' => 'usuario.nombre DESC'
            ),
            'email' => array(
                'ASC' => 'usuario.email ASC, usuario.nombre ASC',
                'DESC' => 'usuario.email DESC, usuario.nombre DESC'
            ),
            'estado' => array(
                'ASC' => 'usuario.estado ASC, usuario.nombre ASC',
                'DESC' => 'usuario.estado DESC, usuario.nombre DESC'
            )
        ));

        //Defino los campos habilitados para la búsqueda
        $fields = array('login', 'nombre', 'apellido', 'email', 'perfil', 'estado');
        if (!in_array($field, $fields)) {
            $field = 'nombre';
        }

        $conditions .= " AND $field LIKE '%$value%'";
        return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "order: $order");
    }


    public function getListadoUsuario()
    {
        $columns = 'usuario.*, perfil.perfil ';
        $join   = 'INNER JOIN perfil ON perfil.id = usuario.perfil_id ';
        $conditions = "usuario.perfil_id != " . Perfil::SUPER_USUARIO; //Por el super usuario
        $conditions .= " AND usuario.id IS NOT NULL ";
        $order = 'nombre ASC';

        return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "order: $order");
    }

    /**
     * Método para crear/modificar un objeto de base de datos
     *
     * @param string $medthod: create, update
     * @param array $data: Data para autocargar el modelo
     * @param array $otherData: Data adicional para autocargar
     *
     * @return object ActiveRecord
     */
    public static function setUsuario($method, $data, $optData = null)
    {
        $obj = new Usuario($data);
        if ($optData) {
            $obj->dump_result_self($optData);
        }
        if (!empty($obj->id)) { //Si va a actualizar
            $old = new Usuario();
            $old->find_first($obj->id);
            if (!empty($obj->oldpassword)) { //Si cambia de claves
                if (empty($obj->password) or empty($obj->repassword)) {
                    Flash::error("Indica la nueva contraseña");
                    return false;
                }
                $obj->oldpassword = sha1($obj->oldpassword);
                if ($obj->oldpassword !== $old->password) {
                    Flash::error("La contraseña anterior no coincide con la registrada. Verifica los datos e intente nuevamente");
                    return false;
                }
            }
        }
        //Verifico si las contraseñas coinciden (password y repassword)
        if ((!empty($obj->password) && !empty($obj->repassword)) or ($method == 'create')) {
            if ($method == 'create' && (empty($obj->password))) {
                Flash::error("Indica la contraseña para el inicio de sesión");
                return false;
            }
            $obj->password = sha1($obj->password);
            $obj->repassword = sha1($obj->repassword);
            if ($obj->password !== $obj->repassword) {
                Flash::error('Las contraseñas no coinciden. Verifica los datos e intenta nuevamente.');
                return 'cancel';
            }
        } else {
            if (isset($obj->id)) { //Mantengo la contraseña anterior
                $obj->password = $old->password;
            }
        }
        //DwUtils::print_r($obj, $data, $optData);
        $rs = $obj->$method();
        if ($rs) {
            ($method == 'create') ? DwAudit::debug("Se ha registrado el usuario $obj->login en el sistema") : DwAudit::debug("Se ha modificado la información del usuario $obj->login");
        }
        return ($rs) ? $obj : FALSE;
    }

    /**
     * Método para verificar si existe un campo registrado
     */
    protected function _getRegisteredField($field, $value, $id = NULL)
    {
        $conditions = "$field = '$value'";
        $conditions .= (!empty($id)) ? " AND id != $id" : '';
        return $this->count("conditions: $conditions");
    }

    /**
     * Callback que se ejecuta antes de guardar/modificar
     */
    protected function before_save()
    {
        if (Session::get('perfil_id') != Perfil::SUPER_USUARIO) { //Solo el super usuario puede hacer esto
            //Verifico las exclusiones de los nombres de usuarios del config.ini
            $exclusion = DwConfig::read('config', array('custom' => 'login_exclusion'));
            $exclusion = explode(',', $exclusion);
            if (!empty($exclusion)) {
                if (in_array($this->login, $exclusion)) {
                    Flash::error('El nombre de usuario indicado, no se encuentra disponible.');
                    return 'cancel';
                }
            }
        }
        //Verifico si el login está disponible
        if ($this->_getRegisteredField('login', $this->login, $this->id)) {
            Flash::error('El nombre de usuario no se encuentra disponible.');
            return 'cancel';
        }
        //Verifico si se encuentra el mail registrado
        if ($this->_getRegisteredField('email', $this->email, $this->id)) {
            Flash::error('El correo electrónico ya se encuentra registrado o no se encuentra disponible.');
            return 'cancel';
        }
        $this->datagrid = Filter::get($this->datagrid, 'int');
    }

    /**
     * Callback que se ejecuta despues de insertar un usuario
     */
    protected function after_create() {}

    /**
     * Método para obtener la información de un usuario
     * @return type
     */
    public function getInformacionUsuario($usuario)
    {
        //echo '<pre>';print_r($usuario);sleep(2);
        $usuario = Filter::get($usuario, 'int');
        if (!$usuario) {
            return NULL;
        }
        $columns = 'usuario.*, perfil.perfil, perfil.plantilla';
        $join = 'INNER JOIN perfil ON perfil.id = usuario.perfil_id ';
        $conditions = "usuario.id = $usuario";
        return $this->find_first("columns: $columns", "join: $join", "conditions: $conditions");
    }

    public static function setEstadoUsuario($accion, $data, $optData = NULL)
    {
        $accion = strtolower($accion);
        $obj = new Usuario($data);
        if ($optData) {
            $obj->dump_result_self($optData);
        }
        //Verifico el estado actual
        $estado = $obj->estado;

        //Verifico las acciones
        if ($accion == 'registrar') {
            $obj->estado = self::ACTIVO;
        } else if (($accion == 'bloquear') && (empty($estado) or $estado == self::ACTIVO)) {
            $obj->estado = self::BLOQUEADO;
        } else if (($accion == 'reactivar') && ($estado != self::ACTIVO)) {
            $obj->estado = self::ACTIVO;
        } else {
            return FALSE;
        }
        return $obj->save($obj->estado);
    }
}
