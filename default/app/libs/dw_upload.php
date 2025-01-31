<?php

class DwUpload
{
    public $file;
    public $file_ext;
    public $oldName;
    public $name;
    public $path;
    public $allowedTypes = '*';
    public $encryptName = FALSE;
    public $resize = FALSE;
    public $removeOld = FALSE;
    public $resizeWidth = 170;
    public $resizeHeight = 200;
    protected $size = 0;
    public $maxSize = "15MB";
    protected $_error;

    public function __construct($input = '', $path = '')
    {
        $this->file = $input;
        $this->path = dirname(APP_PATH) . '/public/' . trim($path, '/');
    }

    public function save($rename = '')
    {
        if (!$this->isUploaded()) {
            return FALSE;
        }
        if (!$this->isWritable()) {
            return FALSE;
        }
        if (!$this->size = $this->isSizeValid()) {
            return FALSE;
        }
        if (!$this->isAllowedFiletype()) {
            return FALSE;
        }

        $this->name = $this->_setFileName($rename);
        $file_tmp = $_FILES[$this->file]['tmp_name'];

        if (move_uploaded_file($file_tmp, "$this->path/$this->name")) {
            if ($this->resize) {
                if (is_file("$this->path/$this->name")) {
                    @chmod("$this->path/$this->name", 0777);
                    try {
                        $this->resizeImage("$this->path/$this->name", $this->resizeWidth, $this->resizeHeight);
                    } catch (Exception $e) {
                        DwAudit::error("Error al eliminar en el Sistema:" . $e->getMessage());
                        $this->setError('Se ha producido un error al intentar reescalar la imagen. <br />Verifica si el archivo es una imagen. Error: ' . $e->getMessage());
                        return FALSE;
                    }
                }
            }
            unset($_FILES[$this->file]);
            return array('error' => false, 'path' => $this->path, 'name' => $this->name, 'oldName' => $this->oldName, 'size' => $this->_toBytes4Humans($this->size));
        }

        $this->setError('No se pudo copiar el archivo al servidor. Intenta nuevamente.');
        return FALSE;
    }

    protected function resizeImage($filePath, $width, $height)
    {
        list($origWidth, $origHeight, $type) = getimagesize($filePath);

        $ratio = min($width / $origWidth, $height / $origHeight);
        $newWidth = round($origWidth * $ratio);
        $newHeight = round($origHeight * $ratio);

        switch ($type) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($filePath);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($filePath);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($filePath);
                break;
            default:
                throw new Exception("Tipo de imagen no soportado");
        }

        $destination = imagecreatetruecolor($newWidth, $newHeight);

        if ($type == IMAGETYPE_PNG) {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
            $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
            imagefilledrectangle($destination, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($destination, $filePath, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($destination, $filePath);
                break;
            case IMAGETYPE_GIF:
                imagegif($destination, $filePath);
                break;
        }

        imagedestroy($source);
        imagedestroy($destination);
    }

    public function isUploaded($file = '')
    {
        $file = (empty($file)) ? $this->file : $file;
        if (!(isset($_FILES[$file]) && is_uploaded_file($_FILES[$file]['tmp_name']))) {
            $this->setError('El archivo no se ha podido cargar en el servidor.');
            return FALSE;
        }
        if ($_FILES[$file]['error'] > 0) {
            $this->setError('El archivo cargado contiene errores. Intenta nuevamente.');
            return FALSE;
        }
        return TRUE;
    }

    public function isWritable($path = '')
    {
        $path = empty($path) ? $this->path : $path;
        if (!file_exists($path)) {
            $this->setError('No fue posible ubicar el directorio de carga del archivo.');
            return FALSE;
        }
        if (!is_writable($path)) {
            $this->setError('El directorio donde se alojará el archivo no tiene permisos de escritura. ' . $path);
            return FALSE;
        }
        return TRUE;
    }

    public function isSizeValid($file = '')
    {
        $file = empty($file) ? $this->file : $file;
        $total_bytes = ($this->maxSize) ? $this->_toBytes($this->maxSize) : 0;
        $this->size = $_FILES[$file]['size'];
        if ($this->maxSize !== NULL && ($this->size > $total_bytes)) {
            $this->setError("No se admiten archivos superiores a $this->maxSize");
            return FALSE;
        }
        return $this->size;
    }

    public function setAllowedTypes($types)
    {
        if (!is_array($types) && $types == '*') {
            $this->allowedTypes = '*';
            return;
        }
        $this->allowedTypes = explode('|', $types);
    }

    public function setEncryptName($encrypt)
    {
        $this->encryptName = $encrypt;
    }

    public function setSize($size = '15MB', $width = 0, $height = 0, $removeOld = FALSE)
    {
        $this->maxSize = $size;
        if ($width > 0 && $height > 0) {
            $this->resize = TRUE;
            $this->resizeWidth = $width;
            $this->resizeHeight = $height;
            $this->removeOld = ($removeOld === TRUE) ? TRUE : FALSE;
        }
    }

    public function isAllowedFiletype($file = '')
    {
        $file = empty($file) ? $_FILES[$this->file] : $_FILES[$file];
        $ext = $this->getExtension($file['name']);

        // Si se permiten todos los tipos
        if ($this->allowedTypes === '*') {
            return TRUE;
        }

        // Verificar si $this->allowedTypes es un array y no está vacío
        if (!is_array($this->allowedTypes) || empty($this->allowedTypes)) {
            $this->setError('No se ha especificado los tipos de archivos permitidos en el servidor.');
            return FALSE;
        }

        if (!in_array($ext, $this->allowedTypes)) {
            $this->setError('El tipo de archivo subido es incorrecto.');
            return FALSE;
        }

        $types = array('gif', 'jpg', 'jpeg', 'png', 'jpe', 'webp', 'heif');
        if (in_array($ext, $types)) {
            if (!in_array($file['type'], array('image/jpeg', 'image/pjpeg', 'image/gif', 'image/png', 'image/heif'))) {
                $this->setError('Solo se admiten imagenes JPEG, PNG y GIF.');
                return FALSE;
            }
            if (getimagesize($file['tmp_name']) === FALSE) {
                $this->setError('Oops! al parecer la imagen no es correcta.');
                return FALSE;
            }
        }

        return TRUE;
    }

    public function getExtension($filename)
    {
        $file = explode('.', $filename);
        $this->file_ext = Filter::get(end($file), 'lower');
        return $this->file_ext;
    }

    public function setError($error)
    {
        $this->_error = $error;
    }

    public function getError()
    {
        return $this->_error;
    }

    protected function _setFileName($rename)
    {
        if ($this->encryptName) {
            $name = md5(uniqid() . time()) . '.' . $this->file_ext;
            $this->oldName = $_FILES[$this->file]['name'];
        } else {
            $name = empty($rename) ? $_FILES[$this->file]['name'] : $rename . '.' . $this->file_ext;
        }
        return $name;
    }

    protected function _toBytes($size)
    {
        if (preg_match('/([KMGTP]?B)/', $size, $matches)) {
            $bytes_array = array('B' => 1, 'KB' => 1024, 'MB' => 1024 * 1024, 'GB' => 1024 * 1024 * 1024, 'TB' => 1024 * 1024 * 1024 * 1024, 'PB' => 1024 * 1024 * 1024 * 1024 * 1024);
            $size = floatval($size) * $bytes_array[$matches[1]];
        }
        return intval(round($size, 2));
    }

    protected function _toBytes4Humans($size)
    {
        $base = log($size) / log(1024);
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
        return round(pow(1024, $base - floor($base)), 2) . $suffixes[floor($base)];
    }
}
