<?php 
namespace Runsite\CMF\Models\Model\Field\Accessors;

use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Models\Node\Relation;
use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Dynamic\Language;

class RelationToMany {

	public $value = null;
	protected $attributes = null;
	protected $relations = null;

	public function __construct($value, $attributes)
	{
		$this->value = $value;
		$this->attributes = $attributes;
	}

	public function relations()
	{
		if(!$this->relations)
		{
			$node = Node::findOrFail($this->attributes['node_id']);
			$field = Field::where('model_id', $node->model_id)->where('name', $this->attributes['field_name'])->first();
			$language = Language::findOrFail($this->attributes['language_id']);
			$related_model_name = $field->settings()->where('parameter', 'related_model_name')->first();

			if(empty($related_model_name->value))
			{
				return null;
			}

			$relations = Relation::where('language_id', $language->id)
			->where('node_id', $node->id)
			->where('field_id', $field->id)
			->get();

			$this->relations = M($related_model_name->value, true, $language->locale)->whereIn('node_id', $relations->pluck('related_node_id'))->get();
		}
		

		
		return $this->relations;
	}

	public function defaultMethod()
	{
		return $this->relations();
	}
}
