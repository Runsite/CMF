<?php 

namespace Runsite\CMF\Models\Node;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Node\Node;

class Dependency extends Eloquent
{
    protected $table = 'rs_node_dependencies';
    protected $fillable = ['node_id', 'depended_model_id', 'position'];

    public function node()
    {
        return $this->belongsTo(Node::class, 'node_id');
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
        $lastPosition =  Dependency::where('node_id', $attributes['node_id'])->orderBy('position', 'desc')->first();
        if($lastPosition)
        {
            return $lastPosition->position + 1;
        }

        return 1;
    }

    public function delete()
    {
        // Calculating position
        Dependency::where('node_id', $this->node_id)->where('position', '>', $this->position)->decrement('position');

        foreach(Node::where('model_id', $this->depended_model_id)->where('parent_id', $this->node_id)->get() as $node)
        {
            $node->delete();
        }

        return parent::delete();
    }

    public function moveDown()
    {
        Dependency::where('node_id', $this->node_id)->where('position', '>', $this->position)->orderBy('position', 'asc')->first()->decrement('position');
        return $this->increment('position');
    }

    public function moveUp()
    {
        Dependency::where('node_id', $this->node_id)->where('position', '<', $this->position)->orderBy('position', 'desc')->first()->increment('position');
        return $this->decrement('position');
    }
}
