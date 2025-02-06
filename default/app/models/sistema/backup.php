<?php

class Backup extends ActiveRecord
{
    protected $backupPath;

    public function initialize()
    {
        $this->belongs_to('usuario');
        $this->backupPath = APP_PATH . 'temp/backup/';
    }

    /**
     * Método para obtener el listado de backups
     */
    public function getListadoBackup($order = '', $page = 0)
    {
        $columns = 'backup.*, usuario.nombre, usuario.apellido';
        $join = 'INNER JOIN usuario ON usuario.id = backup.usuario_id';

        $order = $this->get_order($order, 'backup.backup_at', [
            'nombre' => [
                'ASC' => 'usuario.nombre ASC, usuario.apellido ASC',
                'DESC' => 'usuario.nombre DESC, usuario.apellido DESC'
            ],
            'apellido' => [
                'ASC' => 'usuario.apellido ASC, usuario.nombre ASC',
                'DESC' => 'usuario.apellido DESC, usuario.nombre DESC'
            ],
            'fecha' => [
                'ASC' => 'backup.backup_at ASC',
                'DESC' => 'backup.backup_at DESC'
            ],
            'denominacion' => [
                'ASC' => 'backup.denominacion ASC, backup.backup_at DESC',
                'DESC' => 'backup.denominacion DESC, backup.backup_at DESC'
            ],
            'tamano' => [
                'ASC' => 'backup.tamano ASC, backup.backup_at DESC',
                'DESC' => 'backup.tamano DESC, backup.backup_at DESC'
            ]
        ]);

        if ($page) {
            return $this->paginated("columns: $columns", "join: $join", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join", "order: $order");
        }
    }

    /**
     * Método para buscar backups
     */
    public function getAjaxBackup($field, $value, $order = '', $page = 0)
    {
        $value = Filter::get($value, 'string');
        if (strlen($value) <= 2 || $value == 'none') {
            return NULL;
        }

        $columns = 'backup.*, usuario.nombre, usuario.apellido';
        $join = 'INNER JOIN usuario ON usuario.id = backup.usuario_id';
        $conditions = "backup.id > 0";

        $order = $this->get_order($order, 'backup.backup_at', [
            'nombre' => [
                'ASC' => 'usuario.nombre ASC, usuario.apellido ASC',
                'DESC' => 'usuario.nombre DESC, usuario.apellido DESC'
            ],
            'apellido' => [
                'ASC' => 'usuario.apellido ASC, usuario.nombre ASC',
                'DESC' => 'usuario.apellido DESC, usuario.nombre DESC'
            ],
            'fecha' => [
                'ASC' => 'backup.backup_at ASC',
                'DESC' => 'backup.backup_at DESC'
            ],
            'denominacion' => [
                'ASC' => 'backup.denominacion ASC, backup.backup_at DESC',
                'DESC' => 'backup.denominacion DESC, backup.backup_at DESC'
            ],
            'tamano' => [
                'ASC' => 'backup.tamano ASC, backup.backup_at DESC',
                'DESC' => 'backup.tamano DESC, backup.backup_at DESC'
            ]
        ]);

        // Campos permitidos para la búsqueda
        $fields = ['denominacion', 'nombre', 'apellido', 'fecha'];
        if (!in_array($field, $fields)) {
            $field = 'nombre';
        }

        if ($field == 'fecha') {
            $conditions .= " AND DATE(backup.backup_at) LIKE '%$value%'";
        } else {
            $conditions .= " AND $field LIKE '%$value%'";
        }

        return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "order: $order");
    }

    /**
     * Método para crear un backup
     */
    public function createBackup($data)
    {
        $backup = new Backup($data);

        $backup->archivo = "backup-" . ($backup->count() + 1) . ".zip"; // Cambia la extensión a .zip
        $backup->usuario_id = Session::get('id');

        if (!$backup->create()) {
            Flash::error('Error al crear el registro de backup en la base de datos.');
            return false;
        }

        if (!is_writable($this->backupPath)) {
            Flash::error('El directorio de backups no tiene permisos de escritura.');
            return false;
        }

        $file = $this->backupPath . $backup->archivo;

        $config = $this->getDatabaseConfig();
        // Exportar la base de datos a un archivo SQL temporal
        $tempSqlFile = $this->backupPath . "temp_backup.sql";
        $exec = "mysqldump -h {$config['host']} -u {$config['username']} --password={$config['password']} --opt --default-character-set=latin1 {$config['name']} > $tempSqlFile";
        system($exec, $resultado);

        if ($resultado) {
            Flash::error('Error al exportar la base de datos. Verifica las credenciales de la base de datos.');
            return false;
        }

        // Comprimir el archivo SQL en un archivo ZIP
        $zip = new ZipArchive();
        if ($zip->open($file, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($tempSqlFile, "backup.sql");
            $zip->close();
            unlink($tempSqlFile); // Eliminar el archivo SQL temporal
        } else {
            Flash::error('Error al crear el archivo ZIP. Verifica que la extensión ZipArchive esté habilitada en PHP.');
            return false;
        }

        $tamano = filesize($file);
        $clase = array(" Bytes", " KB", " MB", " GB", " TB");
        $backup->tamano = round($tamano / pow(1024, ($i = floor(log($tamano, 1024)))), 2) . $clase[$i];
        $backup->update();

        Flash::valid('Backup creado correctamente: ' . $backup->archivo);
        return $backup;
    }

    /**
     * Método para restaurar un backup
     */
    public function restoreBackup($id)
    {
        $backup = $this->find_first($id);
        if (!$backup) {
            Flash::error('Backup no encontrado en la base de datos.');
            return false;
        }

        $file = $this->backupPath . $backup->archivo;
        if (!is_file($file)) {
            Flash::error('Archivo de backup no encontrado en el servidor.');
            return false;
        }

        $config = $this->getDatabaseConfig();

        // Extraer el archivo ZIP
        $zip = new ZipArchive();
        if ($zip->open($file) === TRUE) {
            $tempSqlFile = $this->backupPath . "temp_backup.sql";
            $zip->extractTo($this->backupPath);
            $zip->close();
        } else {
            Flash::error('Error al extraer el archivo ZIP. Verifica que el archivo no esté corrupto.');
            return false;
        }

        // Restaurar la base de datos desde el archivo SQL extraído
        $exec = "mysql -h {$config['host']} -u {$config['username']} --password={$config['password']} {$config['name']} < $tempSqlFile";
        system($exec, $result);

        if ($result) {
            Flash::error('Error al restaurar el backup. Verifica las credenciales de la base de datos.');
            return false;
        }

        unlink($tempSqlFile); // Eliminar el archivo SQL temporal

        Flash::valid('Backup restaurado correctamente: ' . $backup->archivo);
        return $backup;
    }

    /**
     * Obtiene la configuración de la base de datos
     */
    protected function getDatabaseConfig()
    {
        // Obtén la configuración de la base de datos
        $config = Config::get('databases.development');
        // Verifica que la configuración sea un array
        if (!is_array($config)) {
            throw new Exception('La configuración de la base de datos no es válida.');
        }

        // Asegúrate de que los índices necesarios estén presentes
        $requiredKeys = ['host', 'username', 'password', 'name'];
        foreach ($requiredKeys as $key) {
            if (!isset($config[$key])) {
                throw new Exception("La clave '$key' no está presente en la configuración de la base de datos.");
            }
        }

        return $config;
    }
}
