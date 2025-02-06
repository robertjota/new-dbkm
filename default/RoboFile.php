<?php

/**
 * Console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

class RoboFile extends \Robo\Tasks
{
  private $newdir;
  private $suffix = '1.0';
  private $files;
  private $changes = array(
    'Router::toAction(' => 'Redirect::toAction(',
    'Router::to(' => 'Redirect::to(',
    'Router::route_to(' => 'Redirect::route_to(',
    'Flash::notice(' => 'Flash::info(',
    'Flash::success(' => 'Flash::valid(',
    'Util::uncamelize(' => 'Util::smallcase(',
    "View::response('view')" => "View::template(null)",
    "Util::mkpath(" => "FileUtil::mkdir(",
    "Util::removedir(" => "FileUtil::rmdir(",
    "Util::lcfirst(" => "lcfirst(",
    "<?php eh(" => "<?= h("
  );
  private $delete = array();
  private $reemplazo = array();
  private $reemplaza = array();
  // define public methods as commands
  /**
   * @description Convertir una app beta2-0.9 a 1.0
   */
  public function kumbiaUpdate()
  {
    $this->say('<info>Actualizando aplicación de KumbiaPHP a v1.0</info>');
    $this->newdir = __DIR__ . $this->suffix;
    $exist = file_exists($this->newdir) ? 'Existe' : 'No existe';
    $this->say($exist);
    if ($exist === 'No existe') {
      $this->say('<info>Copiando</info> ' . __DIR__ . ' en ' . $this->newdir);
      $this->_copyDir(__DIR__, $this->newdir);
    }
    //$this->dir($this->newdir);
    $this->files();
    $this->kumbiaChanges();
  }
  private function kumbiaChanges()
  {
    foreach ($this->changes as $from => $to) {
      $this->say("Cambiando $from a $to");
      foreach ($this->files as $file) {
        $this->taskReplaceInFile($file)
          ->from($from)
          ->to($to)
          ->run();
      }
    }
  }
  private function kumbiaDelete()
  {
    $this->say('Eliminando Router::to() a Redirect::to()');
    foreach ($this->files as $file) {
      $this->taskReplaceInFile($file)
        ->from('Router::toAction')
        ->to('Redirect::toAction')
        ->run();
    }
  }
  private function files($dir = '/app', $extension = '*.php')
  {
    $this->files = Finder::create()
      ->name($extension)
      ->in($this->newdir . $dir);
    $this->say(count($this->files) . ' ficheros');
  }
  /**
   * @description Borrar la cache de la applicación
   */
  public function kumbiaCacheClean()
  {
    $this->_cleanDir('app/temp/cache');
    $this->say('<info>Cache borrada.</info>');
  }
  /**
   * @description Actualiza <?php echo a <?= (PHP 5.4+)
   */
  public function kumbiaEchoShort($dir = 'app/views', $extension = "*.phtml")
  {
    $this->say('<info>actualizando <?php echo a <?=</info>');
    $this->files($dir, $extension);
    foreach ($this->files as $file) {
      $this->taskReplaceInFile($file->getRealPath())
        ->from('<?php echo ')
        ->to('<?= ')
        ->run();
    }
    $this->say('<info>echo actualizado a php 7.4.</info>');
  }

  public function kumbiaCreateScaffoldController($relacion, $carpeta, $controllerName, $modelName, $extendsFrom = 'BackendController', $template = 'rrs')
  {
    if (!is_dir("app/controllers/{$carpeta}")) {
      mkdir("app/controllers/{$carpeta}");
    }
    $controllerName = strtolower($controllerName);
    $file = "app/controllers/{$carpeta}/{$controllerName}_controller.php";
    $viewsDir = "app/views/{$carpeta}/{$controllerName}";
    $fs = new Filesystem();
    if (!$fs->exists($file)) {
      $this->say("<info>Creando Controlador $controllerName</info>");
      //crear archivo
      $fs->touch($file);

      //escribir template
      //cambiar guiones controlador bajos por notacion CamelCase
      if (strpos($controllerName, '_') > 0) {
        $controllerNameCamel = str_replace("_", "", ucwords($controllerName, "_"));
        $controllerNameSpace = str_replace("_", " ", ucwords($controllerName, "_"));
      } else {
        $controllerNameCamel = ucfirst($controllerName);
        $controllerNameSpace = ucfirst($controllerName);
      }
      //cambiar nombre Controlador a inicial en Mayusculas
      $controllerName = ucfirst($controllerName);

      //cambiar guiones bajos modelo por notacion CamelCase
      if (strpos($modelName, '_') > 0) {
        $modelNameCamel = str_replace("_", "", ucwords($modelName, "_"));
      } else {
        $modelNameCamel = ucfirst($modelName);
      }
      //cambiar nombre Modelo a inicial en Mayusculas
      $modelName = ucfirst($modelName);

      $fs->touch($file);

      //crear archivo a partir del template
      if (!empty($relacion)) {
        $this->taskWriteToFile($file)
          ->textFromFile("templates/controllerjoin.{$template}.php")
          ->run();
      } else {
        $this->taskWriteToFile($file)
          ->textFromFile("templates/controller.{$template}.php")
          ->run();
      }
      //reemplazar elementos
      $reemplazar = array(
        '%Relacion%',
        '%ModelDir%',
        '%ControllerCamel%',
        '%ControllerSpace%',
        '%Item%',
        '%BaseController%',
        '%Model%',
        '%ModelCamel%',
        '%lcaseModels%',
        '%lcaseModel%'
      );
      $reemplazo = array(
        $relacion,
        $carpeta,
        $controllerNameCamel,
        $controllerNameSpace,
        $controllerName,
        $extendsFrom,
        $modelName,
        $modelNameCamel,
        strtolower($controllerName),
        strtolower($modelName),
      );
      $this->taskReplaceInFile($file)
        ->from($reemplazar)
        ->to($reemplazo)
        ->run();
      $this->say("<info>Controlador $controllerName ha sido creado</info>");
    } else {
      $this->say("<error>Controlador $controllerName ya existía!</error>");
    }
    if (!$fs->exists($viewsDir)) {
      $fs->mkdir($viewsDir);
      $this->say("<info>Carpeta de Vistas creada en {$viewsDir}</info>");
    } else {
      $this->say("<error>Carpeta de Vistas ya existía en {$viewsDir}</error>");
    }
  }
  /**
   * @description Crea un controlador sencillo y su carpeta de vistas
   *
   * @param string $controllerName Nombre del controlador
   */
  public function kumbiaCreateController($controllerName, $baseController = 'AppController')
  {
    $controllerName = strtolower($controllerName);
    $file = "app/controllers/{$controllerName}_controller.php";
    $viewsDir = "app/views/{$controllerName}";
    $fs = new Filesystem();
    if (!$fs->exists($file)) {
      $this->say("<info>Creando Controlador</info>");
      //crear archivo
      $fs->touch($file);
      //escribir template
      $controllerName = ucfirst($controllerName);
      $this->taskWriteToFile($file)
        ->textFromFile("templates/controller.simple.rrs.php")
        ->run();
      //reemplazar elementos
      $reemplazar = array(
        '%Item%',
        '%BaseController%'
      );
      $reemplazo = array(
        $controllerName,
        $baseController
      );
      $this->taskReplaceInFile($file)
        ->from($reemplazar)
        ->to($reemplazo)
        ->run();
      $this->say("<info>Controlador creado en {$file}</info>");
    } else {
      $this->say("<error>Controlador ya existía en {$file}</error>");
    }
    //crear directorio para las vistas del controlador
    if (!$fs->exists($viewsDir)) {
      $fs->mkdir($viewsDir);
      $this->say("<info>Carpeta de Vistas creada en {$viewsDir}</info>");
    } else {
      $this->say("<error>Carpeta de Vistas ya existía en {$viewsDir}</error>");
    }
  }


  /**
   * @description Crea un modelo, por defecto de ActiveRecord
   *
   * @param string $modelName Nombre del modelo
   */
  public function kumbiaCreateModel($relacion, $carpeta, $modelName, $modelClass = 'ActiveRecord')
  {
    $modelName = strtolower($modelName);
    $carpeta = strtolower($carpeta);
    if (!is_dir("app/models/{$carpeta}")) {
      mkdir("app/models/{$carpeta}");
      /* mkdir("app/views/_shared/partials/{$carpeta}");
      mkdir("app/views/_shared/partials/{$carpeta}/{$modelName}s"); */
    }
    $file = "app/models/{$carpeta}/{$modelName}.php";
    $fs = new Filesystem();
    if (!$fs->exists($file)) {
      $this->say("<info>Creando Modelo</info>");
      //crear archivo
      $fs->touch($file);
      //escribir template

      if (strpos($modelName, '_') > 0) {
        $modelNameCamel = str_replace("_", "", ucwords($modelName, "_"));
        $modelNameSpace = str_replace("_", " ", ucwords($modelName, "_"));
      } else {
        $modelNameCamel = ucfirst($modelName);
        $modelNameSpace = ucfirst($modelName);
      }
      $modelName = ucfirst($modelName);

      $modelNameMin = strtolower($modelName);
      //crear archivo a partir del template
      if (!empty($relacion)) {
        $this->taskWriteToFile($file)
          ->textFromFile("templates/modeljoin.rrs.php")
          ->run();
      } else {
        $this->taskWriteToFile($file)
          ->textFromFile("templates/model.rrs.php")
          ->run();
      }
      //reemplazar elementos
      $reemplazar = array(
        '%Relacion%',
        '%Model%',
        '%ModelExtends%',
        '%ModelMin%',
        '%ModelCamel%',
        '%ModelSpace%'
      );
      $reemplazo = array(
        $relacion,
        $modelName,
        $modelClass,
        $modelNameMin,
        $modelNameCamel,
        $modelNameSpace
      );
      $this->taskReplaceInFile($file)
        ->from($reemplazar)
        ->to($reemplazo)
        ->run();
      $this->say("<info>Modelo creado en {$file}</info>");
    } else {
      $this->say("<error>Modelo ya existía en {$file}</error>");
    }
  }
  public function kumbiaCreateView($carpeta, $controller, $accion, $formato = 'rrs')
  {
    $file = "app/views/{$carpeta}/{$controller}/{$accion}.phtml";
    $viewsDir = "app/views/{$carpeta}/{$controller}";
    $fs = new Filesystem();
    if (!$fs->exists($file)) {
      $this->say("<info>Creando Vista</info>");
      //crear archivo
      $fs->touch($file);
      //escribir template
      $this->taskWriteToFile($file)
        ->textFromFile("templates/{$formato}/view.{$accion}.phtml")
        ->run();
      $this->taskReplaceInFile($file)
        ->from($this->reemplaza)
        ->to($this->reemplazo)
        ->run();
      $this->say("<info>Vista creada en {$file}</info>");
    } else {
      $this->say("<error>Vista ya existía en {$file}</error>");
    }
  }

  public function kumbiaCreateViewForm($carpeta, $controller, $accion, $formato = 'rrs')
  {
    if (!is_dir("app/views/_shared/partials/{$carpeta}")) {
      mkdir("app/views/_shared/partials/{$carpeta}");
    }
    if (!is_dir("app/views/_shared/partials/{$carpeta}/{$controller}")) {
      mkdir("app/views/_shared/partials/{$carpeta}/{$controller}");
    }
    $file = "app/views/_shared/partials/{$carpeta}/{$controller}/{$accion}.phtml";
    $viewsDir = "app/views/_shared/partials/{$carpeta}/{$controller}";
    $fs = new Filesystem();
    if (!$fs->exists($file)) {
      $this->say("<info>Creando Form</info>");
      //crear archivo
      $fs->touch($file);
      //escribir template
      $this->taskWriteToFile($file)
        ->textFromFile("templates/{$formato}/view.{$accion}.phtml")
        ->run();
      $this->taskReplaceInFile($file)
        ->from($this->reemplaza)
        ->to($this->reemplazo)
        ->run();
      $this->say("<info>Form creado en {$file}</info>");
    } else {
      $this->say("<error>Form ya existía en {$file}</error>");
    }
  }

  /**
   * @description Consola Interactiva para crear Scaffolds Estáticos
   *
   */
  public function kumbiaScaffoldConsole()
  {
    $this->say("<info>Bienvenido al generador de CRUD</info>");
    $seguir = "s";
    while ($seguir === "s") {
      $modelo = "";
      $carpeta = "";
      $plural = "";
      $carpeta = $this->ask("Indique nombre de la carpeta: ");
      $this->say("<info>El nombre debe de ser en singular en minusculas y separ las palabras con guion bajo(_)</info>");
      $modelo = $this->ask("Indique nombre del modelo: ");
      $plural = $this->ask("Indique nombre del modelo en plural: ");
      //$this->say("<info>Modelo extiende de [ActiveRecord|LiteRecord|ActRecord]</info>");
      //$formatoModelo = $this->ask("Modelo extiende: ");
      //$formatoController = "lite";
      //if (strlen(trim($formatoModelo)) == 0 ) {
      $formatoModelo = "ActiveRecord";
      $formatoController = "rrs";
      //}
      if ($this->ask("Generar archivo del Modelo (s/n): ") === "s") {
        $modelo = strtolower($modelo);

        if (strpos($modelo, '_') > 0) {
          $modeloCamel = str_replace("_", "", ucwords($modelo, "_"));
        } else {
          $modeloCamel = ucfirst($modelo);
        }

        $relacion = "";
        if ($this->ask("El Modelo tiene Relacion con otra tabla (s/n): ") === "s") {
          $relacion = $this->ask("Indique nombre de la tabla de Relacion: ");
        }
        $this->kumbiaCreateModel($relacion, $carpeta, $modelo, $formatoModelo);
      }
      $modelo_class = ucfirst($modelo);
      $modelo_input = strtolower($modelo);
      $controlador = $plural;
      //$controlador = $this->ask("Nombre de clase para controlador (sin sufijo Controller): ");
      $controlador_archivo = $controlador . "_controller.php";
      //$extiendeDe = $this->ask("Controlador hereda de [BackendController]: ");
      //if (strlen($extiendeDe) == 0) {
      $extiendeDe = "BackendController";
      //}
      if ($this->ask("Crear acciones del CRUD predeterminadas (s/n): ") === "s") {
        $this->kumbiaCreateScaffoldController($relacion, $carpeta, $controlador, $modelo, $extiendeDe, $formatoController);
      } else {
        $this->kumbiaCreateController($carpeta, $controlador, $extiendeDe);
      }
      //crear vistas
      if ($this->ask("Crear vistas CRUD predeterminadas? (s/n)") === "s") {
        $this->say("<info>Indique los campos a mostrar separados por coma (no indique id)</info>");
        $atributos = $this->ask("Campos a Mostrar: ");
        if (strpos($atributos, ",") != FALSE) {
          $atributos_arr = explode(",", $atributos);
        } else if (!empty($atributos)) {
          $atributos_arr = array($atributos); //elemento unico
        } else {
          $atributos_arr = array('nombre'); //elemento por defecto
        }
        $atributos_rel = [];
        if ($relacion != "") {
          array_push($atributos_rel, $relacion);
        }
        //$this->say("<info>Indique formato de las vistas [rrs]</info>");
        //$formato = $this->ask("Formato: ");
        //if (strlen(trim($formato)) == 0 ) {
        $formato = "rrs";
        //}
        $cListHead = "";
        $cListBody = "";
        $cFormContent = "";
        $cTextContent = "";

        for ($i = 0; $i < count($atributos_arr); $i++) {
          $atributos_arr[$i] = trim(strtolower($atributos_arr[$i]));
          $cListHead .= "         <th class=\"text-center\">" . strtoupper($atributos_arr[$i]) . "</th>" . PHP_EOL;
          $cListBody .= "         <td><?= $" . $modelo . "->" . $atributos_arr[$i] . "; ?></td>" . PHP_EOL;
          $cFormContent .= "    <div class=\"row\">" . PHP_EOL;
          $cFormContent .= "        <div class=\"col-xs-12\">" . PHP_EOL;
          $cFormContent .= "            <?= DwForm::text('" . $modelo . "." . $atributos_arr[$i] . "', (!empty(\$ver) ? ['disabled' => 'disabled'] : ['class'=>'input-upper input-required']), NULL, '" . ucfirst($atributos_arr[$i]) . "'); ?>" . PHP_EOL;
          $cFormContent .= "        </div>" . PHP_EOL;
          $cFormContent .= "    </div>" . PHP_EOL;
          $cTextContent .= "<p>" . PHP_EOL;
          $cTextContent .= "<strong>" . ucfirst($atributos_arr[$i]) . "</strong><br/>" . PHP_EOL;
          $cTextContent .= "<span><?= $" . $modelo . "->" . $atributos_arr[$i] . ";?></span>" . PHP_EOL;
          $cTextContent .= "</p>" . PHP_EOL;
        }
        if (count($atributos_rel) != 0) {
          $atributos_rel[0] = trim(strtolower($atributos_rel[0]));
          $cListHead .= "         <th class=\"text-center\">" . strtoupper($atributos_rel[0]) . "</th>" . PHP_EOL;
          $cListBody .= "     <td><?= $" . $modelo . "->" . $atributos_rel[0] . "; ?></td>" . PHP_EOL;
          $cFormContent .= "    <div class=\"row\">" . PHP_EOL;
          $cFormContent .= "        <div class=\"col-xs-12\">" . PHP_EOL;
          $cFormContent .= "            <?= DwForm::dbSelect('" . $modelo . "." . $atributos_rel[0] . "_id', 'nombre', ['" . $carpeta . "/" . $atributos_rel[0] . "', 'getListado" . ucfirst($atributos_rel[0]) . "'], NULL, (!empty(\$ver) ? ['disabled' => 'disabled'] : ['class'=>'input-upper input-required']), NULL, '" . ucfirst($atributos_rel[0]) . "'); ?>" . PHP_EOL;
          $cFormContent .= "        </div>" . PHP_EOL;
          $cFormContent .= "    </div>" . PHP_EOL;
          $cTextContent .= "<p>" . PHP_EOL;
          $cTextContent .= "<strong>" . ucfirst($atributos_rel[0]) . "</strong><br/>" . PHP_EOL;
          $cTextContent .= "<span><?= $" . $modelo . "->" . $atributos_rel[0] . ";?></span>" . PHP_EOL;
          $cTextContent .= "</p>" . PHP_EOL;
        }

        //contenido para listar
        $this->reemplaza = array(
          "%Controller%",
          "%carpeta%",
          "%Model%",
          "%ModelCamel%",
          "%lcaseModel%",
          "%lcaseModels%",
          "%columnListHead%",
          "%columnListBody%"
        );
        $this->reemplazo = array(
          strtolower($controlador),
          $carpeta,
          ucfirst($modelo),
          $modeloCamel,
          $modelo,
          $plural,
          $cListHead,
          $cListBody
        );
        $this->kumbiaCreateView($carpeta, strtolower($controlador), "listar", $formato);

        //contenido para agregar
        $this->reemplaza = array(
          "%Controller%",
          "%carpeta%"
        );
        $this->reemplazo = array(
          strtolower($controlador),
          $carpeta
        );
        $this->kumbiaCreateView($carpeta, strtolower($controlador), "agregar", $formato);

        //contenido para editar
        $this->reemplaza = array(
          "%Controller%",
          "%carpeta%"
        );
        $this->reemplazo = array(
          strtolower($controlador),
          $carpeta
        );
        $this->kumbiaCreateView($carpeta, strtolower($controlador), "editar", $formato);

        //contenido para ver
        $this->reemplaza = array(
          "%Controller%",
          "%carpeta%",
        );
        $this->reemplazo = array(
          strtolower($controlador),
          $carpeta
        );
        $this->kumbiaCreateView($carpeta, strtolower($controlador), "ver", $formato);

        //contenido para form
        $this->reemplaza = array(
          "%Controller%",
          "%Model%",
          "%lcaseModel%",
          "%carpeta%",
          "%First_Field%",
          "%formContent%"
        );
        $this->reemplazo = array(
          strtolower($controlador),
          ucfirst($modelo),
          $modelo,
          $carpeta,
          $atributos_arr[0],
          $cFormContent
        );
        $this->kumbiaCreateViewForm($carpeta, strtolower($controlador), "form", $formato);
      }
      $seguir = $this->ask("Crear otro CRUD (s/n): ");
    }
    $this->say("<info>Hasta pronto!</info>");
  }
}
