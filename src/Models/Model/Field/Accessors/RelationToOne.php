<?php 
namespace Runsite\CMF\Models\Model\Field\Accessors;

use Runsite\CMF\Models\Node\Node;

class RelationToOne {

	public $value = null;
	protected $attributes = null;
	protected $relation = null;

	public function __construct($value, $attributes)
	{
		$this->value = $value;
		$this->attributes = $attributes;
	}

	public function relation()
	{
		if(!$this->relation)
		{
			$node = Node::findOrFail($this->value);
			$this->relation = $node->dynamic()->where('language_id', $this->attributes['language_id'])->first();
		}
		
		return $this->relation;
	}
}