<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Runsite\CMF\Models\User\Group;
use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Models\User\Access\AccessApplication;
use Runsite\CMF\Models\Application;

class GivePermissions
{
    public $message = 'Giving permissions';

    public function handle($options)
    {
        $group = Group::findOrFail(1);
        $node = Node::findOrFail(1);
        $group->assignAccess(3, $node->id, false);

        foreach(Application::get() as $application)
        {
            $group->assignAccessToApplication(3, $application);
        }
    }
}
