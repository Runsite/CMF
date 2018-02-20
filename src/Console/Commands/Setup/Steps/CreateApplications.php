<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Runsite\CMF\Models\Application;

class CreateApplications
{
    public $message = 'Creating applications';

    protected $applications = [
        'models',
        'users',
        'languages',
        'nodes',
        'translations',
        'elfinder',
    ];

    public function handle()
    {
        foreach($this->applications as $application)
        {
            Application::create(['name'=>$application]);
        }
    }
}
