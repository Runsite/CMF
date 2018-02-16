<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTemplates;

use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Model\Field\FieldGroup;

trait TemplateTrait {

	public function install(Model $model)
	{

		if($this->group_name)
		{
			$fieldGroup = FieldGroup::where('model_id', $model->id)->where('name', $this->group_name)->first();
			if(! $fieldGroup)
			{
				$fieldGroup = FieldGroup::create([
					'model_id' => $model->id,
					'name' => $this->group_name,
				]);
			}
		}

		Field::create([
			'name' => $this->name,
			'display_name' => $this->display_name,
			'hint' => $this->hint,
			'type_id' => $this->type_id,
			'model_id' => $model->id,
			'group_id' => $this->group_name ? $fieldGroup->id : null,
			'is_common' => $this->is_common,
			'is_visible_in_nodes_list' => $this->is_visible_in_nodes_list,
		]);
	}

	public function getTypeName()
	{
		$field = new Field;

		$type = new $field->types[$this->type_id];

		return $type::$name;
	}
}
