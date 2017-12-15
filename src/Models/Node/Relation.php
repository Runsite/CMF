<?php

namespace Runsite\CMF\Models\Node;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Runsite\CMF\Models\Model\Field;

class Relation extends Eloquent
{
    protected $table = 'rs_node_relations';
    protected $fillable = ['language_id', 'node_id', 'field_id', 'related_node_id'];

    public function field()
    {
        return $this->belongsTo(Field::class, 'node_id');
    }

    public function relatedNode()
    {
        return $this->belongsTo(Node::class, 'related_node_id');
    }
}
