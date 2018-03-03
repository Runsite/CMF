<?php 

namespace Runsite\CMF\Contracts;

use Runsite\CMF\Models\Node\Node;

interface NodeAction {

	public function handle(Node $node);
}
