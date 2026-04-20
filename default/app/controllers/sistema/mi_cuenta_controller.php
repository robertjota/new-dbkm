<?php

/**
 * Descripcion: Controlador que se encarga de la gestión de las cuentas de usuario
 *
 * @category
 * @package     Controllers
 */

class MiCuentaController extends BackendController
{

    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter()
    {

        //Se cambia el nombre del módulo actual
        $this->page_module = 'Mi Cuenta';
    }

    /**
     * Método principal
     */
    public function index()
    {
        $usuario = new Usuario();
        if (!$usuario->getInformacionUsuario(Session::get('id'))) {
            Flash::info('Lo sentimos pero no se ha podido establecer tu información');
            return Redirect::to('dashboard');
        }

        // Verificar y agregar campo dark_mode si no existe
        $this->ensureDarkModeColumn();

        $perfil         = $usuario->perfil;
        $app_ajax_old   = $usuario->app_ajax;
        $tmp_usr        = $usuario;

        if (Input::hasPost('usuario')) {
            ActiveRecord::beginTrans();
            //DwUtils::print_r(Input::post('usuario'));
            $usuario = Usuario::setUsuario('update', Input::post('usuario'), array('repassword' => Input::post('usuario.repassword'), 'oldpassword' => Input::post('usuario.oldpassword'), 'id' => $usuario->id, 'login' => $usuario->login, 'perfil_id' => $usuario->perfil_id));

            if ($usuario) {
                ActiveRecord::commitTrans();

                // Actualizar dark_mode en sesión si cambió
                if (isset(Input::post('usuario')['dark_mode'])) {
                    Session::set('dark_mode', Input::post('usuario')['dark_mode']);
                }

                Flash::valid('El usuario se ha actualizado correctamente.');
                Flash::info('Reinicie la sesión para que los cambios se actualicen correctamente');
                $usuario->perfil = $perfil;
            } else {
                $usuario = $tmp_usr;
                ActiveRecord::rollbackTrans();
            }
        }

        $this->temas = DwUtils::getFolders(dirname(APP_PATH) . '/public/css/backend/themes/');
        $this->usuario = $usuario;
        $this->perfil = $perfil;
        $this->page_title = 'Actualizar mis datos';
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
            $data = array('error' => true, 'message' => $upload->getError());
        }
        sleep(1); //Por la velocidad del script no permite que se actualize el archivo
        $this->data = $data;
        View::json();
    }

    /**
     * Verificar y agregar campo dark_mode si no existe
     */
    private function ensureDarkModeColumn()
    {
        try {
            $db = Db::factory();
            // Verificar si la columna existe
            $result = $db->fetch_all("SHOW COLUMNS FROM usuarios LIKE 'dark_mode'");
            if (empty($result)) {
                // Agregar la columna
                $db->query("ALTER TABLE usuarios ADD COLUMN dark_mode ENUM('off', 'on') DEFAULT 'off' AFTER tema");
                Flash::info('Se ha agregado la opción de modo oscuro a tu perfil');
            }
        } catch (Exception $e) {
            // Silenciar errores
        }
    }
}
