<?php
Load::models('admin/producto', 'admin/categoria', 'admin/unidad');
class ProductosController extends BackendController
{
    public $productos;

    protected function before_filter()
    {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Productos';
    }
    public function index()
    {
        Redirect::toAction('listar');
    }


    public function listar()
    {
        $this->page_title = 'Listado de Productos';
        $productos = (new Producto())->find();

        $this->productos = $productos;
    }

    public function agregar()
    {
        $this->page_title = 'Agregar Productos';

        // Verificamos que recibimos los datos via post
        if (Input::hasPost('producto')) {
            // Pasamos los valores post a sus respectivas variables
            if (Producto::setProducto('create', Input::post('producto'))) {
                Flash::valid('¡El registro se agregó con éxito!');
            }
            return Redirect::toAction('listar');
        }
    }

    /**
     * Método para editar
     */
    public function editar($key)
    {
        if (!$id = Security::getKey($key, 'upd_producto', 'int')) {
            Flash::error('Lo sentimos, Error de Seguridad');
            return Redirect::toAction('listar');
        }

        if (!$producto = (new Producto())->find_first($id)) {
            Flash::error('Lo sentimos, no se pudo configurar la información de registro');
            return Redirect::toAction('listar');
        }

        if (Input::hasPost('producto')) {
            if ($producto->setProducto('update', Input::post('producto'), array('id' => $id))) {
                Flash::valid('¡El registro ha sido actualizado con éxito!');
            } else {
                Flash::error('Lo sentimos, la información de registro no se pudo actualizar');
            }
            return Redirect::toAction('listar');
        }

        $this->producto = $producto;
        $this->page_title = 'Actualizar Productoes';
    }


    /**
     * Método para ver
     */
    public function ver($key)
    {
        if (!$id = Security::getKey($key, 'shw_producto', 'int')) {
            return Redirect::toAction('listar');
        }

        if (!$producto = (new Producto())->find_first($id)) {
            Flash::error('Lo sentimos, no se pudo configurar la información de registro');
            return Redirect::toAction('listar');
        }
        $this->producto = $producto;
        $this->page_title = 'Información del Productoes';
    }

    /**
     * Método para eliminar
     */
    public function eliminar($key)
    {
        if (!$id = Security::getKey($key, 'del_producto', 'int')) {
            Flash::error('Lo sentimos, Error de Seguridad');
            return Redirect::toAction('listar');
        }

        if (!$producto = (new Producto())->find_first($id)) {
            Flash::error('Lo sentimos, no se pudo configurar la información de registro');
            return Redirect::toAction('listar');
        }
        try {
            if ($producto->delete()) {
                DwAudit::debug("Se ha Eliminado en la tabla Producto $producto->nombre en el sistema");
                Flash::valid('El registro se ha eliminado correctamente!');
            } else {
                Flash::warning('Lo sentimos, pero este registro no se puede eliminar.');
            }
        } catch (Exception $e) {
            DwAudit::error("Error al eliminar en el Sistema:" . $e->getMessage());
            Flash::error('Este registro no se puede eliminar porque se encuentra relacionado con otro registro.');
        }
        return Redirect::toAction('listar');
    }
}
