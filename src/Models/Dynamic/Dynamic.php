<?php 

namespace Runsite\CMF\Models\Dynamic;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Models\Node\Path;
use Runsite\CMF\Helpers\GlobalScope;
use LaravelLocalization;
use Goszowski\Temp\Temp;

class Dynamic extends Eloquent
{
    protected $table = '';
    protected $fillable = ['node_id', 'language_id'];
    protected $dates = [];

    public function __construct($table, $dates=null)
    {
        parent::__construct();

        $scope = new GlobalScope;

        if($table)
        {
            $this->table = $table;
            $scope->set('DynamicTable', $table);
        }
        elseif($scope->get('DynamicTable'))
        {
            $this->table = $scope->get('DynamicTable');
        }

        if($dates)
        {
            $this->dates = $dates;
        }
    }

    public function node()
    {
        return $this->belongsTo(Node::class, 'node_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function save(array $options = []): bool
    {
        if($this->name)
        {
            $pathExists = Path::where('node_id', $this->node->id)->where('name', $this->node->generatePath($this->name, false, $this->language_id))->count();

            if(!$pathExists)
            {
                // creating new path
                Path::create([
                    'node_id' => $this->node->id,
                    'language_id' => $this->language_id,
                    'name' => $this->node->generatePath($this->name),
                ]);
            }
        }

        $this->node->putAnalytic(2);

        return parent::save($options);
    }

    public function first(): Dynamic
    {
        // $this->orderBy('rs_nodes.position', 'asc');
        return parent::first();
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (! $key) {
            return;
        }



        $node_temp_key = 'node_model_fields_'.$this->attributes['node_id'];
        $node = Temp::get($node_temp_key);
        if(!$node)
        {
            $node = Temp::put($node_temp_key, Node::with('model.fields')->find($this->attributes['node_id']));
        }

        foreach($node->model->fields as $field)
        {
            $accessor_class = 'Runsite\CMF\Models\Model\Field\Accessors\\'.ucfirst(camel_case($field->type()::$displayName));

            if(($field->name == $key or $field->name == $key.'_id') and class_exists($accessor_class))
            {
                $value = null;

                if($field->type()::$needField)
                {
                    $value = $this->attributes[$key] ?? $this->attributes[$key.'_id'];
                }

                return (new $accessor_class($value, [
                    'node_id' => $this->attributes['node_id'],
                    'field_name' => $key,
                    'language_id' => $this->attributes['language_id'],
                ]))->defaultMethod();
            }
        }

        // If the attribute exists in the attribute array or has a "get" mutator we will
        // get the attribute's value. Otherwise, we will proceed as if the developers
        // are asking for a relationship's value. This covers both types of values.
        if (array_key_exists($key, $this->attributes) ||
            $this->hasGetMutator($key)) {
            return $this->getAttributeValue($key);
        }
        // Here we will determine if the model base class itself contains this given key
        // since we don't want to treat any of those methods as relationships because
        // they are all intended as helper methods and none of these are relations.
        if (method_exists(parent::class, $key)) {
            return;
        }
        return $this->getRelationValue($key);
    }
}
