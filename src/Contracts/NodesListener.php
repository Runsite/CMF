<?php 

namespace Runsite\CMF\Contracts;

use Runsite\CMF\Models\Node\Node;

interface NodesListener 
{
	public function store(Node $node);

	public function update(Node $node);

	public function delete(Node $node);
}
