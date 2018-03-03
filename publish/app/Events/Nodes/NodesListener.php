<?php 

namespace App\Events\Nodes;


use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Contracts\NodesListener as NodesListenerInterface;

use App\Events\Nodes\Actions\{
	DefaultAction

	// Register your actions here
};

class NodesListener implements NodesListenerInterface
{
	public function store(Node $node)
	{
		(new DefaultAction())->handle($node);
	}

	public function update(Node $node)
	{
		(new DefaultAction())->handle($node);
	}

	public function delete(Node $node)
	{
		(new DefaultAction())->handle($node);
	}
}
