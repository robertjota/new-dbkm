<?php

/**
 * Robofile - Generador de CRUD para DBKM
 * 
 * Estructura:
 *   app/controllers/admin/{plural}_controller.php
 *   app/models/admin/{singular}.php
 *   app/views/admin/{plural}/*.phtml
 *   app/views/_shared/partials/admin/{plural}/form.phtml
 */

// Constante por defecto ya que el generador se ejecuta fuera del contexto de KumbiaPHP
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', '/');
}

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

class RoboFile extends \Robo\Tasks
{
    private $fs;
    
    public function __construct()
    {
        $this->fs = new Filesystem();
    }
    
    // Plural en español simple
    private function aplural($palabra)
    {
        $palabra = trim($palabra ?? '');
        $ultima = substr($palabra, -1);
        
        if ($ultima === 's') return $palabra;
        if ($ultima === 'z') return substr($palabra, 0, -1) . 'ces';
        return $palabra . 's';
    }
    
    /**
     * @description Generador interactivo
     */
    public function scaffold()
    {
        $this->say('<info>=== Generador CRUD DBKM ===</info>');
        
        $modulo = trim((string)$this->ask("Modulo (admin): "));
        if (empty($modulo)) $modulo = 'admin';
        
        $modelo = trim((string)$this->ask("Modelo (singular): "));
        if (empty($modelo)) {
            $this->say("<error>Debe ingresar un nombre de modelo</error>");
            return;
        }
        
        $modeloLower = strtolower($modelo);
        $controladorPlural = $this->aplural($modeloLower);
        
        $relaciones = [];
        if ($this->ask("Tiene relaciones? (s/n): ") === 's') {
            $this->say("  Relacion: Ingrese el nombre del modelo relacionado (ej: categoria, unidad, usuario)");
            $this->say("  Presione Enter sin ingresar nada para terminar");
            while (true) {
                $rel = trim((string)$this->ask("Relacion (ej: categoria): "));
                if (empty($rel)) break;
                $relaciones[] = $rel;
            }
        }
        
        $campos = trim((string)$this->ask("Campos (separados por coma): "));
        $camposArr = array_map('trim', explode(',', $campos));
        
        $this->say('<info>Generando...</info>');
        
        // Crear modelo (nombre de la tabla = lowercase)
        $this->crearModelo($modulo, $modeloLower, $relaciones);
        
        // Crear controlador
        $this->crearControlador($modulo, $controladorPlural, $modeloLower);
        
        // Crear vistas
        $this->crearVistas($modulo, $controladorPlural, $modeloLower, $camposArr);
        
        // Crear form partial
        $this->crearForm($modulo, $controladorPlural, $modeloLower, $camposArr);
        
        $this->say("<info>CRUD generado: $controladorPlural</info>");
    }
    
/**
 * @description CRUD completo
 * Usage: php robo crud "campos" singular plural [modulo]
 * Ejemplo: php robo crud "codigo,nombre" producto productos auxiliar
 */
public function crud($campos, $singular, $plural = null, $modulo = 'auxiliar')
{
    $modelo = trim((string)$singular);
    $modeloLower = strtolower($modelo);
    $modeloUpper = ucfirst($modelo);
    
    // Si no se proporciona plural, usar el provided o generar
    if ($plural === null) {
        $controladorPlural = $this->aplural($modeloLower);
    } else {
        $controladorPlural = trim((string)$plural);
    }
    
    $modulo = trim((string)$modulo);
    if (empty($modulo)) $modulo = 'auxiliar';
    
    $camposArr = array_map('trim', explode(',', $campos));
    
    $this->crearModelo($modulo, $modelo, []);
    $this->crearControlador($modulo, $controladorPlural, $modeloUpper, $modeloLower);
    $this->crearVistas($modulo, $controladorPlural, $modeloLower, $camposArr);
    $this->crearForm($modulo, $controladorPlural, $modeloLower, $camposArr);
    
    $this->say("<info>CRUD completo: $modulo/$controladorPlural (" . count($camposArr) . " campos)</info>");
}

/**
 * @description Crear solo modelo
 * Usage: php robo model singular [modulo]
 * Ejemplo: php robo model producto auxiliar
 */
public function model($nombre, $modulo = 'auxiliar')
{
    $modelo = trim((string)$nombre);
    $modulo = trim((string)$modulo);
    if (empty($modulo)) $modulo = 'auxiliar';
    
    $this->crearModelo($modulo, $modelo, []);
    
    $this->say("<info>Modelo: $modulo/$modelo</info>");
}

/**
 * @description Crear solo controlador con métodos
 * Usage: php robo controller plural [modulo]
 * Ejemplo: php robo controller productos auxiliar
 */
public function controller($nombre, $modulo = 'auxiliar')
{
    $controladorPlural = trim((string)$nombre);
    $modeloLower = strtolower(rtrim($controladorPlural, 's'));
    if ($modeloLower === $controladorPlural) {
        // Si no termina en 's', usar el mismo nombre
        $modeloLower = strtolower($controladorPlural);
    }
    $modeloUpper = ucfirst($modeloLower);
    $modulo = trim((string)$modulo);
    if (empty($modulo)) $modulo = 'auxiliar';
    
    $this->crearControlador($modulo, $controladorPlural, $modeloUpper, $modeloLower);
    
    $this->say("<info>Controlador: $modulo/$controladorPlural</info>");
}
    
    // ============ FUNCIONES PRIVADAS ============
    
    private function crearModelo($modulo, $modelo, $relaciones = [])
    {
        $directorio = "app/models/$modulo";
        if (!is_dir($directorio)) {
            mkdir($directorio, 0757, true);
        }
        
        $file = "$directorio/" . strtolower($modelo) . ".php";
        
        if (file_exists($file)) {
            $this->say("<warning>Modelo ya existe</warning>");
            return;
        }
        
        $relationsCode = '';
        if (!empty($relaciones)) {
            foreach ($relaciones as $rel) {
                $relationsCode .= "        \$this->belongs_to('$rel');\n";
            }
        }
        
        $contenido = "<?php

/**
 * Modelo " . ucfirst($modelo) . "
 *
 * @category    Model
 * @package     " . ucfirst($modulo) . "
 */

class " . ucfirst($modelo) . " extends ActiveRecord
{
    const ACTIVO = 1;
    const INACTIVO = 2;

    protected function initialize()
    {
" . ($relationsCode ? $relationsCode . "    }" : "        // Relations\n    }") . "
}
";
        
        file_put_contents($file, $contenido);
        $this->say("<info>Modelo: $file</info>");
    }
    
    private function crearControlador($modulo, $controlador, $modelo)
    {
        $directorio = "app/controllers/$modulo";
        if (!is_dir($directorio)) {
            mkdir($directorio, 0757, true);
        }
        
        $file = "$directorio/{$controlador}_controller.php";
        
        if (file_exists($file)) {
            $this->say("<warning>Controlador ya existe</warning>");
            return;
        }
        
        $modeloLower = strtolower($modelo);
        $modeloUpper = ucfirst($modelo);
        $modeloTitle = ucfirst($modeloLower);
        $keyUpd = 'upd_' . $modeloLower;
        $keyDel = 'del_' . $modeloLower;
        $keyShw = 'shw_' . $modeloLower;
        
        $contenido = "<?php

/**
 * Controlador " . ucfirst($controlador) . "
 *
 * @category    Controller
 * @package     " . ucfirst($modulo) . "
 */

Load::models('" . $modulo . "/" . strtolower($modelo) . "');

class " . ucfirst($controlador) . "Controller extends BackendController
{
    protected function before_filter()
    {
        \$this->page_module = '" . ucfirst($controlador) . "';
    }

    public function index()
    {
        return Redirect::toAction('listar');
    }

    public function listar()
    {
        \$obj = new " . $modeloUpper . "();
        \$this->" . $modeloLower . "s = \$obj->find();
        \$this->page_title = 'Listado de " . $modeloTitle . "';
    }

    public function agregar()
    {
        if (Input::hasPost('" . $modeloLower . "')) {
            \$obj = new " . $modeloUpper . "(Input::post('" . $modeloLower . "'));
            if (\$obj->save()) {
                if (\$this->ajax_mode) {
                    DwResponse::sendSuccess('El " . $modeloLower . " se ha registrado correctamente!', null, PUBLIC_PATH . '" . $modulo . "/" . $controlador . "/listar/');
                }
                Flash::valid('El " . $modeloLower . " se ha registrado correctamente!');
                return Redirect::toAction('listar');
            }
            if (\$this->ajax_mode) {
                DwResponse::sendValidation(\$obj->getError());
            }
            Flash::error(\$obj->getError());
        }
        \$this->page_title = 'Agregar " . $modeloTitle . "';
    }

    public function editar(\$key)
    {
        if (!\$id = DwSecurity::getKey(\$key, '" . $keyUpd . "', 'int')) {
            if (\$this->ajax_mode) {
                DwResponse::sendError('Clave inválida');
            }
            return Redirect::toAction('listar');
        }

        \$obj = new " . $modeloUpper . "();
        if (!\$obj->find_first(\$id)) {
            if (\$this->ajax_mode) {
                DwResponse::sendError('No se pudo establecer la información del " . $modeloLower . "');
            }
            Flash::error('Lo sentimos, no se pudo establecer la información del " . $modeloLower . "');
            return Redirect::toAction('listar');
        }

        if (Input::hasPost('" . $modeloLower . "')) {
            if (Input::post('" . $modeloLower . ".id') != \$id) {
                if (\$this->ajax_mode) {
                    DwResponse::sendError('Ha ocurrido un error transformando la información');
                }
                Flash::error('Ha ocurrido un error transformando la información del " . $modeloLower . "');
                return Redirect::toAction('listar');
            }
            \$obj = new " . $modeloUpper . "(Input::post('" . $modeloLower . "'));
            if (\$obj->update()) {
                if (\$this->ajax_mode) {
                    DwResponse::sendSuccess('El " . $modeloLower . " se ha actualizado correctamente!', null, PUBLIC_PATH . '" . $modulo . "/" . $controlador . "/listar/');
                }
                Flash::valid('El " . $modeloLower . " se ha actualizado correctamente!');
                return Redirect::toAction('listar');
            }
            if (\$this->ajax_mode) {
                DwResponse::sendValidation(\$obj->getError());
            }
            Flash::error(\$obj->getError());
        }

        \$this->" . $modeloLower . " = \$obj;
        \$this->page_title = 'Actualizar " . $modeloTitle . "';
    }

    public function eliminar(\$key)
    {
        if (!\$id = DwSecurity::getKey(\$key, '" . $keyDel . "', 'int')) {
            if (\$this->ajax_mode) {
                DwResponse::sendError('Clave inválida');
            }
            return Redirect::toAction('listar');
        }

        \$obj = new " . $modeloUpper . "();
        if (!\$obj->find_first(\$id)) {
            if (\$this->ajax_mode) {
                DwResponse::sendError('No se pudo establecer la información del " . $modeloLower . "');
            }
            Flash::error('Lo sentimos, no se pudo establecer la información del " . $modeloLower . "');
            return Redirect::toAction('listar');
        }

        if (!\$obj->delete()) {
            if (\$this->ajax_mode) {
                DwResponse::sendError('Ha ocurrido un error al intentar eliminar el " . $modeloLower . "');
            }
            Flash::error('Ha ocurrido un error al intentar eliminar el " . $modeloLower . "');
            return Redirect::toAction('listar');
        }

        if (\$this->ajax_mode) {
            DwResponse::sendSuccess('El " . $modeloLower . " se ha eliminado correctamente!', null, PUBLIC_PATH . '" . $modulo . "/" . $controlador . "/listar/');
        }
        Flash::valid('El " . $modeloLower . " se ha eliminado correctamente!');
        return Redirect::toAction('listar');
    }
    
    public function ver(\$key)
    {
        if (!\$id = DwSecurity::getKey(\$key, '" . $keyShw . "', 'int')) {
            return Redirect::toAction('listar');
        }

        \$obj = new " . $modeloUpper . "();
        if (!\$obj->find_first(\$id)) {
            Flash::error('Lo sientenos, no se pudo establecer la información del " . $modeloLower . "');
            return Redirect::toAction('listar');
        }

        \$this->" . $modeloLower . " = \$obj;
        \$this->page_title = 'Ver " . $modeloTitle . "';
    }
}
";
        
        file_put_contents($file, $contenido);
        $this->say("<info>Controlador: $file</info>");
    }
    
    private function crearVistas($modulo, $controlador, $modeloLower, $campos)
    {
        $directorio = "app/views/$modulo/$controlador";
        if (!is_dir($directorio)) {
            mkdir($directorio, 0757, true);
        }
        
        $modeloTitle = ucfirst($modeloLower);
        
        // listar.phtml
        $ths = '';
        $tds = '';
        $relaciones = [];
        
        foreach ($campos as $c) {
            $ths .= "                                    <th class='text-center'>" . strtoupper($c) . "</th>\n";
            
            if (preg_match('/^(.+)_id$/', $c, $matches)) {
                $relatedModel = $matches[1];
                $relaciones[] = $relatedModel;
                $tds .= "                                    <td><?= \$obj->$relatedModel ? \$obj->$relatedModel->nombre : ''; ?></td>\n";
            } else {
                $tds .= "                                    <td><?= \$obj->$c; ?></td>\n";
            }
        }
        
        $listar = '<?php View::flash(); ?>
<?php SectionManager::start(\'styles\')  ?>
<link rel="stylesheet" href="<?= PUBLIC_PATH ?>template/coreui/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="<?= PUBLIC_PATH ?>template/coreui/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="<?= PUBLIC_PATH ?>template/coreui/assets/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css">
<?php SectionManager::end(\'styles\')  ?>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="block-header pt-3">
                    <h3 class="block-title"></h3>
                    <div class="block-options">
                        <?php echo DwHtml::button("' . $modulo . '/' . $controlador . '/agregar/", \'Nuevo\', NULL, \'fa-plus\'); ?>
                    </div>
                </div>
                <div class="block-content">
                    <div class="table-responsive mb-3">
                        <?php if ($' . $modeloLower . 's): ?>
                        <table id="t' . ucfirst($modeloLower) . '" class="table table-bordered table-vcenter table-hover js-dataTable-full table-striped table-condensed">
                            <thead class="bg-gray">
                                <tr>
' . $ths . '                                    <th class="text-center" width="5%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($' . $modeloLower . 's as $obj): ?>
                                <?php if (is_object($obj)): ?>
                                <?php $key_shw = DwSecurity::setKey($obj->id, \'shw_' . $modeloLower . '\'); ?>
                                <?php $key_upd = DwSecurity::setKey($obj->id, \'upd_' . $modeloLower . '\'); ?>
                                <?php $key_del = DwSecurity::setKey($obj->id, \'del_' . $modeloLower . '\'); ?>
                                <tr>
' . $tds . '                                    <td class="text-center">
                                        <div class="dropdown dropstart">
                                            <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton<?= $obj->id ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v fs-4"></i>
                                            </a>
                                            <ul class="dropdown-menu text-center" aria-labelledby="dropdownMenuButton<?= $obj->id ?>">
                                                <li class="dropdown-item">
                                                    <?php echo DwHtml::buttonTable(\'Ver\', "' . $modulo . '/' . $controlador . '/ver/$key_shw/", NULL, \'primary\', \'fa-search\'); ?>
                                                    <?php echo DwHtml::buttonTable(\'Editar\', "' . $modulo . '/' . $controlador . '/editar/$key_upd/", NULL, \'warning\', \'fa-pencil\'); ?>
                                                    <?php echo DwHtml::buttonTableSA(\'Eliminar\', "' . $modulo . '/' . $controlador . '/eliminar/$key_del/", [\'msg-title\' => \'Eliminar ' . $modeloTitle . '\', \'msg\' => \'¿Está seguro de eliminar este ' . $modeloLower . '?\', \'msg-nombre\' => $obj->nombre], \'danger\', \'fa-trash\'); ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <p class="text-muted">No hay registros</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php SectionManager::start(\'styles\') ?>
<link rel="stylesheet" href="<?= PUBLIC_PATH ?>template/coreui/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css">
<?php SectionManager::end(\'styles\') ?>

<?php SectionManager::start(\'scripts\') ?>
<script src="<?= PUBLIC_PATH ?>template/coreui/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= PUBLIC_PATH ?>template/coreui/assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="<?= PUBLIC_PATH ?>template/coreui/assets/js/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= PUBLIC_PATH ?>template/coreui/assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
<script src="<?= PUBLIC_PATH ?>template/coreui/assets/js/plugins/datatables-buttons-jszip/jszip.min.js"></script>
<script src="<?= PUBLIC_PATH ?>template/coreui/assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js"></script>
<script src="<?= PUBLIC_PATH ?>template/coreui/assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js"></script>
<script src="<?= PUBLIC_PATH ?>template/coreui/assets/js/plugins/datatables-buttons/buttons.print.min.js"></script>
<script src="<?= PUBLIC_PATH ?>template/coreui/assets/js/plugins/datatables-buttons/buttons.html5.min.js"></script>
<script>
    $("#t' . ucfirst($modeloLower) . '").DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        },
        order: [[0, "asc"]],
        columnDefs: [
            { targets: [-1], orderable: false, className: "text-center" }
        ]
    });
</script>
                text: "<i class=\"fa fa-file-excel\"></i> Excel",
                className: "btn-success btn-sm",
                exportOptions: { columns: ":visible" }
            },
            {
                extend: "pdf",
                text: "<i class=\"fa fa-file-pdf\"></i> PDF",
                className: "btn-danger btn-sm",
                exportOptions: { columns: ":visible" },
                customize: function(doc) {
                    doc.defaultFontSize = 8;
                    doc.styles.tableHeader.fontSize = 9;
                }
            },
            {
                extend: "print",
                text: "<i class=\"fa fa-print\"></i> Imprimir",
                className: "btn-info btn-sm"
            }
        ]
    });
</script>
<?php SectionManager::end(\'scripts\') ?>
';
        
        file_put_contents("$directorio/listar.phtml", $listar);
        
        // agregar.phtml
        $formFields = '';
        $colCount = 0;
        $hasFields = false;
        foreach ($campos as $c) {
            $hasFields = true;
            if ($colCount == 0) {
                $formFields .= "                    <div class=\"row\">\n";
            }
            $label = ucfirst($c);
            $formFields .= "                        <div class=\"col-md-6\">\n";
            $formFields .= "                            <?php echo DwForm::text('$modeloLower.$c', ['class' => 'input-required'], NULL, '$label'); ?>\n";
            $formFields .= "                        </div>\n";
            $colCount++;
            if ($colCount == 2) {
                $formFields .= "                    </div>\n";
                $colCount = 0;
            }
        }
        if ($hasFields && $colCount == 1) {
            $formFields .= "                    </div>\n";
        }
        
        $agregar = '<?php View::flash(); ?>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="block-content">
                    <?php echo DwForm::openAjax(); ?>

' . $formFields . '

                    <div class="row mb-3 mt-3">
                        <div class="form-actions">
                            <div class="text-left">
                                <?php echo DwForm::send("Guardar"); ?>
                                <?php echo DwForm::cancel(); ?>
                            </div>
                        </div>
                    </div>
                    <?php echo DwForm::close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->
';
        
        file_put_contents("$directorio/agregar.phtml", $agregar);
        
        // editar.phtml
        $editFields = '';
        $colCount = 0;
        $hasFields = false;
        foreach ($campos as $c) {
            $hasFields = true;
            if ($colCount == 0) {
                $editFields .= "                    <div class=\"row\">\n";
            }
            $label = ucfirst($c);
            $editFields .= "                        <div class=\"col-md-6\">\n";
            $editFields .= "                            <?php echo DwForm::text('$modeloLower.$c', ['class' => 'input-required'], \$" . $modeloLower . "->$c ?? '', '$label'); ?>\n";
            $editFields .= "                        </div>\n";
            $colCount++;
            if ($colCount == 2) {
                $editFields .= "                    </div>\n";
                $colCount = 0;
            }
        }
        if ($hasFields && $colCount == 1) {
            $editFields .= "                    </div>\n";
        }
        
        $editar = '<?php View::flash(); ?>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="block-content">
                    <?php echo DwForm::openAjax(); ?>

' . $editFields . '

                    <?php echo DwForm::hidden(\'' . $modeloLower . '.id\', null, $' . $modeloLower . '->id); ?>

                    <div class="row mb-3 mt-3">
                        <div class="form-actions">
                            <div class="text-left">
                                <?php echo DwForm::send("Actualizar"); ?>
                                <?php echo DwForm::cancel(); ?>
                            </div>
                        </div>
                    </div>
                    <?php echo DwForm::close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->
';
        
        file_put_contents("$directorio/editar.phtml", $editar);
        
        // ver.phtml
        $verCampos = '';
        $filas = array_chunk($campos, 3);
        foreach ($filas as $fila) {
            $verCampos .= "                        <div class=\"row text-center\">\n";
            foreach ($fila as $c) {
                $label = ucfirst($c);
                $verCampos .= "                            <div class=\"col-md-4\">
                                <label for=\"\" class=\"control-label mb-2\">$label:</label>
                                <h5><?= \$" . $modeloLower . "->$c ?? ''; ?></h5>
                            </div>\n";
            }
            $verCampos .= "                        </div>\n";
        }
        
        $ver = '<?php View::flash(); ?>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="block-content">
                    <fieldset class="border p-2">
                        <legend class="float-none w-auto block-header block-header-default">Datos del ' . $modeloLower . '</legend>
' . $verCampos . '
                    </fieldset>
                </div>
                <div class="block-content">
                    <div class="row mb-3 mt-3">
                        <div class="form-actions">
                            <div class="text-left">
                                <?= DwButton::back(\'' . $modulo . '/' . $controlador . '/listar\', \'Regresar al listado\', \'fa-undo\', \'Atras\'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->
';
        
        file_put_contents("$directorio/ver.phtml", $ver);
        $this->say("<info>Vistas: $directorio/</info>");
    }
    
    private function crearForm($modulo, $controlador, $modeloLower, $campos)
    {
        $directorio = "app/views/$modulo/$controlador";
        if (!is_dir($directorio)) {
            mkdir($directorio, 0757, true);
        }
        
        $loadModels = [];
        $formHtml = '';
        foreach ($campos as $c) {
            if (preg_match('/^(.+)_id$/', $c, $matches)) {
                $relatedModel = $matches[1];
                $loadModels[] = $relatedModel;
                $formHtml .= "<?php echo DwForm::dbSelect('$modeloLower.$c', 'nombre', array('admin/$relatedModel', 'find'), NULL, array('class' => 'input-required'), NULL, '" . ucfirst($relatedModel) . "'); ?>\n";
            } elseif ($c === 'costo' || $c === 'precio' || $c === 'monto') {
                $formHtml .= "<?php echo DwForm::number('$modeloLower.$c', ['class' => 'input-required'], 0.00, '" . ucfirst($c) . "'); ?>\n";
            } elseif ($c === 'existencia' || $c === 'cantidad' || $c === 'stock') {
                $formHtml .= "<?php echo DwForm::number('$modeloLower.$c', ['class' => 'input-required'], 0, '" . ucfirst($c) . "'); ?>\n";
            } else {
                $formHtml .= "<?php echo DwForm::text('$modeloLower.$c', ['class' => 'input-required'], NULL, '" . ucfirst($c) . "'); ?>\n";
            }
        }
        
        $loadModels = array_unique($loadModels);
        if (!empty($loadModels)) {
            $modelsStr = "";
            foreach ($loadModels as $m) {
                $modelsStr .= ", '$modulo/$m'";
            }
            $contenido = "<?php\n\nLoad::models('$modulo/$modeloLower'$modelsStr);\n\n?>\n$formHtml";
        } else {
            $contenido = $formHtml;
        }
        
        file_put_contents("$directorio/form.phtml", $contenido);
        $this->say("<info>Form: $directorio/form.phtml</info>");
    }
}