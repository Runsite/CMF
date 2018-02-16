<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTemplates;

use Runsite\CMF\Models\Model\Field\FieldTemplates\TemplateTrait;

class SEODescriptionTemplate {

	use TemplateTrait;

	public $name = 'description';
	public $display_name;
	public $hint;
	public $type_id = 10;
	public $group_name = 'SEO';
	public $is_common = false;
	public $is_visible_in_nodes_list = false;

	public function __construct()
	{
		$this->display_name = trans('runsite::field_templates.'.$this->name.'.display_name');
		$this->hint = trans('runsite::field_templates.'.$this->name.'.hint');
	}
}
