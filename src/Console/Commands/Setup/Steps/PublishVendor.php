<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Artisan;

class PublishVendor
{
	public $message = 'Publishing vendor';

	public function handle()
	{
		Artisan::call('vendor:publish', [
			'--provider' => 'Runsite\CMF\RunsiteCMFServiceProvider',
			'--force' => true,
		]);
	}
}