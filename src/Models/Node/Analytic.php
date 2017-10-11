<?php 

namespace Runsite\CMF\Models\Node;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Runsite\CMF\Models\Model\Model;

class Analytic extends Eloquent
{
    protected $table = 'rs_node_analytics';
    protected $fillable = ['model_id', 'parent_node_id', 'type'];

    public function parentNode()
    {
        return $this->belongsTo(Node::class, 'parent_node_id');
    }

    public function model()
    {
        return $this->belongsTo(Model::class, 'model_id');
    }
}