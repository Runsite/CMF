<?php 

namespace Runsite\CMF\Models\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Dependency extends Eloquent
{
    protected $table = 'rs_model_dependencies';
    protected $fillable = ['model_id', 'depended_model_id', 'position'];

    public function model()
    {
        return $this->belongsTo(Model::class, 'model_id');
    }

    public function dependedModel()
    {
        return $this->belongsTo(Model::class, 'depended_model_id');
    }

    public static function create(array $attributes = [])
    {
        $attributes['position'] = self::getNewPosition($attributes);
        return parent::query()->create($attributes);
    }

    public static function getNewPosition(array $attributes = [])
    {
        $lastPosition =  Dependency::where('model_id', $attributes['model_id'])->orderBy('position', 'desc')->first();
        if($lastPosition)
        {
            return $lastPosition->position + 1;
        }

        return 1;
    }

    public function delete()
    {
        // Calculating position
        Dependency::where('model_id', $this->model_id)->where('position', '>', $this->position)->decrement('position');
        return parent::delete();
    }

    public function moveDown()
    {
        Dependency::where('model_id', $this->model_id)->where('position', '>', $this->position)->orderBy('position', 'asc')->first()->decrement('position');
        return $this->increment('position');
    }

    public function moveUp()
    {
        Dependency::where('model_id', $this->model_id)->where('position', '<', $this->position)->orderBy('position', 'desc')->first()->increment('position');
        return $this->decrement('position');
    }
}