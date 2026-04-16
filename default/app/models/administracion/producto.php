<?php

/**
 * Modelo Producto
 *
 * @category    Model
 * @package     Admin
 */

class Producto extends ActiveRecord
{
    const ACTIVO = 1;
    const INACTIVO = 2;

    protected function initialize()
    {
        $this->belongs_to('categoria');
        $this->belongs_to('unidad');
    }
}