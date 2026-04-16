<?php

require_once CORE_PATH . 'libs/kumbia_active_record/behaviors/paginate.php';

/**
 *
 * Extensión del paginador del core para Datatables
 *
 * @category    Paginación
 * @package     Libs
 */

class DwPaginate extends Paginator
{

    public static function paginate($model)
    {
        $params = Util::getParams(func_get_args());
        $page_number = isset($params['page']) ? Filter::get($params['page'], 'numeric') : 1;
        $per_page = isset($params['per_page']) ? Filter::get($params['per_page'], 'numeric') : 500;
        $counter = ($page_number > 1) ? (($page_number * $per_page) - ($per_page - 1)) : 1;

        $page = parent::paginate($model, 'page: ' . $page_number, 'per_page: ' . $per_page);

        if ($page->current > $page->total && $page->count > 0) {
            $url = Router::get('route');
            $url = explode('pag', $url);
            $url = trim($url[0], '/');
            Flash::error('La página solicitada no se encuentra en el paginador.  <br />' . DwHtml::link($url, 'Regresar a la página 1'));
            $page->prev = false;
        }

        $page->counter = ($page->count >= $counter) ? $counter : 1;
        $page->size = $page->count;
        $page->total_page = $page->total;
        $page->uno = 1;

        return $page;
    }

    public static function paginate_by_sql($model, $sql)
    {
        $params = Util::getParams(func_get_args());
        $page_number = isset($params['page']) ? Filter::get($params['page'], 'numeric') : 1;
        $per_page = isset($params['per_page']) ? Filter::get($params['per_page'], 'numeric') : 500;
        $counter = ($page_number > 1) ? (($page_number * $per_page) - ($per_page - 1)) : 1;

        $page = parent::paginate_by_sql($model, $sql, 'page: ' . $page_number, 'per_page: ' . $per_page);

        if ($page->current > $page->total && $page->count > 0) {
            $url = Router::get('route');
            $url = explode('pag', $url);
            $url = trim($url[0], '/');
            Flash::error('La página solicitada no se encuentra en el paginador.  <br />' . DwHtml::link($url, 'Regresar a la página 1'));
            $page->prev = false;
        }

        $page->counter = ($page->count >= $counter) ? $counter : 1;
        $page->size = $page->count;
        $page->total_page = $page->total;

        return $page;
    }
}