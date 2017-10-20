<?php 

namespace Runsite\CMF\Models\Model;

use Illuminate\{
	Database\Eloquent\Model as Eloquent,
	Support\Facades\Schema
};

use Runsite\CMF\Models\{
	Model\Field\Field,
	Model\Field\FieldGroup,
	User\Access\AccessModel,
	User\Group
};

class Model extends Eloquent
{
	protected $table = 'rs_models';
	protected $fillable = ['name', 'display_name', 'display_name_plural'];

	/**
	 * Get name of model table
	 *
	 * @return String
	 */
	public function tableName()
	{
		return snake_case($this->name);
	}

	public function className()
	{
		return ucfirst(camel_case($this->name));
	}

	public function controllerName()
	{
		return str_plural(ucfirst(camel_case($this->name))).'Controller';
	}

	public function settings()
	{
		return $this->hasOne(Setting::class, 'model_id');
	}

	public function fields()
	{
		return $this->hasMany(Field::class, 'model_id');
	}

	public function groups()
	{
		return $this->hasMany(FieldGroup::class, 'model_id');
	}

	public function methods()
	{
		return $this->hasOne(Method::class, 'model_id');
	}

	public function methodsCount()
	{
		$methods = $this->methods;
		$count = 0;
		foreach(['get', 'post', 'patch', 'delete'] as $method)
		{
			if($methods->{$method})
			{
				$count++;
			}
		}

		return $count;
	}

	public function dependencies()
	{
		return $this->belongsToMany(Model::class, 'rs_model_dependencies', 'model_id', 'depended_model_id')->orderBy('position', 'asc');
	}

	public static function create(array $attributes = [], $create_controller=false, $create_model=false)
	{
		$model = parent::query()->create($attributes);

		// Creating default settings
		$setting = Setting::create([
			'model_id' => $model->id,
			'show_in_admin_tree' => true,
			'nodes_ordering' => 'position asc',
			'dynamic_model' => $create_model ? $model->className() : null,
		]);

		$methods = Method::create(['model_id'=>$model->id, 'get'=> $create_controller ? $model->controllerName().'@show' : null]);

		// Creating schema
		Schema::create($model->tableName(), function($table) {
			$table->increments('id');
			$table->integer('node_id')->references('id')->on('rs_nodes')->unsigned();
			$table->integer('language_id')->references('id')->on('rs_languages')->unsigned();
			$table->timestamps();

			$table->foreign('node_id')->references('id')->on('rs_nodes')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('language_id')->references('id')->on('rs_languages')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});

		// Creating first required field
		$field = Field::create([
			'model_id' => $model->id,
			'type_id' => Field::getTypeId('boolean'),
			'position' => 1,
			'name' => 'is_active',
			'display_name' => 'Is active',
			'is_common' => true,
		]);

		// Creating access
		foreach(Group::get() as $group)
		{
			AccessModel::create([
				'group_id' => $group->id,
				'model_id' => $model->id,
			]);
		}

		if($create_model)
		{
			// Creating model file
			$stub = file_get_contents(__DIR__ . '/../../resources/stubs/Model.stub');
			$stub = str_replace('%_model_name_%', $model->className(), $stub);
			file_put_contents(app_path('Models\\'.$model->className().'.php'), $stub);
		}
		

		if($create_controller)
		{
			// Creating controller file
			$stub = file_get_contents(__DIR__ . '/../../resources/stubs/Controller.stub');
			$stub = str_replace('%_controller_name_%', $model->controllerName(), $stub);
			$stub = str_replace('%_model_name_plural_%', str_plural($model->name), $stub);
			$stub = str_replace('%_views_dirname_%', str_plural($model->name), $stub);
			$stub = str_replace('%_model_name_%', $model->name, $stub);
			file_put_contents(app_path('Http\Controllers\\'.$model->controllerName().'.php'), $stub);

			// Creating views
			if(! is_dir(base_path('resources/views/'.str_plural($model->name))))
			{
				mkdir(base_path('resources/views/'.str_plural($model->name)));
			}

			$stub = file_get_contents(__DIR__ . '/../../resources/stubs/view_index.blade.stub');
			$stub = str_replace('%_model_name_plural_%', str_plural($model->name), $stub);
			$stub = str_replace('%_model_name_%', $model->name, $stub);
			file_put_contents(base_path('resources/views/'.str_plural($model->name).'/index.blade.php'), $stub);

			$stub = file_get_contents(__DIR__ . '/../../resources/stubs/view_show.blade.stub');
			file_put_contents(base_path('resources/views/'.str_plural($model->name).'/view.blade.php'), $stub);
		}
		

		return $model;
	}

	public function update(array $attributes = [], array $options = [])
	{
		$model = Model::find($this->id);
		parent::update($attributes, $options);

		if($model->tableName() != $this->tableName())
		{
			Schema::rename($model->tableName(), $this->tableName());

			// renamig model file
			$model_file_path = app_path('Models\\'.$model->className().'.php');
			if(file_exists($model_file_path))
			{
				$class = file_get_contents();
				$class = str_replace('class ' . $model->className() . ' extends', 'class ' . $this->className() . ' extends', $class);
				file_put_contents(app_path('Models\\'.$this->className().'.php'), $class);
				unlink($model_file_path);
			}
		}
	}

	public function delete()
	{
		Schema::dropIfExists($this->tableName());
		unlink(app_path('Models\\'.$this->className().'.php'));
		return parent::delete();
	}


}