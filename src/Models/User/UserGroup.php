<?php 

namespace Runsite\CMF\Models\User;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserGroup extends Eloquent
{
    protected $table = 'rs_user_group';
    protected $fillable = ['user_id', 'group_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_d');
    }
}