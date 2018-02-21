<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Runsite\CMF\Models\Dynamic\Language;

class CreateFirstLanguage
{
	public $message = 'Creating first language';

	public function handle($options)
	{
		Language::create(['locale'=>$options['app_locale']['locale'], 'display_name'=>$options['app_locale']['display_name'], 'is_active'=>true]);

		if($options['app_locale']['locale'] != $options['fallback_locale']['locale'])
		{
			Language::create(['locale'=>$options['fallback_locale']['locale'], 'display_name'=>$options['fallback_locale']['display_name'], 'is_active'=>true]);
		}
	}
}
