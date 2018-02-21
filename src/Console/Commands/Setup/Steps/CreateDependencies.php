<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Model\Dependency;

class CreateDependencies
{
	public $message = 'Creating model dependencies';

	public function handle($options)
	{
		$root = Model::where('name', 'root')->first();
		$section = Model::where('name', 'section')->first();
		$admin_section = Model::where('name', 'admin_section')->first();
		$page = Model::where('name', 'page')->first();

		Dependency::create([
			'model_id' => $root->id,
			'depended_model_id' => $section->id,
		]);

		Dependency::create([
			'model_id' => $section->id,
			'depended_model_id' => $section->id,
		]);

		Dependency::create([
			'model_id' => $root->id,
			'depended_model_id' => $admin_section->id,
		]);

		Dependency::create([
			'model_id' => $root->id,
			'depended_model_id' => $page->id,
		]);
	}
}
