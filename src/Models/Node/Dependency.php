<?php 

namespace Runsite\CMF\Models\Node;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Dependency extends Eloquent
{
    protected $table = 'rs_node_dependencies';
    protected $fillable = ['node_id', 'depended_model_id'];

    public function node()
    {
        return $this->belongsTo(Node::class, 'node_id');
    }
}