<?php 

namespace Runsite\CMF\Models\Node;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Method extends Eloquent
{
    protected $table = 'rs_node_methods';
    protected $fillable = ['node_id', 'get', 'post', 'patch', 'delete'];

    public function node()
    {
        return $this->belongsTo(Node::class, 'node_id');
    }
}