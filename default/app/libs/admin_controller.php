<?php

/**
 * @see Controller nuevo controller
 */
require_once CORE_PATH . 'kumbia/controller.php';

require_once APP_PATH . 'extensions/helpers/dw_form.php';


/**
 * Controlador para proteger los controladores que heredan
 * Para empezar a crear una convención de seguridad y módulos
 *
 * Todas las controladores heredan de esta clase en un nivel superior
 * por lo tanto los métodos aquí definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 */

//Cargo los modelos básicos

Load::models('sistema/usuario', 'sistema/menu');
abstract class AdminController extends Controller
{

    final protected function initialize()
    {
        $template = Config::get('config.application.template');
        if ($template) {
            file_exists(APP_PATH . 'views/_shared/templates/' . $template . '.phtml') ? View::template($template) : View::template('default');
        }

        if (!Auth::is_valid()) {
            // El usuario no es valido, lo mandamos al login
            Redirect::to("sistema/login");
            return false;
        }
    }

    final protected function finalize()
    {
    }
}
