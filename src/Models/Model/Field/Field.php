<?php 

namespace Runsite\CMF\Models\Model\Field;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Schema;
use DB;
use Runsite\CMF\Models\Node\Relation;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\User\Access\AccessField;
use Runsite\CMF\Models\User\Group;

use Runsite\CMF\Models\Model\Field\FieldTypes\BooleanType;
use Runsite\CMF\Models\Model\Field\FieldTypes\DateTimeType;
use Runsite\CMF\Models\Model\Field\FieldTypes\DateType;
use Runsite\CMF\Models\Model\Field\FieldTypes\DecimalType;
use Runsite\CMF\Models\Model\Field\FieldTypes\ImageType;
use Runsite\CMF\Models\Model\Field\FieldTypes\RelationToManyType;
use Runsite\CMF\Models\Model\Field\FieldTypes\RelationToOneType;
use Runsite\CMF\Models\Model\Field\FieldTypes\ServerFileType;
use Runsite\CMF\Models\Model\Field\FieldTypes\StringType;
use Runsite\CMF\Models\Model\Field\FieldTypes\TextareaType;

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
        return $this->types[$this->type_id]::$displayName.'.'.$this->findSettings('control')->value;
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
                $table->{$type::$name}($field->name, $type::$size['base'], $type::$size['extra'])->nullable()->default($type::defaultValue());
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
                    $table->{$type::$name}($field->name, $type::$size['base'], $type::$size['extra'])->default($type::defaultValue())->change();
                }

                if($type::$needField and !$field->types[$field->type_id]::$needField)
                {
                    $table->{$type::$name}($field->name, $type::$size['base'], $type::$size['extra'])->default($type::defaultValue());
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
        Schema::table($this->model->tableName(), function($table)
        {
            $table->dropColumn([$this->name]);
        });

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
}