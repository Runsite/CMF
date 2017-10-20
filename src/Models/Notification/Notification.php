<?php

namespace Runsite\CMF\Models\Notification;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Runsite\CMF\Models\{
    Node\Node,
    User\User
};

class Notification extends Eloquent
{
    protected $table = 'rs_notifications';
    protected $fillable = ['user_id', 'node_id', 'message'];

    public function node()
    {
        return $this->belongsTo(Node::class, 'node_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'node_id');
    }
}
