<?php 

namespace Runsite\CMF\Models\User;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Invite extends Eloquent
{
    protected $table = 'rs_user_invite';
    protected $fillable = ['user_id', 'token', 'expires_at'];
    protected $dates = ['expires_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
