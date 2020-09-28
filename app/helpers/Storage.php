<?php
namespace App\Helpers;

class Storage
{
    private array $mimes = [
        'txt' => 'text/plain',
        'css' => 'text/css',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',
        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',
        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',
        // adobe
        'pdf' => 'application/pdf',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',
        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'docx' => 'application/msword',
        'xlsx' => 'application/vnd.ms-excel',
        'pptx' => 'application/vnd.ms-powerpoint',
        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    ];
    
    private static string $disk;

    public static function disk($disk)
    {
        if(!is_dir(storage_path('public/'))) {
            mkdir(storage_path('public/'), 0775);
        }

        if(!is_dir(storage_path('private/'))) {
            mkdir(storage_path('private/'), 0775);
        }

        self::$disk = storage_path($disk);
        return new self();
    }

    public function put($file, $content, $replace = false)
    {
        if ($replace === true) {
            $this->putFile($file, $content);
            return true;
        }
        
        if (!is_file(self::$disk.'/'.$file)) {
            $this->putFile($file, $content);
            return true;
        }

        return false;
    }
    
    protected function putFile($file, $content)
    {
        if (file_put_contents(self::$disk.'/'.$file, $content)) {
            chmod(self::$disk.'/'.$file, 0775);
            return true;
        }
    }

    public function upload($file, $destination = '/', $as = null)
    {
        if(!isset($file['name']) || empty($file['name'])) {
            Session::msg(['Upload failed! file not exist!'], 'danger');
            return false;
        }
        
        if ($file['error'] === UPLOAD_ERR_OK) {

            $this->make($destination);

            $destination  = self::$disk . $destination;
            $destination .= $as ? strtolower($as).'.'.pathinfo($file['name'], PATHINFO_EXTENSION) : strtolower($file['name']);

            if(move_uploaded_file($file['tmp_name'], $destination)) {
                if ($this->checkFile($destination) === true) {
                    return true;
                }
                return false;
            }
            
            return false;
        } else {
            Session::msg(['Upload failed! '. $file['name'] . ' is corrupted.', 'danger']);
            return false;
        }
    }

    public function get($path = null, $pattern = '*', $glob_type = GLOB_BRACE)
    {
        $path = self::$disk . '/' . $path;
        $result = [];

        foreach (glob($path.$pattern, $glob_type) as $key => $value) {
            $result[$key] = str_replace(storage_path(), '', $value);
        }

       return $result;
    }
    
    public function getContent($path)
    {
        return file_get_contents(self::$disk.'/'.$path);
    }

    public function make($path, $mode = 0775)
    {
        if(!is_dir(self::$disk.'/'.$path))
            mkdir(self::$disk.'/'.$path, $mode, true);

        return $this;
    }

    public function download($file)
    {
        if ($fd = fopen(self::$disk. '/' .$file, "r")) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename(self::$disk. '/' .$file) . '"');
            header('Content-Length: ' . filesize(self::$disk. '/' .$file));
            header("Content-Transfer-Encoding: Binary");
            header("Cache-control: private");
            while (!feof($fd)) {
                echo fread($fd, 2048);
            }
        }
        ob_flush();
        fclose($fd);
        exit;
    }

    public static function remove($path = null)
    {
        if (is_file(storage_path($path))) {
            unlink(storage_path($path));
            return true;
        } else if (!file_exists(storage_path($path))) {
            return false;
        } else if (is_dir(storage_path($path))) {
            foreach(scandir(storage_path($path)) as $file) {
                if ($file != '.' && $file != '..') {
                    self::remove($path.'/'.$file);
                }
            }
            rmdir(storage_path($path));
        }
        return false;
    }
    
    private function checkFile($destination)
    {
        $pathInfo = pathinfo($destination);
        if (isset($this->mimes[$pathInfo['extension']]) === true
            && (string) $this->mimes[$pathInfo['extension']] === (string) mime_content_type ($destination)
        ) {
            return true;
        } else {
            self::remove(str_replace(storage_path(), '', $destination));
            return false;
        }
    }
}
