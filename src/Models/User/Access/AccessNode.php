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

    public static function assignRecursively($parent_id, $group_id, $access_level)
    {
        $nodes = Node::where('parent_id', $parent_id)->get();

        foreach($nodes as $node)
        {
            $access = AccessNode::where('node_id', $node->id)->where('group_id', $group_id)->first();

            if(! $access)
            {
                $access = AccessNode::create([
                    'node_id' => $node->id,
                    'group_id' => $group_id,
                    'access' => 0,
                ]);
            }

            $access->access = $access_level;
            $access->save();

            self::assignRecursively($node->id, $group_id, $access_level);
        }
    }
}
