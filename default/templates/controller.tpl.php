<?php
	/**
	 *
	 * Descripcion: Controlador %Item%
	 *
	 * @category
	 * @package     Controllers
	 */

Load::models('configuracion/%lcaseModels%');
	// CRUD CONTROLLER PARA: %Model%
	class %Item%Controller extends %BaseController% {
		/**
		 * Método que se ejecuta antes de cualquier acción
		 */
		protected function before_filter() {
			// Si es AJAX enviar solo el view
			if (Input::isAjax()) {
			View::template(NULL);
			}
			//Se cambia el nombre del módulo actual
			$this->page_module = 'Gestión para %Item%';
    	}

		//listar los elementos de %Model%
		function index()
		{
			$this->%lcaseModels% = (new %Model%)->find();
		}

		//crear un nuevo elemento de %Model%
		function add()
		{
			if (Input::hasPost('%lcaseModel%')) {
				$%lcaseModel% = new %Model%(Input::post('%lcaseModel%'));
				if ($%lcaseModel%->create()) {
					Input::delete('%lcaseModel%');
					Flash::valid('Elemento creado exitosamente');
				} else {
					Flash::error('Elemento no pudo ser creado.');
				}
			}
		}

		//editar un elemento de %Model%
		function edit($id)
		{
			$%lcaseModel% = (new %Model%)->find_first($id);
			if (Input::hasPost('%lcaseModel%')) {
				if ($%lcaseModel%->update(Input::post('%lcaseModel%'))) {
					Flash::valid('Elemento actualizado exitosamente');
				} else {
					Flash::error('Elemento no pudo ser actualizado.');
				}
			}
			$this->%lcaseModel% = $%lcaseModel%;
		}

		function delete($id)
		{
			$%lcaseModel% = (new %Model%)->find_first($id);
			if ($%lcaseModel%->delete()) {
				Flash::valid('Elemento eliminado exitosamente');
			} else {
				Flash::error('Elemento no pudo ser eliminado.');
			}
			Redirect::to(); //redirección a index
		}

		function show($id)
		{
			$this->%lcaseModel% = (new %Model%)->find_first($id);
		}

	}
