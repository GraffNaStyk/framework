<?php

namespace App\Helpers;

class Storage
{
    private static $disk;

    public static function disk($disk)
    {
        if(!is_dir(storage_path('public/'))) {
            mkdir(storage_path('public/'), 0775);
        }

        if(!is_dir(storage_path('private/'))) {
            mkdir(storage_path('public/'), 0775);
        }

        self::$disk = storage_path($disk);
        return new self();
    }

    public function put($file, $content)
    {
        if(!is_file(self::$disk.'/'.$file)) {
            if(file_put_contents(self::$disk.'/'.$file, $content))
                return true;
        }

        return false;
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
            $destination .= $as ? strtolower($as) : strtolower($file['name']);

            if(move_uploaded_file($file['tmp_name'], $destination))
                return true;

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
            while (!feof($fd))
                echo fread($fd, 2048);
        }
        ob_flush();
        fclose($fd);
        exit;
    }

    public static function remove($path = null)
    {
        if(is_file(storage_path($path))) {
            unlink(storage_path($path));
            return true;
        } elseif(!file_exists(storage_path($path))) {
            return false;
        } elseif(is_dir(storage_path($path))) {
            foreach(scandir(storage_path($path)) as $file) {
                if ($file != '.' && $file != '..') {
                    self::remove($path.'/'.$file);
                }
            }
            rmdir(storage_path($path));
        }
        return false;
    }
}
