<?php 

namespace App\Events\Nodes\Actions;

use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Contracts\NodeAction;

class DefaultAction implements NodeAction 
{
	public function handle(Node $node)
	{
		return;
	}
}
