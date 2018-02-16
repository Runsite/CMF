<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTemplates;

use Runsite\CMF\Models\Model\Field\FieldTemplates\TemplateTrait;

class ContentTemplate {

	use TemplateTrait;

	public $name = 'content';
	public $display_name;
	public $hint;
	public $type_id = 11;
	public $group_name = null;
	public $is_common = false;
	public $is_visible_in_nodes_list = false;

	public function __construct()
	{
		$this->display_name = trans('runsite::field_templates.'.$this->name.'.display_name');
		$this->hint = trans('runsite::field_templates.'.$this->name.'.hint');
	}
}
