<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Runsite\CMF\Models\User\Group;

class CreateDevelopersGroup 
{
    public $message = 'Creating developers group';

    public function handle($options)
    {
        $group = Group::create([
            'name' => 'Developer',
            'description' => 'Developers',
        ]);
    }
}
