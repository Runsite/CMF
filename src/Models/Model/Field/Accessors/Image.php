<?php 
namespace Runsite\CMF\Models\Model\Field\Accessors;

class Image {

	public $value = null;
	protected $attributes = null;

	public function __construct($value, $attributes)
	{
		$this->value = $value;
		$this->attributes = $attributes;
		return $this;
	}

	public function size($size)
	{
		return asset('storage/images/'.$this->attributes['node_id']. '/' .$this->attributes['field_name'] . '/' . $this->attributes['language_id'] . '/' . $size . '/' . $this->value);
	}

	public function max()
	{
		return $this->size('max');
	}

	public function min()
	{
		return $this->size('min');
	}

	public function defaultMethod()
	{
		return $this;
	}
}
