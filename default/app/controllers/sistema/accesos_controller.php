<?php

/**
 * Descripcion: Controlador que se encarga de la gestión de los accesos de los usuarios
 *
 * @category
 * @package     Controllers
 *
 */

class AccesosController extends BackendController
{

    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter()
    {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Accesos al sistema';
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
     * @param type $order Método de ordenamiento
     * @param type $page Número de página
     */
    public function listar()
    {
        $acceso             = new Acceso();
        $this->accesos      = $acceso->getListadoAcceso(NULL, 'todos', 'acceso_at DESC');
        $this->page_title   = 'Entrada y salida de usuarios';
    }
}
