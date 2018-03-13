<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Runsite\CMF\Models\Application;

class CreateApplications
{
    public $message = 'Creating applications';

    protected $applications = [
        'models' => [
            'is_tool' => true,
            'color_name' => 'red',
        ],
        'users' => [
            'is_tool' => true,
            'color_name' => 'success',
        ],
        'languages' => [
            'is_tool' => true,
            'color_name' => 'primary',
        ],
        'nodes' => [
            'is_tool' => false,
            'color_name' => '',
        ],
        'translations' => [
            'is_tool' => false,
            'color_name' => '',
        ],
        'elfinder' => [
            'is_tool' => true,
            'color_name' => 'warning',
        ],
    ];

    public function handle($options)
    {
        foreach($this->applications as $name=>$parameters)
        {
            Application::create([
                'name' => $name,
                'is_tool' => $parameters['is_tool'],
                'color_name' => $parameters['color_name'],
            ]);
        }
    }
}
