<?php

namespace App\Facades\Storage;

use App\Facades\Faker\Faker;
use App\Model\File;

class Storage
{
	private array $mimes = [
		'txt' => 'text/plain',
		'css' => 'text/css',
		'json' => 'application/json',
		'xml' => 'application/xml',
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
		// archives
		'zip' => 'application/zip',
		// audio/video
		'mp3' => 'audio/mpeg',
		'qt' => 'video/quicktime',
		'mov' => 'video/quicktime',
		// adobe
		'pdf' => 'application/pdf',
		// ms office
		'doc' => 'application/msword',
		'xls' => 'application/vnd.ms-excel',
		'docx' => 'application/msword',
		'xlsx' => 'application/vnd.ms-excel',
	];
	
	private static string $disk;
	private static string $relativePath;
	
	public static function disk(): Storage
	{
		self::checkDirectories();
		self::$relativePath = '/storage/public';
		self::$disk = storage_path('public');
		
		return new self();
	}
	
	public static function private(): Storage
	{
		self::checkDirectories();
		self::$disk = storage_path('private');
		return new self();
	}
	
	private static function checkDirectories()
	{
		if (! is_dir(storage_path('public/'))) {
			mkdir(storage_path('public/'), 0775);
		}
		
		if (! is_dir(storage_path('private/'))) {
			mkdir(storage_path('private/'), 0775);
		}
	}
	
	public function put($file, $content, $replace = false): bool
	{
		$file = ltrim($file, '/');
		
		if ($replace) {
			$this->putFile($file, $content);
			return true;
		}
		
		if (! is_file(self::$disk.'/'.$file)) {
			$this->putFile($file, $content);
			return true;
		}
		
		return false;
	}
	
	protected function putFile($file, $content): bool
	{
		$file = ltrim($file, '/');
		
		if (file_put_contents(self::$disk.'/'.$file, $content)) {
			chmod(self::$disk.'/'.$file, 0775);
			return true;
		}
		
		return false;
	}
	
	public function upload($file, $destination = '/', $as = null): bool
	{
		if ($file['error'] === UPLOAD_ERR_OK) {
			$this->make($destination);
			
			$pathInfo = pathinfo($file['name']);
			$location  = self::$disk.$destination;
			
			if (class_exists(File::class)) {
				$hash = Faker::getUniqueStr(File::class, 'hash', 100);
				$location .= $hash.'.'.$pathInfo['extension'];
			} else {
				$location .= $as
					? mb_strtolower($as).'.'.$pathInfo['extension']
					: mb_strtolower($file['name']);
			}
			
			if (move_uploaded_file($file['tmp_name'], $location)) {
				if ($this->checkFile($location) === true) {
					chmod($location, 0775);
					if (class_exists(File::class)) {
						File::insert([
							'name' => $pathInfo['filename'],
							'hash' => $hash,
							'dir'  => self::$relativePath.$destination,
							'ext'  => '.'.$pathInfo['extension'],
							'sha1' => sha1_file($location)
						])->exec();
					}
					return true;
				}
			}
		}
		
		return false;
	}
	
	public function get(string $path): string
	{
		$path = ltrim($path, '/');
		return file_get_contents(self::$disk.'/'.$path);
	}
	
	public function make(string $path, int $mode = 0775): Storage
	{
		$path = ltrim($path, '/');
		
		if (! is_dir(self::$disk.'/'.$path)) {
			mkdir(self::$disk.'/'.$path, $mode, true);
		}
		
		return $this;
	}
	
	public function download($file)
	{
		$file = ltrim($file, '/');
		
		if ($fd = fopen(self::$disk.'/'.$file, 'r')) {
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.basename(self::$disk.'/'.$file).'"');
			header('Content-Length: '.filesize(self::$disk.'/'.$file));
			header('Content-Transfer-Encoding: Binary');
			header('Cache-control: private');
			while (! feof($fd)) {
				echo fread($fd, 2048);
			}
		}
		
		ob_flush();
		fclose($fd);
		exit;
	}
	
	public function remove($path = null): bool
	{
		$path = ltrim($path, '/');
		
		if (is_file(self::$disk.'/'.$path)) {
			unlink(self::$disk.'/'.$path);
			return true;
		}
		
		return false;
	}
	
	private function checkFile(string $destination): bool
	{
		$pathInfo = pathinfo($destination);
		
		if (isset($this->mimes[$pathInfo['extension']])
			&& (string) $this->mimes[$pathInfo['extension']] === (string) mime_content_type($destination)
		) {
			return true;
		}
		
		self::remove(str_replace(storage_path(), '', $destination));

		return false;
	}
}
