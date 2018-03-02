<?php

namespace Runsite\CMF\Models\Node;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Setting extends Eloquent
{
	protected $table = 'rs_node_settings';
	protected $fillable = ['node_id', 'use_response_cache', 'node_icon'];

	public function node()
	{
		return $this->belongsTo(Node::class, 'node_id');
	}
}
