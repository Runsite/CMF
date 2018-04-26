<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Artisan;

class PublishVendor
{
	public $message = 'Publishing vendor (It may take several seconds)';

	public function handle($options)
	{
		Artisan::call('vendor:publish', [
			'--provider' => 'Runsite\CMF\RunsiteCMFServiceProvider',
			'--force' => true,
		]);
	}
}
