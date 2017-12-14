<?php 
namespace Runsite\CMF\Models\Model\Field\Accessors;

use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Models\Model\Field\Field;

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
		if(!$this->relation and $this->value)
		{
			$node = Node::findOrFail($this->value);
			$this->relation = $node->dynamic()->where('language_id', $this->attributes['language_id'])->first();
		}
		
		return $this->relation;
	}

	public function availableValues()
	{
		$node = Node::findOrFail($this->attributes['node_id']);
		$field = Field::where('model_id', $node->model_id)->where('name', $this->attributes['field_name'])->first();

		$related_model_name = $field->settings()->where('parameter', 'related_model_name')->first();
		$related_parent_node_id = $field->settings()->where('parameter', 'related_parent_node_id')->first();

		$values = [];

		if(!$related_model_name->value)
		{
			return $values;
		}

		$values = M($related_model_name->value);

		if($related_parent_node_id->value)
		{
			$values = $values->where('parent_id', $related_parent_node_id->value);
		}

		$values = $values->get();

		return $values;
	}
}