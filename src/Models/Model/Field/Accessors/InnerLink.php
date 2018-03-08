<?php 
namespace Runsite\CMF\Models\Model\Field\Accessors;

use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Models\Model\Field\Field;

class InnerLink {

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
		if(!$this->relation and $this->value)
		{
			$node = Node::findOrFail($this->value);
			$this->relation = $node->dynamic()->where('language_id', $this->attributes['language_id'])->first();
		}
		
		return $this->relation;
	}

	public function defaultMethod()
	{
		return $this->relation();
	}
}
