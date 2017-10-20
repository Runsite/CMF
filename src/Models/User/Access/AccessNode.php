<?php 

namespace Runsite\CMF\Models\User\Access;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Runsite\CMF\Models\{
    Node\Node,
    User\Group
};

class AccessNode extends Eloquent
{
    protected $table = 'rs_group_node_access';
    protected $fillable = ['group_id', 'node_id', 'access'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function node()
    {
        return $this->belongsTo(Node::class, 'node_id');
    }
}