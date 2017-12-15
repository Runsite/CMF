<?php 

namespace Runsite\CMF\Models\Model\Field;

use DB;
use Auth;

use Illuminate\{
    Database\Eloquent\Model as Eloquent,
    Support\Facades\Schema
};

use Runsite\CMF\Models\{
    Node\Relation,
    Model\Model,
    User\Access\AccessField,
    User\Group
};

use Runsite\CMF\Models\Model\Field\FieldTypes\{
    BooleanType,
    DateTimeType,
    DateType,
    DecimalType,
    ImageType,
    RelationToManyType,
    RelationToOneType,
    ServerFileType,
    StringType,
    TextareaType,
    CkeditorType
};

class Field extends Eloquent
{
    protected $table = 'rs_fields';
    protected $fillable = ['model_id', 'type_id', 'group_id', 'position', 'name', 'display_name', 'hint', 'is_common', 'is_visible_in_nodes_list'];

    public $types = [
        1  => BooleanType::class,
        2  => DateTimeType::class,
        3  => DateType::class,
        4  => DecimalType::class,
        5  => ImageType::class,
        6  => RelationToManyType::class,
        7  => RelationToOneType::class,
        8  => ServerFileType::class,
        9  => StringType::class,
        10 => TextareaType::class,
        11 => CkeditorType::class,
    ];

    public static function getTypeId($needleName)
    {
        $field = new Field;
        foreach($field->types as $id=>$type)
        {
            if($type::$displayName == $needleName)
            {
                return $id;
            }
        }

        return false;
    }

    public function model()
    {
        return $this->belongsTo(Model::class);
    }

    public function type()
    {
        return new $this->types[$this->type_id];
    }

    public function group()
    {
        return $this->belongsTo(FieldGroup::class);
    }

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    public function findSettings($parameter)
    {
        return $this->settings()->where('parameter', $parameter)->first();
    }

    public function getControlPath()
    {
        $base = $this->types[$this->type_id]::$displayName.'.';



        if(! Auth::user()->access()->field($this)->edit)
        {
            if(! Auth::user()->access()->field($this)->read)
            {
                return null;
            }

            return $base.'.readonly';
        }

        $control = $this->findSettings('control')->value;
        return $base.'.'.$control;
    }

    public static function getNewPosition(array $attributes = [])
    {
        $lastPosition =  Field::where('model_id', $attributes['model_id'])->orderBy('position', 'desc')->first();
        if($lastPosition)
        {
            return $lastPosition->position + 1;
        }

        return 1;
    }


    public static function create(array $attributes = [])
    {
        $attributes['position'] = self::getNewPosition($attributes);
        $field = parent::query()->create($attributes);
        $type = $field->types[$field->type_id];

        if($type::$needField)
        {
            // Creating schema
            Schema::table($field->model->tableName(), function($table) use($type, $field) 
            {
                if($type::defaultValue())
                {
                    $table->{$type::$name}($field->name, $type::$size['base'], $type::$size['extra'])->nullable()->default($type::defaultValue());
                }
                else
                {
                    $table->{$type::$name}($field->name, $type::$size['base'], $type::$size['extra'])->nullable();
                }
            });
        }

        // Creating field settings
        foreach($type::$defaultSettings as $parameter=>$setting)
        {
            Setting::create([
                'field_id' => $field->id,
                'parameter' => $parameter,
                'value' => $setting['value'],
            ]);
        }

        // Creating groups access
        foreach(Group::get() as $group)
        {
            AccessField::create([
                'group_id' => $group->id,
                'field_id' => $field->id,
                'access' => 2,
            ]);
        }
        
        return $field;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $field = Field::find($this->id);
        parent::update($attributes, $options);
        $type = $this->types[$this->type_id];

        if($field->type_id != $this->type_id)
        {
            DB::table($field->model->tableName())->update([
                $field->name => null
            ]);

            Schema::table($field->model->tableName(), function($table) use($type, $field)
            {
                // TODO: окультурить ці кондішини бо виглядає як індія
                if($type::$needField and $field->types[$field->type_id]::$needField)
                {
                    if($type::defaultValue())
                    {
                        $table->{$type::$name}($field->name, $type::$size['base'], $type::$size['extra'])->default($type::defaultValue())->change();
                    }
                    else
                    {
                        $table->{$type::$name}($field->name, $type::$size['base'], $type::$size['extra'])->change();
                    }
                }

                if($type::$needField and !$field->types[$field->type_id]::$needField)
                {
                    if($type::defaultValue())
                    {
                        $table->{$type::$name}($field->name, $type::$size['base'], $type::$size['extra'])->default($type::defaultValue());
                    }
                    else
                    {
                        $table->{$type::$name}($field->name, $type::$size['base'], $type::$size['extra']);
                    }
                }
                
                if(!$type::$needField and $field->types[$field->type_id]::$needField)
                {
                    $table->dropColumn($field->name);
                }
            });

            // Replacing field settings
            Setting::where('field_id', $field->id)->delete();
            foreach($type::$defaultSettings as $parameter=>$setting)
            {
                Setting::create([
                    'field_id' => $this->id,
                    'parameter' => $parameter,
                    'value' => $setting['value'],
                ]);
            }

            if($type::$needField and !$field->types[$field->type_id]::$needField)
            {
                Relation::where('field_id', $field->id)->delete();
            }
        }

        if($field->name != $this->name)
        {
            Schema::table($field->model->tableName(), function($table) use($field)
            {
                $table->renameColumn($field->name, $this->name);
            });
        }
    }

    public function delete()
    {
        if($this->type()::$needField)
        {
            Schema::table($this->model->tableName(), function($table)
            {
                $table->dropColumn([$this->name]);
            });
        }

        // Calculating position
        Field::where('model_id', $this->model_id)->where('position', '>', $this->position)->decrement('position');

        return parent::delete();
    }

    public function beforeDeleting($node)
    {
        $type = $this->types[$this->type_id];
        return $type::beforeDeleting($this, $node);
    }

    public function moveDown()
    {
        Field::where('model_id', $this->model_id)->where('position', '>', $this->position)->orderBy('position', 'asc')->first()->decrement('position');
        return $this->increment('position');
    }

    public function moveUp()
    {
        Field::where('model_id', $this->model_id)->where('position', '<', $this->position)->orderBy('position', 'desc')->first()->increment('position');
        return $this->decrement('position');
    }

    public function getValue($dynamic=null, $language=null)
    {
        if(!$dynamic or !$language)
        {
            // default value
            return $this->type()::defaultValue();
        }

        return $dynamic->where('language_id', $language->id)->first()->{$this->name};
    }

    public function prevField()
    {
        return Field::where('model_id', $this->model_id)
            ->where('position', '<', $this->position)
            ->orderBy('position', 'desc')
            ->first();
    }

    public function nextField()
    {
        return Field::where('model_id', $this->model_id)
            ->where('position', '>', $this->position)
            ->orderBy('position', 'asc')
            ->first();
    }

    public function getAvailableRelationValues()
    {
        $related_model_name = $this->settings()->where('parameter', 'related_model_name')->first();
        $related_parent_node_id = $this->settings()->where('parameter', 'related_parent_node_id')->first();

        $values = [];

        if(empty($related_model_name->value))
        {
            return $values;
        }

        $values = M($related_model_name->value);

        if($related_parent_node_id->value)
        {
            $values = $values->where('parent_id', $related_parent_node_id->value);
        }

        $values = $values->get();

        return $values;
    }
}