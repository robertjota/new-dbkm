<?php

class IndexController extends BackendController
{
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter()
    {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Dashboard';
    }

    public function index()
    {
        $this->page_title = 'Panel de control';
    }

    public function dashboard() {}
}
