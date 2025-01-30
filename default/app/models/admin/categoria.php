<?php
class Categoria extends ActiveRecord
{
    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize()
    {
        $this->has_many('producto');
    }
}
