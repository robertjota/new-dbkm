<?php

Load::models('sistema/backup');

/**
 * Controlador para el manejo de backups
 */

class BackupsController extends BackendController
{
    protected $backup;

    public function before_filter()
    {
        $this->backup = new Backup();
        $this->page_module = 'Backups';
    }

    /**
     * Método principal
     */
    public function index()
    {
        Redirect::toAction('listar');
    }

    /**
     * Método para listar backups
     */
    public function listar($order = 'order.id.desc', $page = 'page.1')
    {
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $backups = $this->backup->getListadoBackup($order, $page);

        if (empty($backups->items)) {
            Flash::warning("Haga una copia de seguridad lo antes posible.");
        }

        $this->backups = $backups;
        $this->order = $order;
        $this->page_title = 'Lista de copias de seguridad';
    }

    /**
     * Método para crear un backup
     */
    public function crear()
    {
        if (Input::hasPost('backup')) {
            try {
                $backup = $this->backup->createBackup(Input::post('backup'));
                Flash::valid('Backup creado correctamente: ' . $backup->archivo);
                return Redirect::to('/sistema/backups/listar');
            } catch (Exception $e) {
                Flash::error($e->getMessage());
            }
        }
        $this->page_title = 'Crear copia de seguridad';
    }

    /**
     * Método para restaurar un backup
     */
    public function restaurar($key = '')
    {
        if (!Input::isAjax()) {
            Flash::error('Método incorrecto para restaurar el sistema.');
            return Redirect::toAction('listar');
        }

        try {
            $id = Security::getKey($key, 'restaurar_backup', 'int');
            $backup = $this->backup->restoreBackup($id);
            Flash::valid('Sistema restaurado desde: ' . $backup->archivo);
        } catch (Exception $e) {
            Flash::error($e->getMessage());
        }

        return View::ajax();
    }

    /**
     * Método para descargar un backup
     */
    public function descargar($key = '')
    {
        if (!$id = Security::getKey($key, 'descargar_backup', 'int')) {
            return Redirect::toAction('listar');
        }

        $backup = new Backup();
        if (!$backup->find_first($id)) {
            Flash::info('La copia de seguridad no está registrada en la base de datos');
            return Redirect::toAction('listar');
        }

        $file = APP_PATH . 'temp/backup/' . $backup->archivo;
        if (!is_file($file)) {
            Flash::warning('No pudimos localizar el archivo. Comuníquese con el administrador del sistema.');
            DwAudit::error("No se pudo encontrar la copia de seguridad $backup->archivo en el sistema");
            return Redirect::toAction('listar');
        }

        View::template(NULL);

        $this->backup = $backup;
    }
}
