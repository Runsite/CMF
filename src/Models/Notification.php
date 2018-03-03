<?php 

namespace Runsite\CMF\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Runsite\CMF\Models\{
	Node\Node,
	User\User
};

class Notification extends Eloquent
{
	protected $table = 'rs_notifications';
	protected $fillable = ['node_id', 'user_id', 'is_reviewed', 'is_sounded', 'message', 'icon_name'];

	public function node()
	{
		return $this->belongsTo(Node::class, 'node_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
