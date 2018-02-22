<?php 

namespace Runsite\CMF\Models\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Method extends Eloquent
{
	protected $table = 'rs_model_methods';
	protected $fillable = ['model_id', 'get', 'post', 'patch', 'delete'];

	public function model()
	{
		return $this->belongsTo(Model::class, 'model_id');
	}

	public function validMethod($method)
	{
		if(is_null($this->{$method}))
		{
			return true;
		}

		if(! str_is('*@*', $this->{$method}))
		{
			return trans('runsite::models.methods.errors.String must contain @');
		}

		$string_parts = explode('@', $this->{$method});
		$class_name = '\App\Http\Controllers\\'.$string_parts[0];
		$method = $string_parts[1];

		if(! class_exists($class_name))
		{
			return trans('runsite::models.methods.errors.Сontroller does not exist');
		}

		// $class = new $class_name;

		if(! method_exists($class_name, $method))
		{
			return trans('runsite::models.methods.errors.Method does not exist');
		}

		return true;
	}

	public function getControllers()
	{
		$controllers = [];

		foreach(['get', 'post', 'patch', 'delete'] as $method)
		{
			if($this->{$method} and $this->validMethod($method) === true)
			{
				$string_parts = explode('@', $this->{$method});
				$class_name = $string_parts[0];
				$controllers[$class_name] = $class_name;
			}
		}

		return $controllers;
	}
}
