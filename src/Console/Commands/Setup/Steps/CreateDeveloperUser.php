<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Runsite\CMF\Models\User\User;
use Runsite\CMF\Models\User\Group;

class CreateDeveloperUser 
{
    public $message = 'Creating developer user';

    public function handle()
    {
        $group = Group::create([
            'name' => 'Developer',
            'description' => 'Developers',
        ]);

        $user = User::create([
            'name' => 'Developer',
            'email' => 'developer@domain.com',
            'password' => bcrypt('secret'),
        ]);

        $user->assignGroup($group);

        // TODO: remove
        $group = Group::create([
            'name' => 'Administrator',
            'description' => 'Administrators',
        ]);

        $group = Group::create([
            'name' => 'Manager',
            'description' => 'Managers',
        ]);
    }
}