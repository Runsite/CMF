<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTemplates;

use Runsite\CMF\Models\Model\Field\FieldTemplates\TemplateTrait;

class ImageTemplate {

	use TemplateTrait;

	public $name = 'image';
	public $display_name;
	public $hint;
	public $type_id = 5;
	public $group_name = null;
	public $is_common = true;
	public $is_visible_in_nodes_list = true;

	public function __construct()
	{
		$this->display_name = trans('runsite::field_templates.'.$this->name.'.display_name');
		$this->hint = trans('runsite::field_templates.'.$this->name.'.hint');
	}
}
