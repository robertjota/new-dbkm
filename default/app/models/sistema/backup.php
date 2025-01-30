<?php
/**
 *
 * Descripcion: Modelo para el manejo de las copias de seguridad
 *
 * @category
 * @package     Models
 * @subpackage
 */

class Backup extends ActiveRecord {

    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
        $this->belongs_to('usuario');
    }

    /**
     * Método para buscar usuarios
     */
    public function getAjaxBackup($field, $value, $order='', $page=0) {
        $value = Filter::get($value, 'string');
        if( strlen($value) <= 2 OR ($value=='none') ) {
            return NULL;
        }
        $columns    = 'backup.*, usuario.nombre, usuario.apellido';
        $join       = 'INNER JOIN usuario ON usuario.id = backup.usuario_id ';
        $conditions = "backup.id > 0";

        $order      = $this->get_order($order, 'backup.backup_at', array(
                        'nombre'=>array(
                            'ASC'=>'usuario.nombre ASC, usuario.apellido ASC',
                            'DESC'=>'usuario.nombre DESC, usuario.apellido DESC'
                        ),
                        'apellido'=>array(
                            'ASC'=>'usuario.apellido ASC, usuario.nombre ASC',
                            'DESC'=>'usuario.apellido DESC, usuario.nombre DESC'
                        ),
                        'fecha'=>array(
                            'ASC'=>'backup.backup_at ASC' ,
                            'DESC'=>'backup.backup_at DESC'
                        ),
                        'denominacion'=>array(
                            'ASC'=>'backup.denominacion ASC, backup.backup_at DESC' ,
                            'DESC'=>'backup.denominacion DESC, backup.backup_at DESC'
                        ),
                        'tamano'=>array(
                            'ASC'=>'backup.tamano ASC, backup.backup_at DESC' ,
                            'DESC'=>'backup.tamano DESC, backup.backup_at DESC'
                        )
                    )
        );

        //Por seguridad defino los campos habilitados para la búsqueda
        $fields = array('denominacion', 'nombre', 'apellido', 'fecha');
        if(!in_array($field, $fields)) {
            $field = 'nombre';
        }

        if($field=='fecha') {
            $conditions.= " AND DATE(backup.backup_at) LIKE '%$value%'";
        } else {
            $conditions.= " AND $field LIKE '%$value%'";
        }

        if($page) {
            return $this->paginated("columns: $columns", "join: $join", "conditions: $conditions", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "order: $order");
        }
    }

    /**
     * Método para listar las copias de seguridad
     * @param type $order patrón de ordenamiento order.campo.tipo
     * @param type $page
     * @return type
     */
    public function getListadoBackup($order='', $page=0) {
        $columns    = 'backup.*, usuario.nombre, usuario.apellido';
        $join       = 'INNER JOIN usuario ON usuario.id = backup.usuario_id ';

        $order      = $this->get_order($order, 'backup.backup_at', array(
                        'nombre'=>array(
                            'ASC'=>'usuario.nombre ASC, usuario.apellido ASC',
                            'DESC'=>'usuario.nombre DESC, usuario.apellido DESC'
                        ),
                        'apellido'=>array(
                            'ASC'=>'usuario.apellido ASC, usuario.nombre ASC',
                            'DESC'=>'usuario.apellido DESC, usuario.nombre DESC'
                        ),
                        'fecha'=>array(
                            'ASC'=>'backup.backup_at ASC' ,
                            'DESC'=>'backup.backup_at DESC'
                        ),
                        'denominacion'=>array(
                            'ASC'=>'backup.denominacion ASC, backup.backup_at DESC' ,
                            'DESC'=>'backup.denominacion DESC, backup.backup_at DESC'
                        ),
                        'tamano'=>array(
                            'ASC'=>'backup.tamano ASC, backup.backup_at DESC' ,
                            'DESC'=>'backup.tamano DESC, backup.backup_at DESC'
                        )
                    )
        );

        if($page) {
            return $this->paginated("columns: $columns", "join: $join", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join", "order: $order");
        }
    }


    /**
     * Método para crear una copia de seguridad
     *
     * @param type $data Input del post
     * @param string $path Ruta donde se almacenará la copia de seguridad
     * @param type $database Pull de conexión
     * @return boolean|\Backup
     */
    public static function createBackup($data, $path='', $database='') {
        $obj = new Backup($data);
        $obj->archivo = "backup-".($obj->count() + 1 ).".sql.gz";
        $obj->usuario_id = Session::get('id');
        //Inicio transacción
        ActiveRecord::beginTrans();
        if(!$obj->create()) {
            ActiveRecord::rollbackTrans();
            return FALSE;
        }
        if(empty($path)) {
            $path = APP_PATH . 'temp/backup/';
        }
        if(!is_writable($path)) {
            ActiveRecord::rollbackTrans();
            Flash::error('Error: BKP-CRE001. El directorio de las copias de seguridad no tiene permisos de escritura.');
            return false;
        }
        $file       = $path.$obj->archivo;
        $system     = $obj->_getSystem();
        $database   = (empty($database)) ? Config::get('config.application.database') : $database;
        $config     = $obj->_getConfig($database);

/**************************************************
 * AGREGADO PARA ESTE TIPO DE SERVICIO DE HOSTING *
 * ***********************************************/

// SE DEFINEN LAS VARIABLES DEL PROGRAMA
$host               = $config[host];
$username           = $config[username];
$password           = $config[password];
$database_name      = $config[database];
$backup_file_name   = "backup-".($obj->count() + 1 ).'.sql';

// SE DEFINEN LAS VARIABLES PARA LA DATA A RESPALDAR
$conn               = mysqli_connect($host, $username, $password, $database_name);
$tables             = array();
$sql                = "SHOW TABLES";
$backupSQL          = "";

// SE CREA LA CONECCIÓN CON LA DB.
$result = mysqli_query($conn, $sql);

// SE EXTRAEN LOS NOMBRES DE LAS TABLAS DE LA DB.
while($row = mysqli_fetch_row($result)){
    $tables[] = $row[0];
}
// SE EXTRAEN LOS CAMPOS Y SUS REGISTROS DE CADA TABLA PARA TODO GUARDARLO EN $backupSQL
foreach($tables as $table){
    $query = "SHOW CREATE TABLE $table";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_row($result);
    $backupSQL .= "\n\n".$row[1].";\n\n";

    $query = "SELECT * FROM $table";
    $result = mysqli_query($conn, $query);

    $columnCount = mysqli_num_fields($result);

    for ($i = 0; $i<$columnCount; $i++) {
        while ($row = mysqli_fetch_row($result)) {
            $backupSQL .= "INSERT INTO $table VALUES(";
            for ($j = 0; $j < $columnCount; $j++) {
                $row[$j] = $row[$j];
                if (isset($row[$j])) {
                    $backupSQL .= '"'.$row[$j].'"';
                } else {
                    $backupSQL .= '""';
                }
                if ($j < ($columnCount-1)) {
                    $backupSQL .= ',';
                }
            }
            $backupSQL .= ");\n";
        }
    }
    $backupSQL .= "\n";
}
// CON ESA VARIABLE SE CREA EL ARCHIVO DE BACKUP ES CUAL ES COMPRIMIDO
if (!empty($backupSQL)){
    $fileHandler = fopen($path.'/'.$backup_file_name,'w+');
    DwUtis::print_r($path.'/'.$backup_file_name);
    $number_of_lines = fwrite($fileHandler, $backupSQL);
    fclose($fileHandler);

    // RUTINA PARA COMPRIMIR EL ARCHIVO DE BACKUP CREADO
    $gz = gzopen($path.'/'.$backup_file_name.'.gz','w9');
    gzwrite($gz, $backupSQL);
    gzclose($gz);
    $old = filesize($path.'/'.$backup_file_name);
    $new = filesize($path.'/'.$backup_file_name.'.gz');
    $Percentage = ($new / $old) * 100;
    //echo number_format($Percentage,2)."\n\n";

    // ENCABEZADO PARA EL CORREO DE REPORTE
    //header('Content-Description: File Transfer');
    //header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename= '.basename($path.'/'.$backup_file_name.'.gz'));
    //header('Content-Transfer-Encoding: binary');
    //header('Expires: 0');
    //header('Cache-Control: must-revalidate');
    //header('Pragma: public');
    header('Content-Length-File: '.$old.' Bytes');
    header('Content-Length-File-Compressed: '.$new.' Bytes');
    header('Percentage-Compressed: '.number_format($Percentage,2).'%');
    unlink($backup_file_name);
    flush();
}


//        $exec       = "$system -h ".$config['host']." -u ".$config['username']." --password=".$config['password']." --opt --default-character-set=latin1 ".$config['name']." | gzip > $file";

/*
        system($exec, $resultado);
        if($resultado) {
            ActiveRecord::rollbackTrans();
            Flash::error('Error: BKP-CRE002. Se ha producido un error al intentar crear una nueva copia de seguridad.');
            return false;
        }
*/
        $tamano         = filesize($file);
        $clase          = array(" Bytes", " KB", " MB", " GB", " TB");
        $obj->tamano    = round($tamano/pow(1024,($i = floor(log($tamano, 1024)))), 2 ).$clase[$i];
        $obj->update();
        @chmod($file, 0777);
        ActiveRecord::commitTrans();
        if($obj) {
            DwAudit::debug("Se crea una copia de seguridad bajo la denominación: $obj->denominacion");
        }
        return $obj;
    }

    /**
     * Callback que se ejecuta antes de guardar/modificar
     * @return string
     */
    public function before_save() {
        $conditions = "denominacion = '$this->denominacion'";
        $conditions.= (isset($this->id)) ? " AND id != $this->id" : '';
        if($this->count($conditions)) {
            Flash::error('Lo sentimos, pero ya existe una copia de seguridad con la misma denominación.');
            return 'cancel';
        }
    }

    /**
     * Método para restaurar el sistema
     * @param type $id
     */
    public static function restoreBackup($id, $path='') {
        $id         = Filter::get($id, 'int');
        if(empty($id)) {
            return FALSE;
        }
        if(empty($path)) {
            $path = APP_PATH . 'temp/backup/';
        }
        $obj        = new Backup();
        if(!$obj->find_first($id)) {
            Flash::error('Lo sentimos, pero no se ha podido establecer la información de la copia de seguridad.');
            return FALSE;
        }
        $file       = $path.$obj->archivo;
        if(!is_file($file)) {
            Flash::error('Error: BKP-RES001. Se ha producido un error en la restauración del sistema. <br />No se pudo localizar el archivo de restaruación.');
            return FALSE;
        }
        //Almaceno las copias de seguridad anteriores
        $old_backup = $obj->find('order: backup_at ASC');

        $system     = $obj->_getSystem(TRUE); //Verifico el sistema operativo para la restauración
        $database   = Config::get('config.application.database'); //tomo el entorno actual
        $config     = $obj->_getConfig($database);//Tomo la configuración de conexión
        $exec       = "gunzip < $file | $system -h ".$config['host']." -u ".$config['username']." --password=".$config['password']." ".$config['name'];
        system($exec, $result);
        if(!$result) {
            //Inserto los backups anteriores
            foreach($old_backup as $backup) {
                if($backup->id >= $obj->id ) {
                    $obj->sql("REPLACE INTO `backup` (`id`,`usuario_id`,`denominacion`,`tamano`,`archivo`,`backup_at`) VALUES ('$backup->id', '$backup->usuario_id', '$backup->denominacion', '$backup->tamano', '$backup->archivo', '$backup->backup_at')");
                }
            }
            if($obj) {
                DwAudit::debug("Se ha restaurado el sistema con la copia de seguridad: $obj->denominacion");
            }
            return ($obj) ? $obj : FALSE;
        }
        return FALSE;

    }


}
?>
