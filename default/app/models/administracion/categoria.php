<?php

/**
 * Modelo Categoria
 *
 * @category    Model
 * @package     Admin
 */

class Categoria extends ActiveRecord
{
    const ACTIVO = 1;
    const INACTIVO = 2;

    protected function initialize()
    {
        $this->has_many('producto');
    }
}