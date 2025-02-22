<?php

/**
 *
 * Extension para el manejo de botones
 *
 * @category    Helpers
 * @package     Helpers
 */

class DwButton
{

    /**
     * Contador de botones
     * @var int
     */
    protected static $_counter = 1;

    /**
     * Método para crear un botón
     * @param type $title Título a mostrar
     * @param type $icon Icono a mostrar
     * @param type $attrs Atributos adicionales
     * @param type $text Texto a mostrar
     * @return type
     */
    public static function save($title = 'Guardar', $icon = 'fa-save', $attrs = NULL, $text = 'Guardar')
    {
        if (is_array($attrs) or empty($attrs)) {
            $attrs['class'] = (empty($attrs['class'])) ? 'btn-success' : $attrs['class'];
            $attrs['title'] = $title;
        }
        return self::showButton($icon, $attrs, $text, 'submit');
    }

    /**
     * Método para resetear un formulario
     * @param type $form ID del formulario
     * @param type $formUpdate Indica si el formulario es de modificación o creación
     * @param type $icon Icono a mostrar
     * @return type
     */
    public static function reset($form = 'form-1', $formUpdate = FALSE, $icon = 'fa-undo')
    {
        $title = (!$formUpdate) ? 'Limpiar' : 'Resetear Formulario';
        $attrs = array();
        $attrs['class'] = 'btn-info';
        $attrs['title'] = $title;
        $attrs['onclick'] = "document.getElementById('$form').reset();";
        return self::showButton($icon, $attrs, 'clear', 'button');
    }

    /**
     * Método para cancelar un formulario
     * @param type $redir Página a redirigir al presionar el botón
     * @param type $title Título a mostrar
     * @param type $icon Icono a mostrar
     * @return type
     */
    public static function cancel($redir = NULL, $title = '', $icon = 'fa-ban', $etq = 'CANCELAR')
    {
        $attrs = [];
        $attrs['class'] = 'btn-secondary';
        $attrs['title'] = empty($title) ? 'Cancelar' : $title;
        if (empty($redir)) {
            $attrs['onclick'] = 'history.back()';
            return self::showButton($icon, $attrs, 'Cancelar', 'button');
        } else {
            return DwHtml::button($redir, $etq, $attrs, $icon);
        }
    }

    /**
     * Método para crear un botón para regresar a la página anterior
     * @param type $redir Página a redirigir al presionar el botón
     * @param type $title Título a mostrar
     * @param type $icon Icono a mostrar
     * @return type
     */
    public static function back($redir = NULL, $title = '', $icon = 'fa-undo')
    {
        $attrs = array();
        $attrs['class'] = 'btn-warning';
        $attrs['rel'] = 'tooltip';
        $attrs['title'] = empty($title) ? 'ATRAS' : $title;
        if (empty($redir)) {
            $attrs['class'] .= ' btn-back';
            return self::showButton($icon, $attrs, empty($title) ? 'ATRAS' : $title, 'button');
        } else {
            return DwHtml::button($redir, empty($title) ? 'ATRAS' : $title, $attrs, $icon);
        }
    }

    /**
     * Método para crear un botón para regresar a la página anterior
     * @param type $redir Página a redirigir al presionar el botón
     * @param type $title Título a mostrar
     * @param type $icon Icono a mostrar
     * @return type
     */
    public static function redir($redir = NULL, $title = '', $icon = 'fa-external-link', $etq = 'IR A')
    {
        $attrs = array();
        $attrs['class'] = 'btn-warning';
        $attrs['rel'] = 'tooltip';
        $attrs['title'] = empty($title) ? "IR A" : $title;
        if (empty($redir)) {
            $attrs['class'] .= ' btn-back';
            return self::showButton($icon, $attrs, 'ir a', 'button');
        } else {
            return DwHtml::button($redir, $etq, $attrs, $icon);
        }
    }

    /**
     * Método para crear un botón para regresar a la página anterior
     * @param type $redir Página a redirigir al presionar el botón
     * @param string $title Título a mostrar
     * @param string $icon Icono a mostrar
     * @return type
     */
    public static function aprobar($redir = NULL, $title = '', $icon = 'fa-external-link', $etq = 'IR A')
    {
        $attrs = array();
        $attrs['class'] = 'btn-danger btn-xs cerrar-modal';
        $attrs['rel'] = 'tooltip';
        $attrs['title'] = empty($title) ? "IR A" : $title;
        if (empty($redir)) {
            $attrs['class'] .= ' btn-back';
            return self::showButton($icon, $attrs, 'IR A', 'button');
        } else {
            return DwHtml::button($redir, $etq, $attrs, $icon);
        }
    }

    /**
     * Método para crear un botón para avanzar dentro de unos tabs
     * @return type
     */
    public static function nextTab($title = '', $icon = 'fa-forward')
    {
        $attrs = array();
        $attrs['class'] = 'btn-info';
        $attrs['title'] = empty($title) ? 'Siguiente' : $title;
        $attrs['class'] .= ' js-next-tab';
        return self::showButton($icon, $attrs, 'Siguiente', 'button', 'right');
    }

    /**
     * Método para crear un botón para avanzar dentro de unos tabs
     * @return type
     */
    public static function prevTab($title = '', $icon = 'fa-backward')
    {
        $attrs = array();
        $attrs['class'] = 'btn-info';
        $attrs['title'] = empty($title) ? 'Anterior' : $title;
        $attrs['class'] .= ' js-prev-tab';
        return self::showButton($icon, $attrs, 'Anterior', 'button');
    }

    /**
     * Método para crear un botón para imprimir reportes
     * @param string $path Ruta del controlador del módulo de reporte
     * @param string $file Tipos de formato de reporte
     * @param string $title (opcional) Titulo del botón
     * @param string $text (opcional) Texto a mostrar en el botón
     * @return type
     */
    public static function report($path, $files = 'html', $title = '', $text = '')
    {
        $path = '/reporte/' . trim($path, '/') . '/';
        $attrs = array();
        $attrs['class'] = 'btn-info js-report no-ajax';
        $attrs['title'] = 'Imprimir reporte';
        $attrs['data-report-title'] = (empty($title)) ? 'Imprimir reporte' : $title;
        $attrs['data-report-format'] = $files;
        if (empty($text)) {
            return DwHtml::button($path, '', $attrs, 'fa-print');
        } else {
            return DwHtml::button($path, strtoupper($text), $attrs, 'fa-print');
        }
    }

    /**
     * Método para crear un botón para envío de formularios
     * @param string $title Título a mostrar
     * @param string $icon Icono a mostrar
     * @param array $attrs Atributos adicionales
     * @param string $text Texto a mostrar
     * @return type
     */
    public static function submit($title = 'Guardar registro', $icon = 'fa-save', $attrs = NULL, $text = 'Guardar')
    {
        return self::save($title, $icon, $attrs, $text);
    }


    /**
     * Método que se encarga de crear el botón
     * @param string $icon
     * @param array $attrs
     * @param string $text
     * @param string $type
     * @return type
     */
    public static function showButton($icon = '', $attrs = array(), $text = '', $type = 'button', $iconAlign = 'left')
    {
        $text = Filter::get($text, 'upper');
        $attrs['class'] = 'btn ' . $attrs['class'];
        if (!preg_match("/\btext-bold\b/i", $attrs['class'])) {
            $attrs['class'] = $attrs['class'] . ' text-bold';
        }
        $attrs = Tag::getAttrs($attrs);
        $text = (!empty($text) && $icon) ? '<span class="hidden-xs">' . $text . '</span>' : $text;
        if ($icon) {
            if ($iconAlign !== 'left') {
                $text = $text . ' <i class="btn-icon-only fa ' . $icon . '"></i>';
            } else {
                $text = '<i class="btn-icon-only fa ' . $icon . '"></i> ' . $text;
            }
        }
        return "<button type=\"$type\" $attrs>$text</button>";
    }
}
