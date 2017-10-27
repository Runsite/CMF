<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Artisan;

class StoragePreparation
{
	public $message = 'Storage preparation';

	public function handle()
	{
		Artisan::call('storage:link');
	}
}