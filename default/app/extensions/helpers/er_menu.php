<?php
/**
 *
 * Extension para renderizar los menús
 *
 * @category    Helpers
 * @package     Helpers
 */

Load::models('sistema/menu');

class ErMenu {

    /**
     * Variable que contiene los menús
     */
    protected static $_main = null;

    /**
     * Variable que contiene el menu procesado
     */
    protected static $_menu = null;

    /**
     * Variable que contien los items del menú
     */
    protected static $_items = null;

    /**
     * Variable para indicar el nivel del item del menu
     */
    protected static $_level = 0;

    /**
     * Variabla para indicar el entorno
     */
    protected static $_entorno;

    /**
     * Variable para indicar el perfil
     */
    protected static $_perfil;


    /**
     * Método para cargar en variables los menús
     * @param type $perfil
     */
    public static function load($entorno, $perfil=NULL) {
        // Inicializamos las variables
        self::$_entorno = $entorno;
        self::$_perfil = $perfil;

        if (self::$_main === null) {
            self::$_main = [];
            self::$_level = 0;
            // Asignamos todos los menus activos en el entorno definido
            self::$_menu = (new Menu())->getMenuActivoByEntorno($entorno);

            // Asignamos todos los items del menu del perfil en el entorno
            $menu = new Menu();
            self::$_items = $menu->getMenuActivoByEntornoAndPerfil($entorno, $perfil);

            // Construimos el array del menu segun los items
            foreach (self::$_items as $item) {
                self::makeMenuArray($item);
            }
        }
    }

    /**
     * Devuelve el objecto del menu segun el id
     */
    private static function loadFather($id)
    {
        foreach (self::$_menu as $menu) {
            if ($menu->id === $id) {
                return $menu;
            }
        }
    }

    /**
     * Creamos el array de menu segun el objeto
     */
    private static function makeMenuArray($obj)
    {
        if (!array_key_exists($obj->id, self::$_main)) {
            self::$_main[$obj->id] = $obj;
        }
        if (!empty($obj->menu_id)) {
            $father = self::loadFather($obj->menu_id);
            self::makeMenuArray($father);
            return;
        }
    }

    private static function buildMenu($obj) {
        $child = false;
        foreach (self::$_main as $main) {
            if ($main->menu_id === $obj->id) {
                $child = true;
                break;
            }
        }
        if ($child) {
            $html = (self::$_level > 0) ? '<li class="dropdown-submenu">' : '<li class="dropdown">';
            $html.= DwHtml::link($obj->url, $obj->menu, array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'), $obj->icono, APP_AJAX);
            /*$html.= DwHtml::link($obj->url, $obj->menu.' <b class="caret"></b>', array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'), $obj->icono, APP_AJAX);*/
            $html.= '<ul class="dropdown-menu">';
            self::$_level++;
            foreach (self::$_main as $main) {
                if ($main->menu_id === $obj->id) {
                    $html .= self::buildMenu($main);
                }
            }
            self::$_level--;
            $html.= '</ul>';
        } else {
            $html = '<li>'.DwHtml::link($obj->url, $obj->menu, NULL, $obj->icono, APP_AJAX);
        }
        $html .= '</li>'.PHP_EOL;
        return $html;
    }

    public static function Desktop()
    {
        // $route = trim(Router::get('route'), '/');
        $html = '';
        if(self::$_main) {
            $html .= '<ul class="nav navbar-nav hidden-xs">';
            foreach (self::$_main as $main) {
                if (empty($main->menu_id)) {
                    $html .= self::buildMenu($main);
                }
            }
            $html .= '</ul>';
        }
        echo $html;
    }

    public static function Tablet()
    {
        // $route = trim(Router::get('route'), '/');
        $html = '';
        if(self::$_main) {
            $html .= '<ul class="nav navbar-nav visible-xs">';
            foreach (self::$_main as $main) {
                if (empty($main->menu_id)) {
                    $html .= self::buildMenu($main);
                }
            }
            $html .= '</ul>';
        }
        echo $html;
    }

}
