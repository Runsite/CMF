<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Runsite\CMF\Models\Dynamic\Language;
use Larapack\ConfigWriter\Repository as ConfigRepository;

class CreateFirstLanguage
{
	public $message = 'Creating first language';

	public function handle($options)
	{
		Language::create(['locale'=>$options['app_locale']['locale'], 'display_name'=>$options['app_locale']['display_name'], 'is_active'=>true, 'is_main'=>true]);

		$config = new ConfigRepository('app');
		$config->set('locale', $options['app_locale']['locale']);

		if($options['app_locale']['locale'] != $options['fallback_locale']['locale'])
		{
			Language::create(['locale'=>$options['fallback_locale']['locale'], 'display_name'=>$options['fallback_locale']['display_name'], 'is_active'=>true]);

			$config->set('fallback_locale', $options['fallback_locale']['locale']);
		}
		else
		{
			$config->set('fallback_locale', $options['app_locale']['locale']);
		}

		$config->save();
	}
}
