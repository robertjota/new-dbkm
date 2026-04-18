<?php

/**
 * Descripcion: Controlador que se encarga de la gestión de la configuración del sistema
 *
 * @category
 * @package     Controllers
 */

Load::models('sistema/sistema');

class ConfiguracionController extends BackendController
{

    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter()
    {
        //Se cambia el nombre del módulo actual
        $this->page_title = 'Configuración del sistema';
    }

    /**
     * Método principal para las configuraciones básicas
     */
    public function index()
    {
        if (Input::hasPost('application') && Input::hasPost('custom')) {
            try {
                Sistema::setConfig(Input::post('application'), 'application');
                Sistema::setConfig(Input::post('custom'), 'custom');
                Flash::valid('El archivo de configuración se ha actualizado correctamente!');
            } catch (KumbiaException $e) {
                Flash::error('Oops!. Se ha realizado algo mal internamente. <br />Intentalo de nuevo!.');
            }
            Input::delete('application');
            Input::delete('custom');
        }
        $this->config = DwConfig::read('config', '', true);
        $this->page_module = 'Configuración general';
    }

    /**
     * Método para todas las configuraciones
     */
    public function config()
    {
        if (Input::hasPost('application') && Input::hasPost('custom')) {
            try {
                Sistema::setConfig(Input::post('application'), 'application');
                Sistema::setConfig(Input::post('custom'), 'custom');
                Flash::valid('El archivo de configuración se ha actualizado correctamente!');
            } catch (KumbiaException $e) {
                Flash::error('Oops!. Se ha realizado algo mal internamente. <br />Intentalo de nuevo!.');
            }
            Input::delete('application');
            Input::delete('custom');
        }
        $this->config = DwConfig::read('config', '', true);
        $this->page_module = 'Configuración general';
    }

    /**
     * Método para editar el routes
     */
    public function routes()
    {
        if (Input::hasPost('routes')) {
            try {
                Sistema::setRoutes(Input::post('routes'));
                Flash::valid('El archivo de enrutamiento se ha actualizado correctamente!');
            } catch (KumbiaException $e) {
                Flash::error('Oops!. Se ha realizado algo mal internamente. <br />Intentalo de nuevo!.');
            }
            Input::delete('routes');
            $_POST = array();
        }
        $this->routes = DwConfig::read('routes', '', true);
        $this->page_module = 'Configuración de enrutamientos';
    }

    /**
     * Método para editar el databases
     */
    public function databases()
    {
        if (Input::hasPost('development') && Input::hasPost('production')) {
            try {
                Sistema::setDatabases(Input::post('development'), 'development');
                Sistema::setDatabases(Input::post('production'), 'production');
                Flash::valid('El archivo de conexión se ha actualizado correctamente!');
            } catch (KumbiaException $e) {
                Flash::error('Oops!. Se ha realizado algo mal internamente. <br />Intentalo de nuevo!.');
            }
            Input::delete('databases');
        }
        $this->databases = DwConfig::read('databases', '', true);
        $this->page_module = 'Configuración de conexión';
    }

    /**
     * Método para verificar la conexión de la bd
     */
    public function test()
    {
        if (!Input::isAjax()) {
            Flash::error('Acceso incorrecto para la verificación del sistema.');
            return Redirect::to('dashboard');
        }
        if (!Input::hasPost('development') or !(Input::hasPost('production'))) {
            Flash::error('Oops!. No hemos recibido algún parámetro de configuración.');
        } else {
            if (Input::hasPost('development')) {
                Sistema::testConnection(Input::post('development'), 'development', true);
            }
            if (Input::hasPost('production')) {
                Sistema::testConnection(Input::post('production'), 'production', true);
            }
        }
        View::ajax();
    }

    /**
     * Método para resetear las configuraciones del sistema
     * @return type
     */
    public function reset()
    {
        try {
            if (Sistema::reset()) {
                Flash::valid('El sistema se ha reseteado correctamente!');
            }
        } catch (KumbiaException $e) {
            Flash::error('Se ha producido un error al resetear la configuración del sistema.');
        }
        return Redirect::toAction('index');
    }

    /**
     * Método para configurar los datos de la empresa
     */
    public function empresa()
    {
        $this->page_title = 'Datos de la Empresa';
        
        // Datos por defecto
        $this->empresa = array(
            'nombre' => 'Mi Empresa',
            'rif' => '',
            'direccion' => '',
            'telefono' => '',
            'email' => '',
            'web' => ''
        );
        
        // Intentar cargar del config - ruta correcta es config.custom.empresa
        try {
            $empresaData = Config::get('config.custom.empresa');
            if ($empresaData && is_array($empresaData)) {
                $this->empresa = array_merge($this->empresa, $empresaData);
            }
        } catch (Exception $e) {
            // Usar valores por defecto si hay error
        }
        
        if (Input::hasPost('empresa')) {
            $postData = Input::post('empresa');
            
            // Leer config actual - usar ruta correcta
            $config = Config::read('config');
            $config['custom']['empresa'] = $postData;
            
            // Guardar config
            $configFile = APP_PATH . 'config/config.php';
            $configContent = "<?php\n\nreturn " . var_export($config, true) . ";\n";
            file_put_contents($configFile, $configContent);
            
            // Procesar logo si se subió
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $logoExt = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
                if (in_array($logoExt, array('png', 'jpg', 'jpeg', 'gif', 'svg'))) {
                    $dir = PUBLIC_PATH . 'empresa';
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                    move_uploaded_file($_FILES['logo']['tmp_name'], $dir . '/logo-empresa.' . $logoExt);
                }
            }
            
            // Procesar logo mini si se subió
            if (isset($_FILES['logo_mini']) && $_FILES['logo_mini']['error'] === UPLOAD_ERR_OK) {
                $logoMiniExt = strtolower(pathinfo($_FILES['logo_mini']['name'], PATHINFO_EXTENSION));
                if (in_array($logoMiniExt, array('png', 'jpg', 'jpeg', 'gif', 'svg'))) {
                    $dir = PUBLIC_PATH . 'empresa';
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                    move_uploaded_file($_FILES['logo_mini']['tmp_name'], $dir . '/logo-mini.' . $logoMiniExt);
                }
            }
            
            Flash::valid('Los datos de la empresa se han guardado correctamente!');
            return Redirect::toAction('empresa');
        }
    }
}
