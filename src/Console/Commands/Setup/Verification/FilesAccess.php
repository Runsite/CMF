<?php

namespace Runsite\CMF\Console\Commands\Setup\Verifications;

use Exception;

class FilesAccess {

	public $message = 'Verification files access';
	public $paths = [];

	public function __construct()
	{
		$this->paths = [
			app_path('User.php'),
			app_path('Http\Controllers'),
			app_path('Http\Models'),
		];
	}

	public function handle()
	{
		foreach($this->paths as $path)
		{
			$this->verifyPath($path);
		}
	}

	public function verifyPath($path)
	{
		if(file_exists($path))
		{
			if(is_dir($path))
			{
				$objects = scandir($path);
				foreach($objects as $object)
				{
					if($object != '.' and $object != '..')
					{
						$this->verifyPath($object);
					}
				}
				if(! is_writable($path))
				{
					throw new Exception('File or directory in not writable: '.$path);
				}
			}
		}
	}
}
