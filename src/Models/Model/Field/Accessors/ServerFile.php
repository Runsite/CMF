<?php 
namespace Runsite\CMF\Models\Model\Field\Accessors;

class ServerFile {

	public $value = null;
	protected $attributes = null;

	public function __construct($value, $attributes)
	{
		$this->value = $value;
		$this->attributes = $attributes;
		return $this;
	}

	public function url()
	{
		return asset('storage/files/nodes/'.$this->attributes['node_id']. '/' .$this->attributes['field_name'] . '/' . $this->attributes['language_id'] . '/' . $this->value);
	}

	public function path()
	{
		return storage_path('app/public/files/nodes/'.$this->attributes['node_id']. '/' .$this->attributes['field_name'] . '/' . $this->attributes['language_id'] . '/' . $this->value);
	}

	public function defaultMethod()
	{
		return $this;
	}
}
