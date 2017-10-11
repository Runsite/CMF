<?php 

namespace Runsite\CMF\Models\Model\Field;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Runsite\CMF\Models\Model;

class FieldGroup extends Eloquent
{
    protected $table = 'rs_field_groups';
    protected $fillable = ['model_id', 'name', 'position'];

    public function model()
    {
        return $this->belongsTo(Model::class, 'model_id');
    }

    public function moveDown()
    {
        FieldGroup::where('model_id', $this->model_id)->where('position', '>', $this->position)->orderBy('position', 'asc')->first()->decrement('position');
        return $this->increment('position');
    }

    public function moveUp()
    {
        FieldGroup::where('model_id', $this->model_id)->where('position', '<', $this->position)->orderBy('position', 'desc')->first()->increment('position');
        return $this->decrement('position');
    }

    public static function create(array $attributes = [])
    {
        $attributes['position'] = self::getNewPosition($attributes);
        $field = parent::query()->create($attributes);
        return $field;
    }

    public static function getNewPosition(array $attributes = [])
    {
        $lastPosition =  FieldGroup::where('model_id', $attributes['model_id'])->orderBy('position', 'desc')->first();
        if($lastPosition)
        {
            return $lastPosition->position + 1;
        }

        return 1;
    }
}