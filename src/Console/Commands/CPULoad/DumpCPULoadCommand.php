<?php

namespace Runsite\CMF\Console\Commands\CPULoad;

use Illuminate\Console\Command;
use Runsite\CMF\Models\Tools\CPULoad;
use Runsite\CMF\Models\User\User;
use Carbon\Carbon;

class DumpCPULoadCommand extends Command
{
	protected $signature = 'runsite:dump-cpu-load';
	protected $description = 'Saving CPU load information';

	public function handle()
	{
		// Cleaning old values
		CPULoad::where('created_at', '<=', Carbon::now()->subDays(7))->delete();


		$value = sys_getloadavg()[1]; // 5 minutes

		$item = CPULoad::create([
			'value' => $value,
		]);

		$this->comment('Saved: '.$value);

		if($value >= config('runsite.cmf.cpu-load.max-value'))
		{
			foreach(User::get() as $user)
			{
				$user->notify(null, trans('runsite::tools.cpu-load.warning'), 'exclamation');
			}
		}
	}
}
