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
        
        // Cargar config directamente del archivo
        $config = require APP_PATH . 'config/config.php';
        
        // Datos por defecto
        $empresa = array(
            'nombre' => '',
            'rif' => '',
            'direccion' => '',
            'telefono' => '',
            'email' => '',
            'web' => ''
        );
        
        // Cargar desde config si existe
        if (isset($config['custom']['empresa']) && is_array($config['custom']['empresa'])) {
            $empresa = array_merge($empresa, $config['custom']['empresa']);
        }
        
        $this->empresa = $empresa;
        
        // Procesar formulario
        $posted = Input::post('empresa');
        
        if (is_array($posted) && count($posted) > 0) {
            // Cargar config actual
            $fullConfig = require APP_PATH . 'config/config.php';
            $fullConfig['custom']['empresa'] = $posted;
            
            // Guardar
            $rs = DwConfig::write('config', $fullConfig['custom'], 'custom');
            
            // Subir logos si existen
            $this->uploadLogo('logo', 'logo-empresa');
            $this->uploadLogo('logo_mini', 'logo-mini');
            
            if ($rs) {
                Flash::valid('Datos guardados correctamente!');
            } else {
                Flash::error('Error al guardar los datos');
            }
            
            return Redirect::toAction('empresa');
        }
    }
    
    /**
     * Subir imagen de logo
     */
    private function uploadLogo($inputName, $fileName)
    {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
            // Usar la ruta correcta: one level up from APP_PATH + public/empresa
            $uploadDir = dirname(APP_PATH) . '/public/empresa/';
            
            Flash::info("Guardando en: $uploadDir");
            
            // Crear directorio si no existe
            if (!is_dir($uploadDir)) {
                $created = mkdir($uploadDir, 0777, true);
                Flash::info("Directorio creado: " . ($created ? 'SI' : 'NO'));
            }
            
            $tmpFile = $_FILES[$inputName]['tmp_name'];
            $ext = strtolower(pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION));
            
            // Extensiones permitidas
            $allowed = ['png', 'jpg', 'jpeg', 'gif', 'svg'];
            
            if (in_array($ext, $allowed)) {
                // Eliminar logos anteriores con otras extensiones
                foreach ($allowed as $oldExt) {
                    $oldFile = $uploadDir . $fileName . '.' . $oldExt;
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
                
                // Nuevo archivo destino
                $target = $uploadDir . $fileName . '.' . $ext;
                
                if (is_uploaded_file($tmpFile)) {
                    $result = move_uploaded_file($tmpFile, $target);
                } else {
                    $result = copy($tmpFile, $target);
                }
                
                if ($result && file_exists($target)) {
                    Flash::valid("Logo $fileName guardado ($ext)");
                } else {
                    Flash::error("Error al guardar $inputName");
                }
            } else {
                Flash::error("Extensión no permitida: $ext");
            }
        }
    }
}
