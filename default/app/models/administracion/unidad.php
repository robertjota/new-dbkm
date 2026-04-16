<?php

/**
 * Modelo Unidad
 *
 * @category    Model
 * @package     Admin
 */

class Unidad extends ActiveRecord
{
    const ACTIVO = 1;
    const INACTIVO = 2;

    protected function initialize()
    {
        $this->has_many('producto');
    }
}